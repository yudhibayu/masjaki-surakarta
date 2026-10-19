<?php

namespace app\components;

use app\modules\referencesMasjaki\models\MasterProdukHukum;
use app\modules\referencesMasjaki\models\ProdukHukumForm;
use app\modules\referencesMasjaki\models\ProdukHukumTahunan;
use Yii;

class StandarHargaService
{
    public function save(ProdukHukumTahunan $record, ProdukHukumForm $form, $allowPublicationChange = false)
    {
        if (!$form->validate()) {
            return false;
        }
        $master = MasterProdukHukum::findOne($form->master_produk_hukum_id);
        if ($master === null || (!$master->is_active && ($record->isNewRecord || $form->file !== null
            || (int) $record->master_produk_hukum_id !== (int) $master->id))) {
            $form->addError('master_produk_hukum_id', 'Produk hukum tidak aktif atau tidak ditemukan.');
            return false;
        }
        if ($record->isNewRecord && $form->file === null) {
            $form->addError('file', 'Dokumen wajib diunggah.');
            return false;
        }

        $staged = null;
        $created = null;
        $transaction = null;
        $lockName = 'produk_hukum_' . $master->id . '_' . $form->tahun;
        $locked = false;
        $committed = false;
        $oldPath = null;
        try {
            if ($form->file !== null) {
                $extension = strtolower($form->file->extension);
                $staged = StandarHargaStorage::directory() . DIRECTORY_SEPARATOR . '.stage-' . bin2hex(random_bytes(16));
                if (!$form->file->saveAs($staged)) {
                    throw new \RuntimeException('Gagal menyimpan unggahan.');
                }
                if (!self::validContent($staged, $extension)) {
                    $form->addError('file', 'Isi file tidak sesuai dengan format PDF, XLS, atau XLSX.');
                    return false;
                }
            }
            $locked = (int) Yii::$app->db->createCommand('SELECT GET_LOCK(:name, 10)', [':name' => $lockName])->queryScalar() === 1;
            if (!$locked) {
                throw new \RuntimeException('Kunci unggahan tidak tersedia.');
            }
            $transaction = Yii::$app->db->beginTransaction();
            $activeNow = Yii::$app->db->createCommand(
                'SELECT is_active FROM {{%master_produk_hukum}} WHERE id=:id FOR UPDATE', [':id' => $master->id]
            )->queryScalar();
            if ($activeNow === false || (!(bool) $activeNow && ($record->isNewRecord || $form->file !== null
                || (int) $record->master_produk_hukum_id !== (int) $master->id))) {
                $form->addError('master_produk_hukum_id', 'Produk hukum tidak aktif atau tidak ditemukan.');
                return false;
            }
            $existing = ProdukHukumTahunan::findOne(['master_produk_hukum_id' => $master->id, 'tahun' => (int) $form->tahun]);
            if ($record->isNewRecord && $existing !== null) {
                $record = $existing;
            } elseif (!$record->isNewRecord && $existing !== null && (int) $existing->id !== (int) $record->id) {
                $form->addError('tahun', 'Kombinasi produk hukum dan tahun sudah ada.');
                return false;
            }
            if ($record->deleted_at !== null && $form->file === null) {
                $form->addError('file', 'Dokumen wajib diunggah untuk mengaktifkan kembali data.');
                return false;
            }
            if ($form->file !== null) {
                $name = StandarHargaStorage::newName($extension);
                $created = StandarHargaStorage::directory() . DIRECTORY_SEPARATOR . $name;
                if (!rename($staged, $created)) {
                    throw new \RuntimeException('Gagal memindahkan dokumen.');
                }
                $oldPath = $record->isNewRecord ? null : $record->file_path;
                $record->nama_file = StandarHargaStorage::safeDownloadName($form->file->name);
                $record->file_path = $name;
                $record->mime_type = self::canonicalMime($extension);
                $record->file_size = filesize($created);
            }
            $isNew = $record->isNewRecord;
            $record->master_produk_hukum_id = $master->id;
            $record->tahun = (int) $form->tahun;
            $record->deleted_at = null;
            if ($isNew || $allowPublicationChange) {
                $record->is_public = (int) $form->is_public;
            }
            if (!$record->save()) {
                foreach ($record->getErrors() as $attribute => $messages) {
                    foreach ($messages as $message) {
                        $form->addError($attribute, $message);
                    }
                }
                return false;
            }
            $transaction->commit();
            $committed = true;
            if ($oldPath !== null && $oldPath !== $record->file_path) {
                try {
                    $this->removeUnreferencedFile($oldPath);
                } catch (\Throwable $cleanupError) {
                    Yii::warning('File lama belum dapat dibersihkan: ' . $cleanupError->getMessage(), __METHOD__);
                }
            }
            return true;
        } catch (\Throwable $error) {
            Yii::error('Penyimpanan produk hukum gagal: ' . $error->getMessage(), __METHOD__);
            $form->addError('file', 'Data gagal disimpan. Silakan coba lagi.');
            return false;
        } finally {
            if ($transaction !== null && $transaction->isActive) {
                $transaction->rollBack();
            }
            if ($staged !== null && is_file($staged)) {
                @unlink($staged);
            }
            if (!$committed && $created !== null && is_file($created)) {
                @unlink($created);
            }
            if ($locked) {
                Yii::$app->db->createCommand('SELECT RELEASE_LOCK(:name)', [':name' => $lockName])->queryScalar();
            }
        }
    }

    public function delete(ProdukHukumTahunan $record)
    {
        $record->deleted_at = date('Y-m-d H:i:s');
        $record->is_public = 0;
        return $record->save(false);
    }

    private function removeUnreferencedFile($name)
    {
        if (ProdukHukumTahunan::find()->where(['file_path' => $name])->exists()) {
            return;
        }
        try {
            @unlink(StandarHargaStorage::path($name));
        } catch (\yii\web\NotFoundHttpException $ignored) {
        }
    }

    public static function validContent($path, $extension)
    {
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($path);
        $header = file_get_contents($path, false, null, 0, 8);
        if ($extension === 'pdf') {
            return $mime === 'application/pdf' && strncmp($header, '%PDF-', 5) === 0;
        }
        if ($extension === 'xls') {
            return in_array($mime, ['application/vnd.ms-excel', 'application/x-ole-storage', 'application/octet-stream'], true)
                && substr($header, 0, 8) === "\xD0\xCF\x11\xE0\xA1\xB1\x1A\xE1";
        }
        if ($extension === 'xlsx' && class_exists(\ZipArchive::class)
            && in_array($mime, ['application/zip', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'], true)) {
            $archive = new \ZipArchive();
            $valid = $archive->open($path) === true;
            if ($valid) {
                $valid = $archive->locateName('[Content_Types].xml') !== false
                    && $archive->locateName('xl/workbook.xml') !== false;
                $archive->close();
            }
            return $valid;
        }
        return false;
    }

    private static function canonicalMime($extension)
    {
        return [
            'pdf' => 'application/pdf',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ][$extension];
    }
}
