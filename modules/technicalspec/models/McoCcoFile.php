<?php

namespace app\modules\technicalspec\models;

use app\modules\monitoring\models\MonitoringTask;
use Yii;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\i18n\Formatter;
use yii\db\ActiveRecord;
use app\overrides\user\models\User;

/**
 * This is the model class for table "mco_cco_file".
 *
 * @property int $id
 * @property int|null $mco_cco_id
 * @property int|null $monitoring_task_id
 * @property string|null $file_name
 * @property string|null $description
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class McoCcoFile extends \yii\db\ActiveRecord
{
    public $sess;

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
        return 'mco_cco_file';
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
            [['mco_cco_id', 'monitoring_task_id', 'created_by', 'updated_by'], 'integer'],
            [['file_name', 'description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['mco_cco_id'], 'exist', 'skipOnError' => true, 'targetClass' => McoCco::class, 'targetAttribute' => ['mco_cco_id' => 'id']],
            [['monitoring_task_id'], 'exist', 'skipOnError' => true, 'targetClass' => MonitoringTask::class, 'targetAttribute' => ['monitoring_task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
                'mco_cco_id' => Yii::t('app', 'Mco Cco ID'),
                'monitoring_task_id' => Yii::t('app', 'Monitoring Task ID'),
                'file_name' => Yii::t('app', 'File Name'),
                'description' => Yii::t('app', 'Description'),
                'created_at' => Yii::t('app', 'Created At'),
                'updated_at' => Yii::t('app', 'Updated At'),
                'created_by' => Yii::t('app', 'Created By'),
                'updated_by' => Yii::t('app', 'Updated By'),
            ];
    }
    // Daerah Start relation
    /**
     * Gets query for [[McoCCo]].
     *
     * @return \yii\db\ActiveQuery|McoCCoQuery
     */
    public function getMcoCCo()
    {
        return $this->hasOne(McoCCo::class, ['id' => 'mco_cco_id']);
    }
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
//Daerah Akhir Relation



    /**
     * {@inheritdoc}
     * @return McoCcoFileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new McoCcoFileQuery(get_called_class());
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
            $data = $this->userMaker->profile->name ? $this->userMaker->profile->name : $this->userMaker->username;
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
            $data = $this->userUpdater->profile->name ? $this->userUpdater->profile->name : $this->userUpdater->username;
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
// $this->mco_cco_name = $this->mcoCco->name;
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
    public static function filePath()
    {
        return Yii::getAlias('@upload') . '/';
    }

    public function removeFile()
    {
        if ($this->file_name != null) {
            $path = $this->filePath() . $this->file_name;
            $test = file_exists($path);
            if ($test) {
                if (unlink($path)) {
                    return true;
                }
            }
        } else {
            return true;
        }
        return false;
    }
}
