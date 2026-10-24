<?php

namespace app\modules\monitoring\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\modules\monitoring\models\MonitoringTask;

class TertibPengawasan extends ActiveRecord
{
    const METODE_LAPANGAN = 'LAPANGAN';
    const METODE_DOKUMEN = 'DOKUMEN';
    const METODE_GABUNGAN = 'GABUNGAN';

    const STATUS_TERJADWAL = 'TERJADWAL';
    const STATUS_PROSES = 'DALAM_PROSES';
    const STATUS_SELESAI = 'SELESAI';

    public static function tableName()
    {
        return '{{%pengawasan_tertib}}';
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
            [['monitoring_task_id', 'tanggal_pengawasan', 'metode_pengawasan', 'uraian'], 'required'],
            [['monitoring_task_id', 'created_by', 'updated_by'], 'integer'],
            [['tanggal_pengawasan'], 'date', 'format' => 'php:Y-m-d'],
            [['uraian'], 'string'],
            [['status_pelaksanaan'], 'default', 'value' => self::STATUS_TERJADWAL],
            [['status_pelaksanaan'], 'in', 'range' => array_keys(self::statusOptions())],
            [['metode_pengawasan'], 'in', 'range' => array_keys(self::metodeOptions())],
            [['is_public'], 'boolean'],
            [['is_public'], 'default', 'value' => 0],
            [['created_at', 'updated_at'], 'safe'],
            [['monitoring_task_id'], 'exist', 'skipOnError' => true, 'targetClass' => MonitoringTask::class, 'targetAttribute' => ['monitoring_task_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'monitoring_task_id' => 'Paket pekerjaan',
            'tanggal_pengawasan' => 'Tanggal pengawasan',
            'metode_pengawasan' => 'Metode pengawasan',
            'uraian' => 'Uraian kegiatan',
            'status_pelaksanaan' => 'Status pelaksanaan',
            'is_public' => 'Tampilkan rekap kegiatan di web umum',
        ];
    }

    public static function metodeOptions()
    {
        return [
            self::METODE_LAPANGAN => 'Pengawasan lapangan',
            self::METODE_DOKUMEN => 'Pemeriksaan dokumen',
            self::METODE_GABUNGAN => 'Gabungan',
        ];
    }

    public static function statusOptions()
    {
        return [
            self::STATUS_TERJADWAL => 'Terjadwal',
            self::STATUS_PROSES => 'Dalam proses',
            self::STATUS_SELESAI => 'Selesai',
        ];
    }

    public function getMonitoringTask()
    {
        return $this->hasOne(MonitoringTask::class, ['id' => 'monitoring_task_id']);
    }

    public function getChecklists()
    {
        return $this->hasMany(TertibChecklist::class, ['pengawasan_id' => 'id'])->orderBy(['id' => SORT_ASC]);
    }

    public function getFindings()
    {
        return $this->hasMany(TertibTemuan::class, ['pengawasan_id' => 'id'])->orderBy(['id' => SORT_DESC]);
    }

    public function getDocuments()
    {
        return $this->hasMany(TertibDokumen::class, ['pengawasan_id' => 'id'])->orderBy(['id' => SORT_DESC]);
    }
}
