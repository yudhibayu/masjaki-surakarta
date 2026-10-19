<?php

namespace app\components;

use Yii;
use app\modules\referencesMasjaki\models\ProdukHukumTahunan;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

class StandarHargaStorage
{
    const ROOT = '@app/storage/standar-harga';

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
        if (!is_string($name) || !preg_match('/\A[a-f0-9]{32}\.(pdf|xls|xlsx)\z/', $name)) {
            throw new NotFoundHttpException('Dokumen tidak ditemukan.');
        }
        $root = realpath(self::directory());
        $file = realpath($root . DIRECTORY_SEPARATOR . $name);
        if ($root === false || $file === false || dirname($file) !== $root || !is_file($file)) {
            throw new NotFoundHttpException('Dokumen tidak ditemukan.');
        }
        return $file;
    }

    public static function safeDownloadName($name)
    {
        $name = basename(str_replace('\\', '/', (string) $name));
        $name = str_replace(["\r", "\n", "\0"], '', $name);
        return $name === '' ? 'dokumen' : $name;
    }

    public static function send($id, $inline, $requirePublic)
    {
        $document = ProdukHukumTahunan::findOne(['id' => $id, 'deleted_at' => null]);
        if ($document === null || $document->master === null || ($requirePublic && !(bool) $document->is_public)) {
            throw new NotFoundHttpException('Dokumen tidak ditemukan.');
        }
        if ($inline && $document->mime_type !== 'application/pdf') {
            throw new NotFoundHttpException('Pratinjau hanya tersedia untuk PDF.');
        }
        $path = self::path($document->file_path);
        $response = Yii::$app->response->sendFile(
            $path,
            self::safeDownloadName($document->nama_file),
            ['inline' => $inline, 'mimeType' => $document->mime_type]
        );
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Cache-Control', 'no-store');
        return $response;
    }
}
