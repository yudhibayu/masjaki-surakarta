<?php

namespace app\components;

use app\modules\monitoring\models\TertibDokumen;
use Yii;

class TertibDokumenService
{
    public function upload(TertibDokumen $document)
    {
        if (!$document->validate()) {
            return false;
        }

        $file = $document->upload;
        $extension = strtolower($file->extension);
        $staged = TertibDokumenStorage::directory() . DIRECTORY_SEPARATOR . '.stage-' . bin2hex(random_bytes(16));
        $target = null;
        $transaction = null;
        $transactionLevel = Yii::$app->db->getTransaction()
            ? Yii::$app->db->getTransaction()->getLevel()
            : 0;
        $committed = false;

        try {
            if (!$file->saveAs($staged)) {
                throw new \RuntimeException('Gagal menyimpan unggahan sementara.');
            }
            if (!self::validContent($staged, $extension)) {
                $document->addError('upload', 'Isi file tidak sesuai dengan format PDF, JPG, atau PNG.');
                return false;
            }

            $originalName = TertibDokumenStorage::safeName($file->name);
            if (mb_strlen($originalName) > 255) {
                $document->addError('upload', 'Nama file terlalu panjang.');
                return false;
            }

            $fileName = TertibDokumenStorage::newName($extension);
            $target = TertibDokumenStorage::directory() . DIRECTORY_SEPARATOR . $fileName;
            if (!rename($staged, $target)) {
                throw new \RuntimeException('Gagal memindahkan file ke penyimpanan privat.');
            }

            $document->nama_file = $originalName;
            $document->file_path = $fileName;
            $document->mime_type = (new \finfo(FILEINFO_MIME_TYPE))->file($target);
            $document->file_size = (int) filesize($target);
            $transaction = Yii::$app->db->beginTransaction();
            if (!$document->save(false)) {
                throw new \RuntimeException('Gagal menyimpan metadata dokumen.');
            }
            $transaction->commit();
            $committed = true;
            return true;
        } catch (\Throwable $error) {
            Yii::error('Unggah dokumen pengawasan tertib gagal: ' . $error->getMessage(), __METHOD__);
            $document->addError('upload', 'Dokumen gagal disimpan. Silakan coba lagi.');
            return false;
        } finally {
            if ($transaction !== null && $transaction->isActive && $transaction->getLevel() > $transactionLevel) {
                $transaction->rollBack();
            }
            if (is_file($staged)) {
                @unlink($staged);
            }
            if (!$committed && $target !== null && is_file($target)) {
                @unlink($target);
            }
        }
    }

    public function delete(TertibDokumen $document)
    {
        $path = $document->file_path;
        $transactionLevel = Yii::$app->db->getTransaction()
            ? Yii::$app->db->getTransaction()->getLevel()
            : 0;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$document->delete()) {
                throw new \RuntimeException('Gagal menghapus metadata dokumen.');
            }
            $transaction->commit();
        } catch (\Throwable $error) {
            if ($transaction->isActive && $transaction->getLevel() > $transactionLevel) {
                $transaction->rollBack();
            }
            throw $error;
        }
        try {
            @unlink(TertibDokumenStorage::path($path));
        } catch (\yii\web\NotFoundHttpException $ignored) {
        }
    }

    public static function validContent($path, $extension)
    {
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($path);
        if ($extension === 'pdf') {
            return $mime === 'application/pdf' && file_get_contents($path, false, null, 0, 5) === '%PDF-';
        }
        if (!in_array($extension, ['jpg', 'jpeg', 'png'], true)) {
            return false;
        }
        $info = @getimagesize($path);
        $expected = $extension === 'png' ? IMAGETYPE_PNG : IMAGETYPE_JPEG;
        return $info !== false && $info[2] === $expected
            && $mime === ($expected === IMAGETYPE_PNG ? 'image/png' : 'image/jpeg');
    }
}
