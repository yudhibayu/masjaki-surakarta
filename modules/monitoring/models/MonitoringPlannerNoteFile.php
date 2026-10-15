<?php

namespace app\modules\monitoring\models;

use Yii;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\i18n\Formatter;
use yii\db\ActiveRecord;
use app\overrides\user\models\User;

/**
 * This is the model class for table "monitoring_planner_note_file".
 *
 * @property int $id Monitoring Planner Note File ID
 * @property string|null $name Monitoring Planner Note File Name
 * @property int $monitoring_planner_note_id Monitoring Planner Note ID
 * @property string|null $monitoring_planner_note_name Monitoring Planner Note Name
 * @property string|null $description Description
 * @property string $file_name File Name
 * @property string|null $file_path File Path
 * @property string|null $file_extension File Extension
 * @property int|null $file_size File Size
 * @property string|null $created_at Created At
 * @property string|null $updated_at Updated At
 * @property int|null $created_by Created By
 * @property int|null $updated_by Updated By
 */
class MonitoringPlannerNoteFile extends \yii\db\ActiveRecord
{
    public $sess;
    public $file_upload = null;

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
        return 'monitoring_planner_note_file';
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
            [['monitoring_planner_note_id', 'file_name'], 'required'],
            [['monitoring_planner_note_id', 'file_size', 'created_by', 'updated_by'], 'integer'],
            [['description', 'file_path'], 'string'],
            [['created_at', 'updated_at', 'file_upload'], 'safe'],
            [['name', 'monitoring_planner_note_name', 'file_name'], 'string', 'max' => 255],
            [['file_extension'], 'string', 'max' => 50],
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
            'monitoring_planner_note_id' => Yii::t('monitoring', 'Monitoring Planner Note ID'),
            'monitoring_planner_note_name' => Yii::t('monitoring', 'Monitoring Planner Note Name'),
            'description' => Yii::t('monitoring', 'Description'),
            'file_name' => Yii::t('monitoring', 'File Name'),
            'file_path' => Yii::t('monitoring', 'File Path'),
            'file_extension' => Yii::t('monitoring', 'File Extension'),
            'file_size' => Yii::t('monitoring', 'File Size'),
            'file_upload' => Yii::t('technicalspec', 'File Upload'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
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
     * Get updated_by relation from [[User]] table.
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getPlannerNote()
    {
        return $this->hasOne(MonitoringPlannerNote::class, ['id' => 'monitoring_planner_note_id']);
    }
    //Daerah Akhir Relation



    /**
     * {@inheritdoc}
     * @return MonitoringPlannerNoteFileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MonitoringPlannerNoteFileQuery(get_called_class());
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
        $this->monitoring_planner_note_name = $this->plannerNote->name;

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

}
