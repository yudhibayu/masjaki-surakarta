<?php

namespace app\modules\referencesMasjaki\models;

use yii\base\Model;

class ProdukHukumForm extends Model
{
    const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2 MB

    public $master_produk_hukum_id;
    public $tahun;
    public $is_public = 0;
    public $file;

    public function rules()
    {
        return [
            [['master_produk_hukum_id', 'tahun'], 'required'],
            [['master_produk_hukum_id'], 'integer'],
            [['tahun'], 'integer', 'min' => 2000, 'max' => 2100],
            [['is_public'], 'boolean'],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => ['pdf', 'xls', 'xlsx'],
                'maxSize' => self::MAX_FILE_SIZE, 'checkExtensionByMimeType' => false],
        ];
    }

    public function attributeLabels()
    {
        return [
            'master_produk_hukum_id' => 'Nama Produk Hukum',
            'tahun' => 'Tahun',
            'is_public' => 'Publikasikan',
            'file' => 'Dokumen',
        ];
    }
}
