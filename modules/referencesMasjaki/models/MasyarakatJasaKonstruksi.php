<?php

namespace app\modules\referencesMasjaki\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class MasyarakatJasaKonstruksi extends ActiveRecord
{
    const PERSEORANGAN = 'PERSEORANGAN';
    const KELOMPOK = 'KELOMPOK';

    public static function tableName()
    {
        return '{{%masyarakat_jasa_konstruksi}}';
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
            [['jenis_subjek', 'nama'], 'required'],
            [['jenis_subjek'], 'in', 'range' => [self::PERSEORANGAN, self::KELOMPOK]],
            [['nama'], 'filter', 'filter' => 'trim'],
            [['nama'], 'string', 'max' => 200],
            [['instansi'], 'string', 'max' => 180],
            [['jabatan'], 'string', 'max' => 150],
            [['nomor_telepon'], 'string', 'max' => 30],
            [['nik'], 'match', 'pattern' => '/^[0-9]{16}$/', 'skipOnEmpty' => true, 'message' => 'NIK harus terdiri dari 16 digit.'],
            [['nik'], 'unique', 'filter' => function ($query) {
                $query->andWhere(['jenis_subjek' => self::PERSEORANGAN, 'deleted_at' => null]);
            }, 'skipOnEmpty' => true],
            [['is_active'], 'boolean'],
            [['is_active'], 'default', 'value' => 1],
            [['alamat'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function beforeValidate()
    {
        if ($this->jenis_subjek === self::KELOMPOK) {
            $this->nik = null;
        }
        return parent::beforeValidate();
    }

    public function attributeLabels()
    {
        return [
            'jenis_subjek' => 'Jenis Subjek', 'nama' => 'Nama Perseorangan / Kelompok',
            'nik' => 'NIK (opsional)', 'instansi' => 'Instansi', 'jabatan' => 'Jabatan',
            'alamat' => 'Alamat', 'nomor_telepon' => 'Nomor Telepon', 'is_active' => 'Aktif',
        ];
    }

    public function getPesertaPembinaan()
    {
        return $this->hasMany(PesertaPembinaan::class, ['masyarakat_jasa_konstruksi_id' => 'id']);
    }
}
