<?php

namespace app\modules\referencesMasjaki\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class ProdukHukumTahunan extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%produk_hukum_tahunan}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    self::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => function () { return date('Y-m-d H:i:s'); },
            ],
            BlameableBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            [['master_produk_hukum_id', 'tahun', 'nama_file', 'file_path'], 'required'],
            [['master_produk_hukum_id', 'file_size', 'created_by', 'updated_by', 'legacy_dokumen_id'], 'integer'],
            [['tahun'], 'integer', 'min' => 2000, 'max' => 2100],
            [['is_public'], 'boolean'],
            [['nama_file', 'file_path'], 'string', 'max' => 255],
            [['mime_type'], 'string', 'max' => 127],
            [['master_produk_hukum_id'], 'exist', 'targetClass' => MasterProdukHukum::class, 'targetAttribute' => ['master_produk_hukum_id' => 'id']],
            [['tahun'], 'unique', 'targetAttribute' => ['master_produk_hukum_id', 'tahun']],
        ];
    }

    public function getMaster()
    {
        return $this->hasOne(MasterProdukHukum::class, ['id' => 'master_produk_hukum_id']);
    }
}
