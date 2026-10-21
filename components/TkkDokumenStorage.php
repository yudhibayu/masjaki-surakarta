<?php

namespace app\components;

use app\modules\referencesMasjaki\models\DokumenPesertaSertifikasi;
use Yii;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

class TkkDokumenStorage
{
    const ROOT = '@app/storage/tkk-sertifikasi';

    public static function directory()
    {
        $path = Yii::getAlias(self::ROOT);
        FileHelper::createDirectory($path, 0750);
        return $path;
    }

    public static function newName($extension)
    {
        return bin2hex(random_bytes(16)) . '.' . strtolower($extension);
    }

    public static function path($name)
    {
        if (!is_string($name) || !preg_match('/\A[a-f0-9]{32}\.(pdf|jpg|jpeg|png)\z/', $name)) {
            throw new NotFoundHttpException('Dokumen tidak ditemukan.');
        }
        $root = realpath(self::directory());
        $file = $root === false ? false : realpath($root . DIRECTORY_SEPARATOR . $name);
        if ($file === false || dirname($file) !== $root || !is_file($file)) {
            throw new NotFoundHttpException('Dokumen tidak ditemukan.');
        }
        return $file;
    }

    public static function safeName($name)
    {
        $name = basename(str_replace('\\', '/', (string) $name));
        $name = preg_replace('/[\x00-\x1f\x7f]/', '', $name);
        return $name === '' ? 'dokumen' : $name;
    }

    public static function download($id)
    {
        $record = DokumenPesertaSertifikasi::findOne($id);
        if ($record === null || $record->peserta === null) {
            throw new NotFoundHttpException('Dokumen tidak ditemukan.');
        }
        $response = Yii::$app->response->sendFile(self::path($record->file_path), self::safeName($record->nama_file), [
            'inline' => false, 'mimeType' => $record->mime_type,
        ]);
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Cache-Control', 'no-store');
        return $response;
    }
}
