<?php

namespace app\modules\monitoring\models;

use app\modules\monitoring\models\MonitoringProgress;
use app\modules\monitoring\models\MonitoringTask;
use app\modules\monitoring\models\MonitoringTaskLocations;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\i18n\Formatter;
use yii\db\ActiveRecord;
use app\overrides\user\models\User;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%monitoring_progress_pictures}}".
 * {@inheritdoc}
 * @property int $id
 * @property string $name
 * @property int $monitoring_progress_id
 * @property string $monitoring_progress_name
 * @property int $monitoring_task_id
 * @property string $monitoring_task_name
 * @property int|null $monitoring_task_locations_id
 * @property string|null $monitoring_task_locations_name
 * @property string $file_name
 * @property string $file_path
 * @property string $file_extension
 * @property int $file_size
 * @property string $file_url
 * @property string|null $file_thumbnail
 * @property string|null $picture_url
 * @property string|null $thumbnail_url
 * @property string|null $description
 * @property string $created_at
 * @property string $updated_at
 * @property string $uploadimage
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class MonitoringProgressPictures extends \yii\db\ActiveRecord
{
    public $sess;
    public $uploadimage;
    public $urlcontrollerpath = '/monitoring/progress-file';
    public $picture_url;
    public $thumbnail_url;

    /**
     * Init Object Controller
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->sess = Yii::$app->session;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%monitoring_progress_pictures}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    /**
     * Model Behaviors
     * Blameable = table field harus ada: created_by dan updated_by
     * Timestamp = table field harus ada: created_at dan updated_at
     * @return void
     */
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
            [
                'class' => BlameableBehavior::class,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'trim'],
            [['description'], 'trim'],
            [['name', 'monitoring_task_id', 'file_name', 'file_url'], 'required'],
            [['name', 'monitoring_task_name', 'monitoring_task_locations_name', 'file_url', 'file_thumbnail', 'file_path', 'description'], 'string'],
            [['monitoring_task_id', 'monitoring_task_locations_id', 'created_by', 'updated_by', 'file_size'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['file_name'], 'string', 'max' => 255],
            [['file_extension'], 'string', 'max' => 50],
            [['picture_url', 'thumbnail_url'], 'string'],
            [['monitoring_progress_id',], 'integer'],
            [['monitoring_progress_id',], 'required'],
            [['monitoring_progress_name',], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('monitoring', 'Name'),
            'monitoring_task_id' => Yii::t('monitoring', 'Monitoring Task ID'),
            'monitoring_task_name' => Yii::t('monitoring', 'Monitoring Task Name'),
            'monitoring_task_locations_id' => Yii::t('monitoring', 'Monitoring Task Location ID'),
            'monitoring_task_locations_name' => Yii::t('monitoring', 'Monitoring Task Location Name'),
            'monitoring_progress_id' => Yii::t('monitoring', 'Monitoring Progress ID'),
            'monitoring_progress_name' => Yii::t('monitoring', 'Monitoring Progress Name'),
            'file_name' => Yii::t('monitoring', 'File Name'),
            'file_path' => Yii::t('monitoring', 'File Path'),
            'file_extension' => Yii::t('monitoring', 'File Extension'),
            'file_size' => Yii::t('monitoring', 'File Size'),
            // 'file_upload' => Yii::t('technicalspec', 'File Upload'),
            'file_url' => Yii::t('monitoring', 'File Url'),
            'file_thumbnail' => Yii::t('monitoring', 'File Thumbnail'),
            'description' => Yii::t('monitoring', 'Description'),
            'uploadimage' => Yii::t('monitoring', 'Upload Image'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
    // Daerah Start relation

    /**
     * Gets query for [[MonitoringTask]].
     *
     * @return \yii\db\ActiveQuery|MonitoringTaskQuery
     */
    public function getMonitoringTask()
    {
        return $this->hasOne(MonitoringTask::class, ['id' => 'monitoring_task_id']);
    }

    /**
     * Get created_by relation from [[User]] table.
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUserMaker()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Get updated_by relation from [[User]] table.
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUserUpdater()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    /**
     * Gets query for [[MonitoringTaskLocations]].
     *
     * @return \yii\db\ActiveQuery|MonitoringTaskLocationsQuery
     */
    public function getTaskLocation()
    {
        return $this->hasOne(MonitoringTaskLocations::class, ['id' => 'monitoring_task_locations_id']);
    }

    /**
     * Gets query for [[MonitoringProgress]].
     *
     * @return \yii\db\ActiveQuery|MonitoringProgressQuery
     */
    public function getMonitoringProgress()
    {
        return $this->hasOne(MonitoringProgress::class, ['id' => 'monitoring_progress_id']);
    }
    //Daerah Akhir Relation



    /**
     * {@inheritdoc}
     * @return MonitoringProgressPicturesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MonitoringProgressPicturesQuery(get_called_class());
    }

    //Daerah Start Virtual Attribute

    /**
     * Virtual Attribute for Full Name
     *
     * @return string
     */
    public function getNamaLengkap()
    {
        return $this->name;
    }

    /**
     * Virtual Attribute for Created By Name
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getMaker()
    {
        $data = null;
        if ($this->userMaker) {
            $data = isset($this->userMaker->profile->name) ? $this->userMaker->profile->name : $this->userMaker->username;
        }
        return $data;
    }

    /**
     * Virtual Attribute for Updated By Name
     *
     * @return \yii\db\ActiveQuery|UserTypeQuery
     */
    public function getUpdater()
    {
        $data = null;
        if ($this->userUpdater) {
            $data = isset($this->userUpdater->profile->name) ? $this->userUpdater->profile->name : $this->userUpdater->username;
        }
        return $data;
    }

    //Daerah Akhir Virtual Attribute

    //Daerah Start Event Driven
    /**
     * Sebelum melakukan validasi input dan sebelum event beforeSave
     *
     * @return void
     */
    public function beforeValidate()
    {
        // Converting to db standard format
        // cth: $this->tanggal_mulai   = $this->tanggal_mulai ? Yii::$app->formatter->asDate($this->tanggal_mulai, 'yyyy-MM-dd') : null;

        parent::beforeValidate();
        return true;
    }

    /**
     * Sebelum disimpan, sesuaikan dnegan format db
     *
     * @param [type] $insert
     * @return void
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            // tulis sini jika ini adalah new insert
        }
        // tulis sini saat update/insert
        if (isset($this->monitoringTask)) {
            $this->monitoring_task_name = $this->monitoringTask->name;
        }
        if (isset($this->taskLocation)) {
            $this->monitoring_task_locations_name = $this->taskLocation->name;
        }
        if (isset($this->monitoringProgress)) {
            $this->monitoring_progress_name = $this->monitoringProgress->name;
        }

        parent::beforeSave($insert);
        return true;
    }

    /**
     * Setelah query ditemukan/selesai dan akan ditampilkan ke view
     *
     * @return void
     */
    public function afterFind()
    {
        // monggo
        if (isset(Yii::$app->hasher)) {
            $this->picture_url = Url::to([$this->urlcontrollerpath . '/picture', 'id' => Yii::$app->hasher->encode($this->id)]);
            $this->thumbnail_url = Url::to([$this->urlcontrollerpath . '/thumbnail', 'id' => Yii::$app->hasher->encode($this->id)]);
        }
        parent::afterFind();
        return true;
    }

    /**
     * Sebelum hapus
     *
     * @return void
     */
    public function beforeDelete()
    {
        // monggo

        parent::beforeDelete();
        return true;
    }
    //Daerah Akhir Event Driven

}
