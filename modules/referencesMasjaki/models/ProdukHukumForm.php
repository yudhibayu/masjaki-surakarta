<?php

namespace app\modules\referencesMasjaki\models;

use yii\base\Model;

class ProdukHukumForm extends Model
{
    const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2 MB
    const JENIS_PRODUK_HUKUM = [
        'Peraturan Daerah' => 'Peraturan Daerah',
        'Peraturan Wali Kota' => 'Peraturan Wali Kota',
        'Keputusan Wali Kota' => 'Keputusan Wali Kota',
        'Keputusan Sekretaris Daerah' => 'Keputusan Sekretaris Daerah',
    ];

    public $master_produk_hukum_id;
    public $jenis_produk_hukum;
    public $tahun;
    public $is_public = 0;
    public $file;

    public function rules()
    {
        return [
            [['master_produk_hukum_id', 'jenis_produk_hukum', 'tahun'], 'required'],
            [['master_produk_hukum_id'], 'integer'],
            [['jenis_produk_hukum'], 'in', 'range' => array_keys(self::JENIS_PRODUK_HUKUM)],
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
            'jenis_produk_hukum' => 'Jenis Produk Hukum',
            'tahun' => 'Tahun',
            'is_public' => 'Publikasikan',
            'file' => 'Dokumen',
        ];
    }
}
