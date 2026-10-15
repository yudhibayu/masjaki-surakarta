<?php

namespace app\modules\technicalspec\models;

use app\modules\monitoring\models\MonitoringProgress;
use app\modules\monitoring\models\MonitoringTask;
use Yii;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\i18n\Formatter;
use yii\db\ActiveRecord;
use app\overrides\user\models\User;

/**
 * This is the model class for table "monitoring_spec".
 *
 * @property int $id
 * @property string $name
 * @property int $ded_data_id
 * @property int $monitoring_progress_id
 * @property int $monitoring_task_id Monitoring Task ID
 * @property string|null $description
 * @property string|null $created_at Created At
 * @property string|null $updated_at Updated At
 * @property int|null $created_by Created By
 * @property int|null $updated_by Updated By
 */
class MonitoringSpec extends \yii\db\ActiveRecord
{
    public $sess;
    public $monitoring_task_name;
    public $monitoring_progress_name;
    public $ref_component_category_id;
    public $ref_component_category_name;
    public $ref_component_id;
    public $ref_component_name;
    public $ded_data_name;
    public $file_uploads;

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
        return 'monitoring_spec';
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
            [['description'], 'trim'],
            [['name', 'ded_data_id', 'monitoring_progress_id', 'monitoring_task_id'], 'required'],
            [['ded_data_id', 'monitoring_progress_id', 'monitoring_task_id', 'created_by', 'updated_by'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('technicalspec', 'Monitoring Spec Name'),
            'ded_data_id' => Yii::t('technicalspec', 'Ded Data ID'),
            'ded_data_name' => Yii::t('technicalspec', 'DED Name'),
            'monitoring_progress_id' => Yii::t('monitoring', 'Monitoring Progress ID'),
            'monitoring_progress_name' => Yii::t('monitoring', 'Monitoring Progress Name'),
            'monitoring_task_id' => Yii::t('monitoring', 'Monitoring Task ID'),
            'monitoring_task_name' => Yii::t('monitoring', 'Monitoring Task Name'),
            'file_uploads' => Yii::t('app', 'File Uploads'),
            'description' => Yii::t('technicalspec', 'Description'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),

            //addon
            'ref_component_category_id' => Yii::t('technicalspec', 'Component Category ID'),
            'ref_component_category_name' => Yii::t('technicalspec', 'Ref Component Category Name'),
            'ref_component_id' => Yii::t('technicalspec', 'Component ID'),
            'ref_component_name' => Yii::t('technicalspec', 'Ref Component Name'),
        ];
    }
    // Daerah Start relation

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
     * Get Monitoring Tasks
     * 
     * @return \yii\db\ActiveQuery|MonitoringTaskQuery
     */
    public function getMonitoringTask()
    {
        return $this->hasOne(MonitoringTask::class, ['id' => 'monitoring_task_id']);
    }

    /**
     * Get Monitoring Progress
     * 
     * @return \yii\db\ActiveQuery|MonitoringTaskQuery
     */
    public function getMonitoringProgress()
    {
        return $this->hasOne(MonitoringProgress::class, ['id' => 'monitoring_progress_id']);
    }

    /**
     * Get ref_component_category_id relation from [[RefComponentCategory]] table.
     *
     * @return \yii\db\ActiveQuery|RefComponentCategoryQuery
     */
    public function getRefComponentCategory()
    {
        return $this->hasOne(RefComponentCategory::class, ['id' => 'ref_component_category_id'])
            ->via('dedData');
    }

    /**
     * Get ref_component_id relation from [[RefComponent]] table.
     *
     * @return \yii\db\ActiveQuery|RefComponentQuery
     */
    public function getRefComponent()
    {
        return $this->hasOne(RefComponent::class, ['id' => 'ref_component_id'])
            ->via('dedData');
    }

    /**
     * Get ded_data_id relation from [[DedData]] table.
     *
     * @return \yii\db\ActiveQuery|DedDataQuery
     */
    public function getDedData()
    {
        return $this->hasOne(DedData::class, ['id' => 'ded_data_id']);
    }

    /**
     * Get files.
     *
     * @return \yii\db\ActiveQuery|DedDataQuery
     */
    public function getFiles()
    {
        return $this->hasMany(MonitoringSpecFiles::class, ['monitoring_spec_id' => 'id']);
    }
    //Daerah Akhir Relation



    /**
     * {@inheritdoc}
     * @return MonitoringSpecQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MonitoringSpecQuery(get_called_class());
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
        // $this->ded_data_name = $this->dedData->name;
        // $this->monitoring_progress_name = $this->monitoringProgress->name;
        // $this->monitoring_task_name = $this->monitoringTask->name;

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
        $this->ded_data_name = $this->dedData->name;
        $this->monitoring_progress_name = $this->monitoringProgress->name;
        $this->monitoring_task_name = $this->monitoringTask->name;
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
