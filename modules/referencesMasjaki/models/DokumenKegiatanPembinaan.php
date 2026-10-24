<?php

namespace app\modules\referencesMasjaki\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class DokumenKegiatanPembinaan extends ActiveRecord
{
    const JENIS = [
        'LAPORAN' => 'Laporan Kegiatan',
        'FOTO' => 'Dokumentasi Foto',
        'DAFTAR_HADIR' => 'Daftar Hadir',
        'PENDUKUNG' => 'Dokumen Pendukung',
    ];

    public $upload;

    public static function tableName()
    {
        return '{{%dokumen_kegiatan_pembinaan}}';
    }

    public function behaviors()
    {
        return [[
            'class' => TimestampBehavior::class,
            'attributes' => [ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']],
            'value' => date('Y-m-d H:i:s'),
        ]];
    }

    public function rules()
    {
        return [
            [['kegiatan_id', 'jenis_dokumen'], 'required'],
            [['kegiatan_id', 'file_size'], 'integer'],
            [['jenis_dokumen'], 'in', 'range' => array_keys(self::JENIS)],
            [['nama_file'], 'string', 'max' => 255],
            [['file_path'], 'string', 'max' => 100],
            [['mime_type'], 'string', 'max' => 120],
            [['upload'], 'required'],
            [['upload'], 'file', 'extensions' => ['pdf', 'jpg', 'jpeg', 'png', 'xls', 'xlsx'], 'checkExtensionByMimeType' => false, 'maxSize' => 10 * 1024 * 1024, 'tooBig' => 'Ukuran file maksimal 10 MB.'],
            [['kegiatan_id'], 'exist', 'targetClass' => KegiatanPembinaan::class, 'targetAttribute' => ['kegiatan_id' => 'id'], 'filter' => ['deleted_at' => null]],
            [['created_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return ['kegiatan_id' => 'Kegiatan', 'jenis_dokumen' => 'Jenis Dokumen', 'upload' => 'File (maksimal 10 MB)'];
    }

    public function getKegiatan()
    {
        return $this->hasOne(KegiatanPembinaan::class, ['id' => 'kegiatan_id']);
    }
}
