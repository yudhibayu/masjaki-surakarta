<?php

namespace app\modules\referencesMasjaki\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class KegiatanPembinaan extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%kegiatan_pembinaan}}';
    }

    public function behaviors()
    {
        return [[
            'class' => TimestampBehavior::class,
            'attributes' => [ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'], ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at']],
            'value' => date('Y-m-d H:i:s'),
        ]];
    }

    public function rules()
    {
        return [
            [['jenis_kegiatan_id', 'tahun_anggaran', 'nama_kegiatan'], 'required'],
            [['jenis_kegiatan_id'], 'integer'],
            [['tahun_anggaran'], 'integer', 'min' => 2000, 'max' => 2100],
            [['nama_kegiatan'], 'filter', 'filter' => 'trim'],
            [['nama_kegiatan'], 'string', 'max' => 200],
            [['tanggal_mulai', 'tanggal_selesai'], 'date', 'format' => 'php:Y-m-d'],
            [['tanggal_selesai'], 'validateDateRange'],
            [['lokasi'], 'string', 'max' => 255],
            [['penyelenggara'], 'string', 'max' => 180],
            [['keterangan'], 'string'],
            [['is_published'], 'boolean'],
            [['is_published'], 'default', 'value' => 0],
            [['jenis_kegiatan_id'], 'exist', 'targetClass' => JenisKegiatanPembinaan::class, 'targetAttribute' => ['jenis_kegiatan_id' => 'id'], 'filter' => ['deleted_at' => null]],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function validateDateRange($attribute)
    {
        if ($this->tanggal_mulai && $this->tanggal_selesai && $this->tanggal_selesai < $this->tanggal_mulai) {
            $this->addError($attribute, 'Tanggal selesai harus sama atau setelah tanggal mulai.');
        }
    }

    public function attributeLabels()
    {
        return [
            'jenis_kegiatan_id' => 'Jenis Kegiatan', 'tahun_anggaran' => 'Tahun Anggaran',
            'nama_kegiatan' => 'Nama Kegiatan', 'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai', 'lokasi' => 'Lokasi',
            'penyelenggara' => 'Penyelenggara', 'keterangan' => 'Keterangan',
            'is_published' => 'Publikasikan',
        ];
    }

    public function getJenisKegiatan()
    {
        return $this->hasOne(JenisKegiatanPembinaan::class, ['id' => 'jenis_kegiatan_id']);
    }

    public function getPeserta()
    {
        return $this->hasMany(PesertaPembinaan::class, ['kegiatan_id' => 'id'])->andWhere(['deleted_at' => null]);
    }

    public function getDokumen()
    {
        return $this->hasMany(DokumenKegiatanPembinaan::class, ['kegiatan_id' => 'id']);
    }
}
