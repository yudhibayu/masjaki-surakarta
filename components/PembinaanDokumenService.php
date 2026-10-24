<?php

namespace app\components;

use app\modules\referencesMasjaki\models\DokumenKegiatanPembinaan;
use Yii;

class PembinaanDokumenService
{
    public function upload(DokumenKegiatanPembinaan $document)
    {
        if (!$document->validate()) {
            return false;
        }
        $file = $document->upload;
        $extension = strtolower($file->extension);
        $staged = PembinaanDokumenStorage::directory() . DIRECTORY_SEPARATOR . '.stage-' . bin2hex(random_bytes(16));
        $target = null;
        $transaction = null;
        $committed = false;
        try {
            if (!$file->saveAs($staged)) {
                throw new \RuntimeException('Gagal menyimpan unggahan sementara.');
            }
            if (!PembinaanDokumenStorage::validContent($staged, $extension)) {
                $document->addError('upload', 'Isi file tidak sesuai dengan format yang dipilih.');
                return false;
            }
            $originalName = PembinaanDokumenStorage::safeName($file->name);
            if (mb_strlen($originalName) > 255) {
                $document->addError('upload', 'Nama file terlalu panjang.');
                return false;
            }
            $fileName = PembinaanDokumenStorage::newName($extension);
            $target = PembinaanDokumenStorage::directory() . DIRECTORY_SEPARATOR . $fileName;
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
            Yii::error('Unggah dokumen pembinaan gagal: ' . $error->getMessage(), __METHOD__);
            $document->addError('upload', 'Dokumen gagal disimpan. Silakan coba lagi.');
            return false;
        } finally {
            if ($transaction !== null && $transaction->isActive) {
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

    public function delete(DokumenKegiatanPembinaan $document)
    {
        $path = $document->file_path;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$document->delete()) {
                throw new \RuntimeException('Gagal menghapus metadata dokumen.');
            }
            $transaction->commit();
        } catch (\Throwable $error) {
            if ($transaction->isActive) {
                $transaction->rollBack();
            }
            throw $error;
        }
        try {
            @unlink(PembinaanDokumenStorage::path($path));
        } catch (\yii\web\NotFoundHttpException $ignored) {
        }
    }
}
