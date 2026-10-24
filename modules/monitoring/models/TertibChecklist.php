<?php

namespace app\modules\monitoring\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class TertibChecklist extends ActiveRecord
{
    const LENGKAP = 'LENGKAP';
    const TIDAK_LENGKAP = 'TIDAK_LENGKAP';
    const TIDAK_RELEVAN = 'TIDAK_RELEVAN';

    public static function tableName()
    {
        return '{{%pengawasan_tertib_checklist}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => date('Y-m-d H:i:s'),
            ],
            ['class' => BlameableBehavior::class],
        ];
    }

    public function rules()
    {
        return [
            [['pengawasan_id', 'nama_persyaratan', 'status_pemeriksaan'], 'required'],
            [['pengawasan_id', 'created_by', 'updated_by'], 'integer'],
            [['catatan'], 'string'],
            [['nama_persyaratan'], 'string', 'max' => 200],
            [['status_pemeriksaan'], 'in', 'range' => array_keys(self::statusOptions())],
            [['created_at', 'updated_at'], 'safe'],
            [['pengawasan_id'], 'exist', 'skipOnError' => true, 'targetClass' => TertibPengawasan::class, 'targetAttribute' => ['pengawasan_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'nama_persyaratan' => 'Persyaratan atau dokumen',
            'status_pemeriksaan' => 'Hasil pemeriksaan',
            'catatan' => 'Catatan pemeriksaan',
        ];
    }

    public static function statusOptions()
    {
        return [
            self::LENGKAP => 'Lengkap',
            self::TIDAK_LENGKAP => 'Tidak lengkap',
            self::TIDAK_RELEVAN => 'Tidak relevan',
        ];
    }

    public function getPengawasan()
    {
        return $this->hasOne(TertibPengawasan::class, ['id' => 'pengawasan_id']);
    }
}
