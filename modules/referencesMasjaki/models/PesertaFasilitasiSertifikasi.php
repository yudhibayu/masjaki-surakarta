<?php

namespace app\modules\referencesMasjaki\models;

class PesertaFasilitasiSertifikasi extends TkkRecord
{
    const JENIS = ['SERTIFIKASI_BARU' => 'Sertifikasi Baru', 'PERPANJANGAN' => 'Perpanjangan', 'PENINGKATAN_JENJANG' => 'Peningkatan Jenjang'];
    const STATUS = [
        'TERDAFTAR' => 'Terdaftar', 'MENGIKUTI_UJI' => 'Mengikuti Uji', 'KOMPETEN' => 'Kompeten',
        'BELUM_KOMPETEN' => 'Belum Kompeten', 'TIDAK_HADIR' => 'Tidak Hadir', 'DIBATALKAN' => 'Dibatalkan',
    ];

    public static function tableName()
    {
        return '{{%peserta_fasilitasi_sertifikasi}}';
    }

    public function rules()
    {
        return [
            [['jabatan_kerja', 'skema_sertifikasi', 'jenjang_kualifikasi', 'nomor_skk'], 'filter', 'filter' => function ($value) { return $value === null ? null : trim($value); }],
            [['tenaga_kerja_id', 'kegiatan_id', 'jabatan_kerja', 'skema_sertifikasi', 'jenis_fasilitasi', 'status_sertifikasi'], 'required'],
            [['tenaga_kerja_id', 'kegiatan_id'], 'integer'],
            [['tenaga_kerja_id'], 'exist', 'targetClass' => TenagaKerjaKonstruksi::class, 'targetAttribute' => ['tenaga_kerja_id' => 'id']],
            [['kegiatan_id'], 'exist', 'targetClass' => KegiatanFasilitasiSertifikasi::class, 'targetAttribute' => ['kegiatan_id' => 'id']],
            [['jabatan_kerja'], 'string', 'max' => 150],
            [['jenjang_kualifikasi'], 'string', 'max' => 100],
            [['skema_sertifikasi'], 'string', 'max' => 180],
            [['jenis_fasilitasi'], 'in', 'range' => array_keys(self::JENIS)],
            [['status_sertifikasi'], 'in', 'range' => array_keys(self::STATUS)],
            [['nomor_skk'], 'string', 'max' => 120],
            [['tanggal_terbit_skk', 'masa_berlaku_skk_hingga'], 'date', 'format' => 'php:Y-m-d'],
            [['nomor_skk'], 'validateSkk'],
            [['keterangan'], 'string'],
        ];
    }

    public function validateSkk($attribute)
    {
        $hasNumber = trim((string) $this->nomor_skk) !== '';
        $hasIssueDate = (string) $this->tanggal_terbit_skk !== '';
        if ($hasNumber !== $hasIssueDate) {
            $this->addError($attribute, 'Nomor SKK dan tanggal terbit harus diisi bersama setelah penerbitan terkonfirmasi.');
        }
        if ($this->masa_berlaku_skk_hingga && !$hasIssueDate) {
            $this->addError('masa_berlaku_skk_hingga', 'Tanggal terbit SKK wajib diisi.');
        } elseif ($this->masa_berlaku_skk_hingga && $hasIssueDate && $this->masa_berlaku_skk_hingga < $this->tanggal_terbit_skk) {
            $this->addError('masa_berlaku_skk_hingga', 'Masa berlaku harus setelah tanggal terbit.');
        }
    }

    public function attributeLabels()
    {
        return [
            'tenaga_kerja_id' => 'Tenaga Kerja', 'kegiatan_id' => 'Kegiatan', 'jabatan_kerja' => 'Jabatan Kerja',
            'jenjang_kualifikasi' => 'Jenjang Kualifikasi', 'skema_sertifikasi' => 'Skema Sertifikasi',
            'jenis_fasilitasi' => 'Jenis Fasilitasi', 'status_sertifikasi' => 'Status Sertifikasi',
            'nomor_skk' => 'Nomor SKK', 'tanggal_terbit_skk' => 'Tanggal Terbit SKK',
            'masa_berlaku_skk_hingga' => 'Masa Berlaku SKK Hingga', 'keterangan' => 'Keterangan',
        ];
    }

    public function getTenagaKerja()
    {
        return $this->hasOne(TenagaKerjaKonstruksi::class, ['id' => 'tenaga_kerja_id']);
    }

    public function getKegiatan()
    {
        return $this->hasOne(KegiatanFasilitasiSertifikasi::class, ['id' => 'kegiatan_id']);
    }

    public function getDokumen()
    {
        return $this->hasMany(DokumenPesertaSertifikasi::class, ['peserta_sertifikasi_id' => 'id']);
    }
}
