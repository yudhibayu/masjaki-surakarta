<?php

namespace app\modules\monitoring\models;

use Yii;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\i18n\Formatter;
use yii\db\ActiveRecord;
use app\overrides\user\models\User;

/**
 * This is the model class for table "monitoring_planner_note".
 *
 * @property int $id
 * @property string $name
 * @property int $order_no
 * @property int $monitoring_task_id
 * @property string|null $monitoring_task_name
 * @property string $note_date Date
 * @property int|null $date_day
 * @property int|null $date_month
 * @property int|null $date_year
 * @property string|null $description
 * @property float|null $planned_financial_budget
 * @property float|null $planned_physical_percent
 * @property float|null $financial_budget
 * @property float|null $physical_percent
 * @property string|null $problem
 * @property string|null $solution
 * @property string $created_at
 * @property string $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * 
 * @property int $maxOrder
 */
class MonitoringPlannerNote extends \yii\db\ActiveRecord
{
    public $sess;
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
        return 'monitoring_planner_note';
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
            [['name', 'monitoring_task_id', 'note_date'], 'required'],
            [['name', 'monitoring_task_name', 'description', 'problem', 'solution'], 'string'],
            [['order_no', 'monitoring_task_id', 'date_day', 'date_month', 'date_year', 'created_by', 'updated_by'], 'integer'],
            [['note_date', 'created_at', 'updated_at'], 'safe'],
            [['planned_financial_budget', 'planned_physical_percent', 'financial_budget', 'physical_percent'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('monitoring', 'Note Name'),
            'order_no' => Yii::t('monitoring', 'Order No'),
            'monitoring_task_id' => Yii::t('monitoring', 'Monitoring Task ID'),
            'monitoring_task_name' => Yii::t('monitoring', 'Monitoring Task Name'),
            'note_date' => Yii::t('monitoring', 'Note Date'),
            'date_day' => Yii::t('monitoring', 'Date Day'),
            'date_month' => Yii::t('monitoring', 'Date Month'),
            'date_year' => Yii::t('monitoring', 'Date Year'),
            'description' => Yii::t('monitoring', 'Description and Conclusion'),
            'planned_financial_budget' => Yii::t('monitoring', 'Planned Financial Budget'),
            'planned_physical_percent' => Yii::t('monitoring', 'Planned Physical Percent'),
            'financial_budget' => Yii::t('monitoring', 'Financial Budget'),
            'physical_percent' => Yii::t('monitoring', 'Physical Percent'),
            'problem' => Yii::t('monitoring', 'Problem'),
            'solution' => Yii::t('monitoring', 'Solution'),
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
    //Daerah Akhir Relation



    /**
     * {@inheritdoc}
     * @return MonitoringPlannerNoteQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MonitoringPlannerNoteQuery(get_called_class());
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

    public function getMaxOrder()
    {
        if (isset($this->monitoring_task_id)) {
            $max_order = MonitoringPlannerNote::find()->where(['monitoring_task_id' => $this->monitoring_task_id])->max('order_no');
            if ($max_order) {
                return (int)$max_order;
            }
        }
        return 0;
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
        $this->monitoring_task_name = $this->monitoringTask->name;
        $this->note_date = $this->note_date ? Yii::$app->formatter->asDate($this->note_date, 'yyyy-MM-dd') : null;

        $this->date_day = (int)date('j', strtotime($this->note_date));
        $this->date_month = (int)date('n', strtotime($this->note_date));
        $this->date_year = (int)date('Y', strtotime($this->note_date));

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
        $this->note_date = $this->note_date != '0000-00-00' ? Yii::$app->formatter->asDate($this->note_date, 'dd-MM-yyyy') : null;

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
