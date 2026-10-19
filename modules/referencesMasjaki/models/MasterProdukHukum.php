<?php

namespace app\modules\referencesMasjaki\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class MasterProdukHukum extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%master_produk_hukum}}';
    }

    public function behaviors()
    {
        return [[
            'class' => TimestampBehavior::class,
            'attributes' => [
                self::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                self::EVENT_BEFORE_UPDATE => ['updated_at'],
            ],
            'value' => function () { return date('Y-m-d H:i:s'); },
        ]];
    }

    public function rules()
    {
        return [
            [['nama'], 'filter', 'filter' => 'trim'],
            [['nama'], 'required'],
            [['nama'], 'string', 'max' => 100],
            [['nama'], 'unique'],
            [['is_active'], 'boolean'],
            [['is_active'], 'default', 'value' => 1],
        ];
    }

    public function getDokumen()
    {
        return $this->hasMany(ProdukHukumTahunan::class, ['master_produk_hukum_id' => 'id'])
            ->andWhere(['produk_hukum_tahunan.deleted_at' => null]);
    }
}
