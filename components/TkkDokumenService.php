<?php

namespace app\components;

use app\modules\referencesMasjaki\models\DokumenPesertaSertifikasi;
use app\modules\referencesMasjaki\models\PesertaFasilitasiSertifikasi;
use app\modules\referencesMasjaki\models\TkkDokumenForm;
use Yii;

class TkkDokumenService
{
    public function upload(PesertaFasilitasiSertifikasi $peserta, TkkDokumenForm $form)
    {
        if (!$form->validate()) { return false; }
        $staged = [];
        $created = [];
        $transaction = null;
        $committed = false;
        try {
            foreach ($form->files as $file) {
                $extension = strtolower($file->extension);
                $temporary = TkkDokumenStorage::directory() . DIRECTORY_SEPARATOR . '.stage-' . bin2hex(random_bytes(16));
                if (!$file->saveAs($temporary)) {
                    throw new \RuntimeException('Gagal menyimpan unggahan.');
                }
                $staged[] = $temporary;
                if (!self::validContent($temporary, $extension)) {
                    $form->addError('files', 'Isi file tidak sesuai dengan format PDF, JPG, atau PNG.');
                    return false;
                }
                $name = TkkDokumenStorage::safeName($file->name);
                if (mb_strlen($name) > 255) {
                    $form->addError('files', 'Nama file terlalu panjang.');
                    return false;
                }
            }
            $transaction = Yii::$app->db->beginTransaction();
            foreach ($form->files as $index => $file) {
                $extension = strtolower($file->extension);
                $name = TkkDokumenStorage::newName($extension);
                $target = TkkDokumenStorage::directory() . DIRECTORY_SEPARATOR . $name;
                if (!rename($staged[$index], $target)) {
                    throw new \RuntimeException('Gagal memindahkan dokumen.');
                }
                $created[] = $target;
                $record = new DokumenPesertaSertifikasi();
                $record->peserta_sertifikasi_id = $peserta->id;
                $record->jenis_dokumen = $form->jenis_dokumen;
                $record->nama_file = TkkDokumenStorage::safeName($file->name);
                $record->file_path = $name;
                $record->mime_type = self::mimeFor($extension);
                $record->file_size = filesize($target);
                if (!$record->save()) {
                    throw new \RuntimeException('Gagal mencatat dokumen.');
                }
            }
            $transaction->commit();
            $committed = true;
            return true;
        } catch (\Throwable $error) {
            Yii::error('Unggah dokumen TKK gagal: ' . $error->getMessage(), __METHOD__);
            $form->addError('files', 'Dokumen gagal disimpan. Silakan coba lagi.');
            return false;
        } finally {
            if ($transaction !== null && $transaction->isActive) { $transaction->rollBack(); }
            foreach ($staged as $path) { if (is_file($path)) { @unlink($path); } }
            if (!$committed) { foreach ($created as $path) { @unlink($path); } }
        }
    }

    public function delete(DokumenPesertaSertifikasi $record)
    {
        $path = $record->file_path;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$record->delete()) { throw new \RuntimeException('Gagal menghapus metadata dokumen.'); }
            $transaction->commit();
        } catch (\Throwable $error) {
            if ($transaction->isActive) { $transaction->rollBack(); }
            throw $error;
        }
        if (!DokumenPesertaSertifikasi::find()->where(['file_path' => $path])->exists()) {
            try {
                @unlink(TkkDokumenStorage::path($path));
            } catch (\yii\web\NotFoundHttpException $ignored) {
            }
        }
    }

    public static function validContent($path, $extension)
    {
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($path);
        if ($extension === 'pdf') {
            return $mime === 'application/pdf' && file_get_contents($path, false, null, 0, 5) === '%PDF-';
        }
        if (!in_array($extension, ['jpg', 'jpeg', 'png'], true)) { return false; }
        $info = @getimagesize($path);
        $expected = $extension === 'png' ? IMAGETYPE_PNG : IMAGETYPE_JPEG;
        return $info !== false && $info[2] === $expected
            && $mime === ($expected === IMAGETYPE_PNG ? 'image/png' : 'image/jpeg');
    }

    private static function mimeFor($extension)
    {
        return $extension === 'pdf' ? 'application/pdf' : ($extension === 'png' ? 'image/png' : 'image/jpeg');
    }
}
