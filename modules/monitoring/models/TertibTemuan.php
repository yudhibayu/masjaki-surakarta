<?php

namespace app\modules\monitoring\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class TertibTemuan extends ActiveRecord
{
    const BELUM = 'BELUM';
    const PROSES = 'PROSES';
    const SELESAI = 'SELESAI';

    public static function tableName()
    {
        return '{{%pengawasan_tertib_temuan}}';
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
            [['pengawasan_id', 'kategori', 'uraian'], 'required'],
            [['pengawasan_id', 'created_by', 'updated_by'], 'integer'],
            [['uraian', 'rekomendasi', 'catatan_internal'], 'string'],
            [['kategori'], 'string', 'max' => 120],
            [['prioritas'], 'default', 'value' => 'NORMAL'],
            [['prioritas'], 'in', 'range' => array_keys(self::prioritasOptions())],
            [['status_tindak_lanjut'], 'default', 'value' => self::BELUM],
            [['status_tindak_lanjut'], 'in', 'range' => array_keys(self::statusOptions())],
            [['is_public'], 'boolean'],
            [['is_public'], 'default', 'value' => 0],
            [['created_at', 'updated_at'], 'safe'],
            [['pengawasan_id'], 'exist', 'skipOnError' => true, 'targetClass' => TertibPengawasan::class, 'targetAttribute' => ['pengawasan_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'kategori' => 'Kategori temuan',
            'uraian' => 'Uraian temuan',
            'rekomendasi' => 'Rekomendasi perbaikan',
            'prioritas' => 'Prioritas',
            'status_tindak_lanjut' => 'Status tindak lanjut',
            'catatan_internal' => 'Catatan internal',
            'is_public' => 'Izinkan jumlah temuan ini masuk rekap publik',
        ];
    }

    public static function prioritasOptions()
    {
        return ['NORMAL' => 'Normal', 'PENTING' => 'Penting', 'MENDESAK' => 'Mendesak'];
    }

    public static function statusOptions()
    {
        return [
            self::BELUM => 'Belum ditindaklanjuti',
            self::PROSES => 'Dalam proses',
            self::SELESAI => 'Selesai',
        ];
    }

    public function getPengawasan()
    {
        return $this->hasOne(TertibPengawasan::class, ['id' => 'pengawasan_id']);
    }

    public function getTindakLanjuts()
    {
        return $this->hasMany(TertibTindakLanjut::class, ['temuan_id' => 'id'])->orderBy(['tanggal_tindak_lanjut' => SORT_DESC, 'id' => SORT_DESC]);
    }
}
