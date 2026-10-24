<?php

namespace app\modules\referencesMasjaki\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class JenisKegiatanPembinaan extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%jenis_kegiatan_pembinaan}}';
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
            [['nama'], 'required'],
            [['nama'], 'filter', 'filter' => 'trim'],
            [['nama'], 'string', 'max' => 120],
            [['nama'], 'unique', 'filter' => function ($query) { $query->andWhere(['deleted_at' => null]); }],
            [['is_active'], 'boolean'],
            [['is_active'], 'default', 'value' => 1],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return ['nama' => 'Nama Jenis Kegiatan', 'is_active' => 'Aktif'];
    }

    public function getKegiatan()
    {
        return $this->hasMany(KegiatanPembinaan::class, ['jenis_kegiatan_id' => 'id']);
    }
}
