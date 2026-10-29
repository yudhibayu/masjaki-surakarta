<?php

namespace app\components;

use Yii;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

class TertibDokumenStorage
{
    const ROOT = '@app/storage/pengawasan-tertib';

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
}
