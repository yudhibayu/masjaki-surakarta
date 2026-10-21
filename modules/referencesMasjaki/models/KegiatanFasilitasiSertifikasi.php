<?php

namespace app\modules\referencesMasjaki\models;

class KegiatanFasilitasiSertifikasi extends TkkRecord
{
    public static function tableName()
    {
        return '{{%kegiatan_fasilitasi_sertifikasi}}';
    }

    public function rules()
    {
        return [
            [['nama_kegiatan', 'lokasi_kegiatan'], 'filter', 'filter' => function ($value) { return $value === null ? null : trim($value); }],
            [['nama_kegiatan', 'tahun_anggaran', 'tanggal_mulai', 'tanggal_selesai', 'lsp_id'], 'required'],
            [['nama_kegiatan'], 'string', 'max' => 200],
            [['lokasi_kegiatan'], 'string', 'max' => 255],
            [['tahun_anggaran'], 'integer', 'min' => 2000, 'max' => 2100],
            [['lsp_id'], 'integer'],
            [['is_public'], 'boolean'],
            [['is_public'], 'default', 'value' => 0],
            [['lsp_id'], 'exist', 'targetClass' => MasterLsp::class, 'targetAttribute' => ['lsp_id' => 'id']],
            [['tanggal_mulai', 'tanggal_selesai'], 'date', 'format' => 'php:Y-m-d'],
            [['tanggal_selesai'], 'validateDateRange'],
            [['keterangan'], 'string'],
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
            'nama_kegiatan' => 'Nama Kegiatan', 'tahun_anggaran' => 'Tahun Anggaran',
            'tanggal_mulai' => 'Tanggal Mulai', 'tanggal_selesai' => 'Tanggal Selesai',
            'lokasi_kegiatan' => 'Lokasi Kegiatan', 'lsp_id' => 'LSP', 'keterangan' => 'Keterangan',
            'is_public' => 'Tampilkan di Web Umum',
        ];
    }

    public function getLsp()
    {
        return $this->hasOne(MasterLsp::class, ['id' => 'lsp_id']);
    }

    public function getPeserta()
    {
        return $this->hasMany(PesertaFasilitasiSertifikasi::class, ['kegiatan_id' => 'id']);
    }
}
