<?php

namespace app\modules\monitoring\models;

use app\models\Satker;
use app\models\Teamlist;
use app\modules\monitoring\models\MonitoringProgressPictures;
use app\modules\references\models\ObjectType;
use app\modules\rup\models\Rup;
use app\modules\technicalspec\models\MonitoringSpec;
use Yii;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\i18n\Formatter;
use yii\db\ActiveRecord;
use app\overrides\user\models\User;

/**
 * This is the model class for table "monitoring_progress".
 *
 * @property int $id
 * @property string $name
 * @property int $order_no
 * @property int $monitoring_task_id
 * @property string|null $monitoring_task_name
 * @property string $progress_date Date
 * @property int|null $date_day Day of Date
 * @property int|null $date_month Month of Date
 * @property int|null $date_year Year of Date
 * @property string|null $description
 * @property float $planned_financial_budget
 * @property float $planned_physical_percent
 * @property float $financial_budget
 * @property float $physical_percent
 * @property string|null $problem
 * @property string|null $solution
 * @property int|null $num_minor_accident
 * @property int|null $num_moderate_accident
 * @property int|null $num_serious_accident
 * @property string|null $work_items_of_potential_accident
 * @property string|null $work_items_of_occured_accident
 * @property boolean $verified Verified
 * @property string $created_at
 * @property string $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $working_hours_start Working Hours Start
 * @property string|null $working_hours_end Working Hours End
 * @property int|null $number_of_workers Number of Workers
 * @property string|null $work_being_done Work being Done
 * @property string|null $notes Notes
 *
 * @property MonitoringProgress $latestProgress
 * @property MonitoringTask $monitoringTask
 * @property MonitoringSpec[] $monitoringSpecs
 * @property MonitoringWeather[] $weathers
 */
class MonitoringProgress extends \yii\db\ActiveRecord
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
        return 'monitoring_progress';
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
            [['name', 'monitoring_task_id', 'progress_date', 'planned_financial_budget', 'planned_physical_percent', 'financial_budget', 'physical_percent'], 'required'],
            [['name', 'monitoring_task_name', 'description', 'problem', 'solution'], 'string'],
            [['order_no', 'monitoring_task_id', 'created_by', 'updated_by'], 'integer'],
            [['progress_date', 'created_at', 'updated_at'], 'safe'],
            [['planned_financial_budget', 'planned_physical_percent', 'financial_budget', 'physical_percent'], 'number'],
            [['monitoring_task_id'], 'exist', 'skipOnError' => true, 'targetClass' => MonitoringTask::class, 'targetAttribute' => ['monitoring_task_id' => 'id']],
            [['progress_date'], 'validateDateInRange'],
            [['verified'], 'boolean'],
            ['order_no', 'validateOrderNumber'],
            ['planned_financial_budget', 'validatePlanBudget'],
            ['planned_physical_percent', 'validatePlanPhysicalProgress'],
            ['financial_budget', 'validateBudget'],
            ['physical_percent', 'validatePhysicalProgress'],
            [['num_minor_accident', 'num_moderate_accident', 'num_serious_accident',], 'integer'],
            [['work_items_of_potential_accident', 'work_items_of_occured_accident',], 'string'],
            [['date_day', 'date_month', 'date_year',], 'integer'],
            [['working_hours_start', 'working_hours_end'], 'safe'],
            [['number_of_workers',], 'integer'],
            [['work_being_done', 'notes',], 'string'],
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
            'order_no' => Yii::t('monitoring', 'Order No'),
            'monitoring_task_id' => Yii::t('monitoring', 'Monitoring Task ID'),
            'monitoring_task_name' => Yii::t('monitoring', 'Monitoring Task Name'),
            'progress_date' => Yii::t('monitoring', 'Date'),
            'description' => Yii::t('monitoring', 'Description and Conclusion'),
            'planned_financial_budget' => Yii::t('monitoring', 'Planned Financial Budget'),
            'planned_physical_percent' => Yii::t('monitoring', 'Planned Physical Percent'),
            'financial_budget' => Yii::t('monitoring', 'Financial Budget'),
            'physical_percent' => Yii::t('monitoring', 'Physical Percent'),
            'problem' => Yii::t('monitoring', 'Problem'),
            'solution' => Yii::t('monitoring', 'Solution'),
            'num_minor_accident' => Yii::t('monitoring', 'Number of Minor Accident'),
            'num_moderate_accident' => Yii::t('monitoring', 'Number of Moderate Accident'),
            'num_serious_accident' => Yii::t('monitoring', 'Number of Serious Accident'),
            'work_items_of_potential_accident' => Yii::t('monitoring', 'Work items of potential accident'),
            'work_items_of_occured_accident' => Yii::t('monitoring', 'Work items of occured accident'),
            'working_hours_start' => Yii::t('monitoring', 'Working Hours Start'),
            'working_hours_end' => Yii::t('monitoring', 'Working Hours End'),
            'number_of_workers' => Yii::t('monitoring', 'Number of Workers'),
            'work_being_done' => Yii::t('monitoring', 'Work being Done'),
            'notes' => Yii::t('monitoring', 'Notes'),
            'verified' => Yii::t('monitoring', 'Verified'),
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

    public function getProgressPictures()
    {
        return $this->hasMany(MonitoringProgressPictures::class, ['monitoring_progress_id' => 'id']);
    }

    public function getWeathers()
    {
        return $this->hasMany(MonitoringWeather::class, ['monitoring_progress_id' => 'id'])->orderBy(['time_from' => SORT_ASC]);
    }

    //via paket/task
    public function getTeam()
    {
        return $this->hasOne(Teamlist::class, ['id' => 'team_id'])->via('monitoringTask');
    }

    public function getObjectType()
    {
        return $this->hasOne(ObjectType::class, ['id' => 'object_type_id'])->via('monitoringTask');
    }

    public function getUnit()
    {
        //menggunakan user untuk placeholder sementara
        return $this->hasOne(Satker::class, ['id' => 'unit_id'])->via('monitoringTask');
    }

    public function getRup()
    {
        //menggunakan user untuk placeholder sementara
        return $this->hasOne(Rup::class, ['idrup' => 'idrup'])->via('monitoringTask');
    }

    public function getMonitoringSpecs()
    {
        return $this->hasMany(MonitoringSpec::class, ['monitoring_progress_id' => 'id']);
    }

    //Daerah Akhir Relation



    /**
     * {@inheritdoc}
     * @return MonitoringProgressQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MonitoringProgressQuery(get_called_class());
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

    public function getMaxOrder()
    {
        if (isset($this->monitoring_task_id)) {
            $max_order = MonitoringProgress::find()->where(['monitoring_task_id' => $this->monitoring_task_id])->max('order_no');
            if ($max_order) {
                return (int)$max_order;
            }
        }
        return 0;
    }

    public function getMaxDate()
    {
        if (isset($this->monitoring_task_id)) {
            $max_date = MonitoringProgress::find()->where(['monitoring_task_id' => $this->monitoring_task_id])->max('progress_date');
            if ($max_date) {
                return $max_date;
            }
        }
        return 0;
    }

    public function getLatestProgress()
    {
        if ($this->maxOrder) {
            return MonitoringProgress::find()->where(['monitoring_task_id' => $this->monitoring_task_id, 'order_no' => $this->maxOrder])->orderBy(['id' => SORT_DESC])->one();
        }
        return null;
    }

    public function getPreviousProgress()
    {
        return MonitoringProgress::find()
            ->where(['monitoring_task_id' => $this->monitoring_task_id,])
            ->andWhere(['<=', 'progress_date', $this->progress_date])
            ->andWhere(['<=', 'order_no', $this->order_no])
            ->andWhere(['!=', 'id', $this->id])
            ->orderBy([
                'order_no' => SORT_DESC,
                'progress_date' => SORT_DESC,
                'id' => SORT_DESC,
            ])
            ->one();
    }

    public function getNextProgress()
    {
        return MonitoringProgress::find()
            ->where(['monitoring_task_id' => $this->monitoring_task_id,])
            ->andWhere(['>=', 'progress_date', $this->progress_date])
            ->andWhere(['>=', 'order_no', $this->order_no])
            ->andWhere(['!=', 'id', $this->id])
            ->orderBy([
                'order_no' => SORT_ASC,
                'progress_date' => SORT_ASC,
                'id' => SORT_ASC,
            ])
            ->one();
    }

    public function getTotalAccidentToThisWeek()
    {
        $sum_minor = MonitoringProgress::find()
            ->where(['monitoring_task_id' => $this->monitoring_task_id,])
            ->andWhere(['<=', 'order_no', $this->order_no])
            ->sum('num_minor_accident');
        $sum_moderate = MonitoringProgress::find()
            ->where(['monitoring_task_id' => $this->monitoring_task_id,])
            ->andWhere(['<=', 'order_no', $this->order_no])
            ->sum('num_moderate_accident');
        $sum_serious = MonitoringProgress::find()
            ->where(['monitoring_task_id' => $this->monitoring_task_id,])
            ->andWhere(['<=', 'order_no', $this->order_no])
            ->sum('num_serious_accident');
        $result = (int)$sum_minor + (int)$sum_moderate + (int)$sum_serious;
        return $result;
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
        $this->progress_date = $this->progress_date ? Yii::$app->formatter->asDate($this->progress_date, 'yyyy-MM-dd') : null;

        $this->date_day = (int)date('j', strtotime($this->progress_date));
        $this->date_month = (int)date('n', strtotime($this->progress_date));
        $this->date_year = (int)date('Y', strtotime($this->progress_date));

        parent::beforeSave($insert);
        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
        }
        $latest = $this->latestProgress;
        $task = $this->monitoringTask;
        $task->latest_plan_financial_budget = $latest->planned_financial_budget;
        $task->latest_plan_physical_percent = $latest->planned_physical_percent;
        $task->latest_monitored_financial_budget = $latest->financial_budget;
        $task->latest_monitored_physical_percent = $latest->physical_percent;
        $task->save(false);
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

    //Daerah Validation
    public function validateDateInRange()
    {
        $less_plan = $this->previousProgress;
        if ($less_plan) {
            if (strtotime($less_plan->progress_date) > strtotime($this->progress_date)) {
                $less_date = Yii::$app->formatter->asDate($less_plan->progress_date);
                $this->addError('progress_date', Yii::t('monitoring', 'Date invalid, must not less than ') . $less_date);
            }
        }

        // old logic
        // if ($this->order_no < $this->maxOrder) { ...

        if (strtotime($this->progress_date) < strtotime($this->maxDate)) {
            $more_plan = $this->nextProgress;
            if ($more_plan) {
                if (strtotime($more_plan->progress_date) < strtotime($this->progress_date)) {
                    $more_date = Yii::$app->formatter->asDate($more_plan->progress_date);
                    $this->addError('progress_date', Yii::t('monitoring', 'Date invalid, must not more than ') . $more_date);
                }
            }
        }
    }

    public function validateOrderNumber()
    {
        // old logic
        // $other_order = self::find()->where(['monitoring_task_id' => $this->monitoring_task_id, 'order_no' => $this->order_no])->one();
        // if ($other_order && ($other_order->id != $this->id)) {
        //     $this->addError('order_no', Yii::t('monitoring', 'Order Number is invalid or already used'));
        // }
        // validasi order tidak boleh sama dan lebih kecil dari 0.
        if ($this->order_no <= 0) {
            $this->addError('order_no', Yii::t('monitoring', 'Order Number is invalid') . ' : ' . $this->order_no);
        }

        // validasi ke monitoring progress sebelumnya
        $prevProgress = $this->previousProgress;
        if (
            $prevProgress
            && (strtotime($prevProgress->progress_date) == strtotime($this->progress_date))
            && ($prevProgress->order_no != $this->order_no)
        ) {
            $prev_order = $prevProgress->order_no;
            $this->addError('order_no', Yii::t('monitoring', 'Order Number is invalid') . ' : ' . $prev_order);
        } else if (
            $prevProgress
            && (strtotime($prevProgress->progress_date) < strtotime($this->progress_date))
            && ($prevProgress->order_no > $this->order_no)
        ) {
            $prev_order = $prevProgress->order_no;
            $this->addError('order_no', Yii::t('monitoring', 'Order Number is invalid') . ' : ' . $prev_order);
        }

        // validasi ke monitoring progress setelahnya
        $nextProgress = $this->nextProgress;
        if (strtotime($this->progress_date) == strtotime($this->maxDate)) {
            if (
                $nextProgress
                && ($this->order_no != $nextProgress->order_no)
            ) {
                $next_order = $nextProgress->order_no;
                $this->addError('order_no', Yii::t('monitoring', 'Order Number is invalid') . ' : ' . $next_order);
            }
        } else if (strtotime($this->progress_date) < strtotime($this->maxDate)) {
            if (
                $nextProgress
                && (strtotime($nextProgress->progress_date) == strtotime($this->progress_date))
                && ($this->order_no != $nextProgress->order_no)
            ) {
                $next_order = $nextProgress->order_no;
                $this->addError('order_no', Yii::t('monitoring', 'Order Number is invalid') . ' : ' . $next_order);
            } else if (
                $nextProgress
                && (strtotime($nextProgress->progress_date) > strtotime($this->progress_date))
                && ($nextProgress->order_no < $this->order_no)
            ) {
                $next_order = $nextProgress->order_no;
                $this->addError('order_no', Yii::t('monitoring', 'Order Number is invalid') . ' : ' . $next_order);
            }
        }
    }

    public function validatePlanBudget()
    {
        $prev_plan = $this->getPreviousProgress();
        if ($prev_plan && ($prev_plan->planned_financial_budget > $this->planned_financial_budget)) {
            $budget = Yii::$app->formatter->asDecimal($prev_plan->planned_financial_budget, 2);
            $this->addError('planned_financial_budget', Yii::t('monitoring', 'Planned Budget invalid, must more than ') . $budget);
        }
        if (strtotime($this->progress_date) < strtotime($this->maxDate)) {
            $next_plan = $this->getNextProgress();
            if ($next_plan && ($next_plan->planned_financial_budget < $this->planned_financial_budget)) {
                $budget = Yii::$app->formatter->asDecimal($next_plan->planned_financial_budget, 2);
                $this->addError('planned_financial_budget', Yii::t('monitoring', 'Planned Budget invalid, must less than ') . $budget);
            }
        }
        $task_budget = $this->monitoringTask->budget;
        if ($this->planned_financial_budget > $task_budget) {
            $budget = Yii::$app->formatter->asDecimal($task_budget, 2);
            $this->addError('planned_financial_budget', Yii::t('monitoring', 'Planned Budget invalid, must not more than ') . $budget);
        }
    }

    public function validatePlanPhysicalProgress()
    {
        $prev_plan = $this->getPreviousProgress();
        if ($prev_plan && ($prev_plan->planned_physical_percent > $this->planned_physical_percent)) {
            $progress = Yii::$app->formatter->asDecimal($prev_plan->planned_physical_percent, 2);
            $this->addError('planned_physical_percent', Yii::t('monitoring', 'Planned Progress invalid, must more than ') . $progress);
        }
        if (strtotime($this->progress_date) < strtotime($this->maxDate)) {
            $next_plan = $this->getNextProgress();
            if ($next_plan && ($next_plan->planned_physical_percent < $this->planned_physical_percent)) {
                $progress = Yii::$app->formatter->asDecimal($next_plan->planned_physical_percent, 2);
                $this->addError('planned_physical_percent', Yii::t('monitoring', 'Planned Progress invalid, must less than ') . $progress);
            }
        }
    }

    public function validateBudget()
    {
        $prev_implementation = $this->getPreviousProgress();
        if ($prev_implementation && ($prev_implementation->financial_budget > $this->financial_budget)) {
            $budget = Yii::$app->formatter->asDecimal($prev_implementation->financial_budget, 2);
            $this->addError('financial_budget', Yii::t('monitoring', 'Budget invalid, must more than ') . $budget);
        }
        if (strtotime($this->progress_date) < strtotime($this->maxDate)) {
            $next_implementation = $this->getNextProgress();
            if ($next_implementation && ($next_implementation->financial_budget < $this->financial_budget)) {
                $budget = Yii::$app->formatter->asDecimal($next_implementation->financial_budget, 2);
                $this->addError('financial_budget', Yii::t('monitoring', 'Budget invalid, must less than ') . $budget);
            }
        }
    }

    public function validatePhysicalProgress()
    {
        $prev_implementation = $this->getPreviousProgress();
        if ($prev_implementation && ($prev_implementation->physical_percent > $this->physical_percent)) {
            $progress = Yii::$app->formatter->asDecimal($prev_implementation->physical_percent, 2);
            $this->addError('physical_percent', Yii::t('monitoring', 'Progress invalid, must more than ') . $progress);
        }
        if (strtotime($this->progress_date) < strtotime($this->maxDate)) {
            $next_implementation = $this->getNextProgress();
            if ($next_implementation && ($next_implementation->physical_percent < $this->physical_percent)) {
                $progress = Yii::$app->formatter->asDecimal($next_implementation->physical_percent, 2);
                $this->addError('physical_percent', Yii::t('monitoring', 'Progress invalid, must less than ') . $progress);
            }
        }
    }
}
