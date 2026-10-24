<?php

namespace app\modules\monitoring\models;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

class TertibTindakLanjut extends ActiveRecord
{
    const PROSES = 'PROSES';
    const SELESAI = 'SELESAI';

    public static function tableName()
    {
        return '{{%pengawasan_tertib_tindak_lanjut}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']],
                'updatedAtAttribute' => false,
                'value' => date('Y-m-d H:i:s'),
            ],
            [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false,
            ],
        ];
    }

    public function rules()
    {
        return [
            [['temuan_id', 'tanggal_tindak_lanjut', 'uraian_tindakan', 'status'], 'required'],
            [['temuan_id', 'created_by'], 'integer'],
            [['tanggal_tindak_lanjut'], 'date', 'format' => 'php:Y-m-d'],
            [['uraian_tindakan', 'hasil_verifikasi', 'catatan_internal'], 'string'],
            [['status'], 'in', 'range' => array_keys(self::statusOptions())],
            [['created_at'], 'safe'],
            [['temuan_id'], 'exist', 'skipOnError' => true, 'targetClass' => TertibTemuan::class, 'targetAttribute' => ['temuan_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'tanggal_tindak_lanjut' => 'Tanggal tindak lanjut',
            'uraian_tindakan' => 'Tindakan perbaikan',
            'hasil_verifikasi' => 'Hasil verifikasi',
            'status' => 'Status setelah tindak lanjut',
            'catatan_internal' => 'Catatan internal',
        ];
    }

    public static function statusOptions()
    {
        return [self::PROSES => 'Dalam proses', self::SELESAI => 'Selesai'];
    }

    public function getTemuan()
    {
        return $this->hasOne(TertibTemuan::class, ['id' => 'temuan_id']);
    }
}
