<?php

namespace app\modules\referencesMasjaki\models;

class TenagaKerjaKonstruksi extends TkkRecord
{
    public static function tableName()
    {
        return '{{%tenaga_kerja_konstruksi}}';
    }

    public function rules()
    {
        return [
            [['nik', 'nama_lengkap'], 'filter', 'filter' => function ($value) { return $value === null ? null : trim($value); }],
            [['nik', 'nama_lengkap'], 'required'],
            [['nik'], 'match', 'pattern' => '/^[0-9]{16}$/', 'message' => 'NIK harus terdiri dari 16 digit.'],
            [['nik'], 'unique'],
            [['nama_lengkap', 'asal_instansi'], 'string', 'max' => 150],
            [['jenis_kelamin'], 'in', 'range' => ['L', 'P']],
            [['tanggal_lahir'], 'date', 'format' => 'php:Y-m-d'],
            [['alamat'], 'string'],
            [['nomor_telepon'], 'string', 'max' => 30],
            [['pendidikan_terakhir'], 'string', 'max' => 100],
        ];
    }

    public function attributeLabels()
    {
        return [
            'nik' => 'NIK', 'nama_lengkap' => 'Nama Lengkap', 'jenis_kelamin' => 'Jenis Kelamin',
            'tanggal_lahir' => 'Tanggal Lahir', 'alamat' => 'Alamat', 'nomor_telepon' => 'Nomor Telepon',
            'pendidikan_terakhir' => 'Pendidikan Terakhir', 'asal_instansi' => 'Asal Instansi',
        ];
    }

    public function getKeikutsertaan()
    {
        return $this->hasMany(PesertaFasilitasiSertifikasi::class, ['tenaga_kerja_id' => 'id']);
    }

    public function getNikTersamar()
    {
        return str_repeat('•', 12) . substr((string) $this->nik, -4);
    }
}
