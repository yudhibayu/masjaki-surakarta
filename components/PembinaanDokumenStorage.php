<?php

namespace app\components;

use Yii;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

class PembinaanDokumenStorage
{
    const ROOT = '@app/storage/pembinaan';

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
        if (!is_string($name) || !preg_match('/\A[a-f0-9]{32}\.(pdf|jpg|jpeg|png|xls|xlsx)\z/', $name)) {
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

    public static function validContent($path, $extension)
    {
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($path);
        if ($extension === 'pdf') {
            return $mime === 'application/pdf' && file_get_contents($path, false, null, 0, 5) === '%PDF-';
        }
        if (in_array($extension, ['jpg', 'jpeg', 'png'], true)) {
            $info = @getimagesize($path);
            $expected = $extension === 'png' ? IMAGETYPE_PNG : IMAGETYPE_JPEG;
            $expectedMime = $expected === IMAGETYPE_PNG ? 'image/png' : 'image/jpeg';
            return $info !== false && $info[2] === $expected && $mime === $expectedMime;
        }
        $header = file_get_contents($path, false, null, 0, 8);
        if ($extension === 'xls') {
            return $header === "\xD0\xCF\x11\xE0\xA1\xB1\x1A\xE1";
        }
        return $extension === 'xlsx' && substr($header, 0, 4) === "PK\x03\x04";
    }
}
