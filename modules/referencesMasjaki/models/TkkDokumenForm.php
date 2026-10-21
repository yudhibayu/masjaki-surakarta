<?php

namespace app\modules\referencesMasjaki\models;

use yii\base\Model;

class TkkDokumenForm extends Model
{
    const MAX_FILE_SIZE = 5 * 1024 * 1024;

    public $jenis_dokumen;
    public $files;

    public function rules()
    {
        return [
            [['jenis_dokumen'], 'required'],
            [['jenis_dokumen'], 'in', 'range' => array_keys(DokumenPesertaSertifikasi::JENIS)],
            [['files'], 'file', 'skipOnEmpty' => false, 'extensions' => ['pdf', 'jpg', 'jpeg', 'png'],
                'maxSize' => self::MAX_FILE_SIZE, 'maxFiles' => 10, 'checkExtensionByMimeType' => false],
        ];
    }

    public function attributeLabels()
    {
        return ['jenis_dokumen' => 'Jenis Dokumen', 'files' => 'File'];
    }
}
