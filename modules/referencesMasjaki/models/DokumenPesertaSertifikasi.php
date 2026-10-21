<?php

namespace app\modules\referencesMasjaki\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class DokumenPesertaSertifikasi extends ActiveRecord
{
    const JENIS = [
        'KTP' => 'KTP', 'FORMULIR' => 'Formulir', 'DAFTAR_HADIR' => 'Daftar Hadir',
        'FOTO_KEGIATAN' => 'Foto Kegiatan', 'BERITA_ACARA' => 'Berita Acara',
        'SKK' => 'SKK', 'LAINNYA' => 'Lainnya',
    ];

    public static function tableName()
    {
        return '{{%dokumen_peserta_sertifikasi}}';
    }

    public function behaviors()
    {
        return [[
            'class' => TimestampBehavior::class,
            'attributes' => [self::EVENT_BEFORE_INSERT => ['created_at']],
            'value' => function () { return date('Y-m-d H:i:s'); },
        ]];
    }

    public function rules()
    {
        return [
            [['peserta_sertifikasi_id', 'jenis_dokumen', 'nama_file', 'file_path', 'mime_type', 'file_size'], 'required'],
            [['peserta_sertifikasi_id', 'file_size'], 'integer'],
            [['peserta_sertifikasi_id'], 'exist', 'targetClass' => PesertaFasilitasiSertifikasi::class,
                'targetAttribute' => ['peserta_sertifikasi_id' => 'id']],
            [['jenis_dokumen'], 'in', 'range' => array_keys(self::JENIS)],
            [['nama_file', 'file_path'], 'string', 'max' => 255],
            [['mime_type'], 'string', 'max' => 100],
        ];
    }

    public function getPeserta()
    {
        return $this->hasOne(PesertaFasilitasiSertifikasi::class, ['id' => 'peserta_sertifikasi_id']);
    }
}
