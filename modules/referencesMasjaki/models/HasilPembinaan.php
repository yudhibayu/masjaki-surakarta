<?php

namespace app\modules\referencesMasjaki\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class HasilPembinaan extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%hasil_pembinaan}}';
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
            [['peserta_pembinaan_id'], 'required'],
            [['peserta_pembinaan_id'], 'integer'],
            [['peserta_pembinaan_id'], 'exist', 'targetClass' => PesertaPembinaan::class, 'targetAttribute' => ['peserta_pembinaan_id' => 'id'], 'filter' => ['deleted_at' => null]],
            [['peserta_pembinaan_id'], 'unique'],
            [['memperoleh_sertifikat'], 'in', 'range' => [0, 1, '0', '1'], 'skipOnEmpty' => true],
            [['hasil_capaian', 'keterangan_tindak_lanjut'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'peserta_pembinaan_id' => 'Peserta', 'hasil_capaian' => 'Hasil Capaian',
            'memperoleh_sertifikat' => 'Memperoleh Sertifikat', 'keterangan_tindak_lanjut' => 'Keterangan Tindak Lanjut',
        ];
    }

    public function getPeserta()
    {
        return $this->hasOne(PesertaPembinaan::class, ['id' => 'peserta_pembinaan_id']);
    }
}
