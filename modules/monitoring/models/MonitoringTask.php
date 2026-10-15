<?php

namespace app\modules\monitoring\models;

use app\models\Satker;
use app\models\Teamlist;
use app\modules\references\models\ObjectType;
use app\modules\references\models\RefCriticalCondition;
use app\modules\references\models\RefKecamatan;
use app\modules\references\models\RefKelurahan;
use app\modules\rup\models\Pbj;
use app\modules\rup\models\RefKegiatan;
use app\modules\rup\models\RefProgram;
use app\modules\rup\models\Rup;
use app\modules\technicalspec\models\McoCco;
use Yii;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\overrides\user\models\User;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%monitoring_task}}".
 *
 * @property int $id ID
 * @property string $name Name
 * @property int|null $idrup
 * @property int|null $program_id
 * @property string|null $program_name
 * @property int|null $kegiatan_id
 * @property string|null $kegiatan_name
 * @property int|null $pbj_id
 * @property float $budget Budget
 * @property float $hps
 * @property float $bid_price
 * @property float $final_price
 * @property int $unit_id Unit
 * @property string $unit_name Unit
 * @property int|null $team_id
 * @property string|null $team_name
 * @property int|null $supervisor_id
 * @property string|null $supervisor_name
 * @property int|null $ppkom_id
 * @property string|null $ppkom_name
 * @property string|null $ppkom_nip
 * @property int|null $executor_id
 * @property string|null $executor_name
 * @property int|null $object_type_id
 * @property string|null $object_type_name
 * @property int|null $critical_day_at
 * @property string $start_date Start Date
 * @property string $end_date End Date
 * @property string|null $finally_finished_date
 * @property int $fiscal_year Fiscal Year
 * @property string|null $provider_selection_start_date
 * @property string|null $provider_selection_end_date
 * @property string|null $contractor_name
 * @property string|null $contract_number
 * @property string|null $contract_date
 * @property string|null $spmk_number
 * @property string|null $spmk_date
 * @property float $latest_plan_financial_budget Latest Financial Budget Plan
 * @property float $latest_plan_physical_percent Latest Physical Percent Progress Plan
 * @property float $latest_monitored_financial_budget Latest Financial Budget Monitored
 * @property float $latest_monitored_physical_percent Latest Physical Percent Progress Monitored
 * @property int $implementation_status Implementation Status
 * @property int $estimated_week_finished
 * @property int|null $work_days
 * @property int $is_active
 * @property int $is_bast_done
 * @property int $is_bpjstk
 * @property string|null $bpjstk_number
 * @property float $bpjstk_contrib_amount
 * @property int $bpjstk_number_workers_protected
 * @property string|null $location_description
 * @property int|null $kecamatan_id
 * @property string|null $default_lat
 * @property string|null $default_lng
 * @property string|null $notes
 * @property float|null $work_range_start
 * @property float|null $work_range_end
 * @property int $kecamatan_id
 * @property string $created_at Created At
 * @property string $updated_at Update At
 * @property int|null $created_by Created By
 * @property int|null $updated_by Updated By
 * @property float $latest_plan_financial_budget_percent Latest Financial Budget Plan at Percent
 * @property float $latest_monitored_financial_budget_percent Latest Financial Budget Monitored at Percent
 * 
 * @property MonitoringProgress[] $progress
 * @property Rup $rup
 * @property Teamlist $team
 * @property ObjectType $objectType
 * @property Satker $unit
 * @property RefKecamatan $kecamatan
 * @property MonitoringProgress $latestMonitoring
 */
class MonitoringTask extends \yii\db\ActiveRecord
{
    public $sess;
    public $latlng = '';
    public $latest_plan_financial_budget_percent;
    public $latest_monitored_financial_budget_percent;
    public $idjenispengadaanrup;
    public $idjenispengadaanpbj;

    // const IMPLEMENTATION_PREPARATION = 0;
    // const IMPLEMENTATION_PBJ_PROCESS = 1;
    // const IMPLEMENTATION_ONGOING = 2;
    // const IMPLEMENTATION_FINISHED = 3;
    const WORK_COMPLETED = 0;
    const WORK_ON_PROCESS = 1;
    const WORK_DELAYED = 2;
    const MONITORING_NOT_YET = 3;
    const BPJSTK_NOT_YET = 4;
    const PREDICTION_COMPLETED = 5;
    const PREDICTION_ON_PROCESS = 6;
    const CRITICAL_CONTRACT_1 = 7;
    const CRITICAL_CONTRACT_2 = 8;
    const TASK_ACTIVE = 1;
    const TASK_NOT_ACTIVE = 0;
    const BAST_DONE = 1;
    const BAST_NOT_DONE = 0;
    const USE_BPJSTK = 1;
    const NOT_USE_BPJSTK = 0;

    public static function implementationStatusNames()
    {
        return [
            // self::IMPLEMENTATION_PREPARATION => Yii::t('app', 'Preparation'),
            // self::IMPLEMENTATION_PBJ_PROCESS => Yii::t('app', 'PBJ Process'),
            // self::IMPLEMENTATION_ONGOING => Yii::t('app', 'On Going'),
            // self::IMPLEMENTATION_FINISHED => Yii::t('app', 'Finished'),
            self::WORK_COMPLETED => Yii::t('app', 'Work Completed'),
            self::WORK_ON_PROCESS => Yii::t('app', 'Work On Process'),
            self::WORK_DELAYED => Yii::t('app', 'Work Delayed'),
            self::MONITORING_NOT_YET => Yii::t('app', 'Monitoring Not Yet'),
            self::BPJSTK_NOT_YET => Yii::t('app', 'BPJSTK Not Yet'),
            self::PREDICTION_COMPLETED => Yii::t('app', 'Predicted work completed in the selected month'),
            self::PREDICTION_ON_PROCESS => Yii::t('app', 'Predicted work on process in the selected month'),
            self::CRITICAL_CONTRACT_1 => Yii::t('app', 'Critical Contract 1'),
            self::CRITICAL_CONTRACT_2 => Yii::t('app', 'Critical Contract 2'),
        ];
    }


    public static function taskActiveStatusName()
    {
        return [
            self::TASK_NOT_ACTIVE => Yii::t('app', 'No'),
            self::TASK_ACTIVE => Yii::t('app', 'Yes'),
        ];
    }

    public static function bastDoneStatusName()
    {
        return [
            self::BAST_NOT_DONE => Yii::t('app', 'No'),
            self::BAST_DONE => Yii::t('app', 'Yes'),
        ];
    }

    public static function bpjstkStatusName()
    {
        return [
            self::NOT_USE_BPJSTK => Yii::t('app', 'No'),
            self::USE_BPJSTK => Yii::t('app', 'Yes'),
        ];
    }

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
        return '{{%monitoring_task}}';
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
            [['name', 'unit_id', 'start_date', 'end_date', 'fiscal_year'], 'required'],
            [['name', 'contractor_name', 'location_description', 'notes', 'program_name', 'kegiatan_name', 'data_adendum'], 'string'],
            [['work_volume', 'work_units_of', 'work_finished_number'], 'string'],
            [['idrup', 'program_id', 'kegiatan_id', 'pbj_id', 'unit_id', 'team_id', 'supervisor_id', 'ppkom_id', 'executor_id', 'object_type_id', 'critical_day_at', 'fiscal_year', 'implementation_status', 'estimated_week_finished', 'is_active', 'is_bast_done', 'is_bpjstk', 'bpjstk_number_workers_protected', 'kecamatan_id', 'kelurahan_id', 'critical_condition_id', 'created_by', 'updated_by'], 'integer'],
            [['budget', 'hps', 'bid_price', 'final_price', 'latest_plan_financial_budget', 'latest_plan_physical_percent', 'latest_monitored_financial_budget', 'latest_monitored_physical_percent', 'bpjstk_contrib_amount'], 'number'],
            [['start_date', 'end_date', 'finally_finished_date', 'provider_selection_start_date', 'provider_selection_end_date', 'contract_date', 'spmk_date', 'created_at', 'updated_at', 'bast_date'], 'safe'],
            [['unit_name', 'team_name', 'supervisor_name', 'ppkom_name', 'ppkom_nip', 'executor_name', 'object_type_name', 'contract_number', 'spmk_number', 'bpjstk_number', 'work_days', 'default_lat', 'default_lng', 'bast_no'], 'string', 'max' => 255],
            [['idrup', 'budget', 'final_price', 'bid_price', 'hps', 'work_days', 'team_id', 'object_type_id', 'kecamatan_id'], 'required'],
            [['idrup'], 'validateAlreadyUsedId'],
            [['work_range_start', 'work_range_end'], 'number'],
            [['contractor_name', 'contract_date', 'contract_number', 'spmk_date', 'spmk_number'], 'required'],
            [['idjenispengadaanrup', 'idjenispengadaanpbj'], 'safe'],
        ];
    }

    // Additional Validation
    /**
     * @param string $attribute the attribute currently being validated
     * @param mixed $params the value of the "params" given in the rule
     * @param \yii\validators\InlineValidator $validator related InlineValidator instance.
     * This parameter is available since version 2.0.11.
     * @param mixed $current the currently validated value of attribute.
     * This parameter is available since version 2.0.36.
     */
    public function validateAlreadyUsedId($attribute, $params, $validator)
    {
        $taskusingrup = self::find()->where([$attribute => $this->$attribute])->andWhere(['fiscal_year' => $this->fiscal_year])->one();
        if ($taskusingrup && ($taskusingrup->id != $this->id)) {
            $attrname = isset(self::attributeLabels()[$attribute]) ? self::attributeLabels()[$attribute] : $attribute;
            $this->addError($attribute, Yii::t('monitoring', '{attr} is already used', ['attr' => $attrname]));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('monitoring', 'Name'),
            'idrup' => Yii::t('monitoring', 'RUP'),
            'program_id' => Yii::t('monitoring', 'Program ID'),
            'program_name' => Yii::t('monitoring', 'Program ID'),
            'kegiatan_id' => Yii::t('monitoring', 'Kegiatan ID'),
            'kegiatan_name' => Yii::t('monitoring', 'Kegiatan ID'),
            'pbj_id' => Yii::t('monitoring', 'PBJ'),
            'budget' => Yii::t('monitoring', 'Budget'),
            'hps' => Yii::t('monitoring', 'Hps'),
            'bid_price' => Yii::t('monitoring', 'Bid Price'),
            'final_price' => Yii::t('monitoring', 'Final Price'),
            'unit_id' => Yii::t('monitoring', 'Unit ID'),
            'unit_name' => Yii::t('monitoring', 'Unit Name'),
            'team_id' => Yii::t('monitoring', 'Team ID'),
            'team_name' => Yii::t('monitoring', 'Team Name'),
            'supervisor_id' => Yii::t('monitoring', 'Supervisor ID'),
            'supervisor_name' => Yii::t('monitoring', 'Supervisor Name'),
            'ppkom_id' => Yii::t('monitoring', 'Ppkom ID'),
            'ppkom_name' => Yii::t('monitoring', 'Ppkom Name'),
            'ppkom_nip' => Yii::t('monitoring', 'Ppkom Nip'),
            'executor_id' => Yii::t('monitoring', 'Executor ID'),
            'executor_name' => Yii::t('monitoring', 'Executor Name'),
            'object_type_id' => Yii::t('monitoring', 'Object Type'),
            'object_type_name' => Yii::t('monitoring', 'Object Type'),
            'critical_day_at' => Yii::t('monitoring', 'Critical Day At'),
            'critical_condition_id' => Yii::t('app', 'Critical Condition'),
            'start_date' => Yii::t('monitoring', 'Start Date'),
            'end_date' => Yii::t('monitoring', 'End Date'),
            'finally_finished_date' => Yii::t('monitoring', 'Finally Finished Date'),
            'fiscal_year' => Yii::t('monitoring', 'Fiscal Year'),
            'provider_selection_start_date' => Yii::t('monitoring', 'Provider Selection Start Date'),
            'provider_selection_end_date' => Yii::t('monitoring', 'Provider Selection End Date'),
            'contractor_name' => Yii::t('monitoring', 'Contractor Name'),
            'contract_number' => Yii::t('monitoring', 'Contract Number'),
            'contract_date' => Yii::t('monitoring', 'Contract Date'),
            'spmk_number' => Yii::t('monitoring', 'Spmk Number'),
            'spmk_date' => Yii::t('monitoring', 'Spmk Date'),
            'latest_plan_financial_budget' => Yii::t('monitoring', 'Latest Plan Financial Budget'),
            'latest_plan_financial_budget_percent' => Yii::t('monitoring', 'Latest Plan Financial Budget') . ' (%)',
            'latest_plan_physical_percent' => Yii::t('monitoring', 'Latest Plan Physical Percent') . ' (%)',
            'latest_monitored_financial_budget' => Yii::t('monitoring', 'Latest Monitored Financial Budget'),
            'latest_monitored_financial_budget_percent' => Yii::t('monitoring', 'Latest Monitored Financial Budget') . ' (%)',
            'latest_monitored_physical_percent' => Yii::t('monitoring', 'Latest Monitored Physical Percent') . ' (%)',
            'implementation_status' => Yii::t('monitoring', 'Implementation Status'),
            'estimated_week_finished' => Yii::t('monitoring', 'Estimated Week Finished'),
            'work_days' => Yii::t('monitoring', 'Work Days'),
            'is_active' => Yii::t('monitoring', 'Is Active'),
            'is_bast_done' => Yii::t('monitoring', 'Is Bast Done'),
            'bast_no' => Yii::t('monitoring', 'Bast No'),
            'bast_date' => Yii::t('monitoring', 'Bast Date'),
            'is_bpjstk' => Yii::t('monitoring', 'Is Bpjstk'),
            'bpjstk_number' => Yii::t('monitoring', 'Bpjstk Number'),
            'bpjstk_contrib_amount' => Yii::t('monitoring', 'Bpjstk Contrib Amount'),
            'bpjstk_number_workers_protected' => Yii::t('monitoring', 'Bpjstk Number Workers Protected'),
            'location_description' => Yii::t('monitoring', 'Location Description'),
            'kecamatan_id' => Yii::t('app', 'Kecamatan'),
            'kelurahan_id' => Yii::t('app', 'Kelurahan'),
            'default_lat' => Yii::t('monitoring', 'Default Lat'),
            'default_lng' => Yii::t('monitoring', 'Default Lng'),
            'latlng' => Yii::t('monitoring', 'Location Point'),
            'notes' => Yii::t('monitoring', 'Notes'),
            'work_range_start' => Yii::t('monitoring', 'Work Range Start'),
            'work_range_end' => Yii::t('monitoring', 'Work Range End'),
            'work_volume' => Yii::t('monitoring', 'Work Volume'),
            'work_units_of' => Yii::t('monitoring', 'Work Units Of'),
            'work_finished_number' => Yii::t('monitoring', 'Work Finished Number'),
            'data_adendum' => Yii::t('monitoring', 'Adendum Data'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),

            //additional
            'idjenispengadaanrup' => 'Jenis Pengadaan (RUP)',
            'idjenispengadaanpbj' => 'Jenis Pengadaan (PBJ)',
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

    public function getUnit()
    {
        //menggunakan user untuk placeholder sementara
        return $this->hasOne(Satker::class, ['id' => 'unit_id']);
    }

    public function getRup()
    {
        //menggunakan user untuk placeholder sementara
        return $this->hasOne(Rup::class, ['idrup' => 'idrup']);
    }

    public function getPbj()
    {
        return $this->hasOne(Pbj::class, ['kodelelang' => 'pbj_id']);
    }

    public function getProgress()
    {
        return $this->hasMany(MonitoringProgress::class, ['monitoring_task_id' => 'id']);
    }

    public function getPictures()
    {
        return $this->hasMany(MonitoringTaskFile::class, ['monitoring_task_id' => 'id']);
    }

    public function getLocations()
    {
        return $this->hasMany(MonitoringTaskLocations::class, ['monitoring_task_id' => 'id']);
    }

    public function getTeam()
    {
        return $this->hasOne(Teamlist::class, ['id' => 'team_id']);
    }

    public function getObjectType()
    {
        return $this->hasOne(ObjectType::class, ['id' => 'object_type_id']);
    }

    public function getKecamatan()
    {
        return $this->hasOne(RefKecamatan::class, ['id' => 'kecamatan_id']);
    }

    public function getKelurahan()
    {
        return $this->hasOne(RefKelurahan::class, ['id' => 'kelurahan_id']);
    }

    public function getCriticalCondition()
    {
        return $this->hasOne(RefCriticalCondition::class, ['id' => 'critical_condition_id']);
    }

    public function getMcoCcos()
    {
        return $this->hasMany(McoCco::class, ['monitoring_task_id' => 'id']);
    }

    /**
     * Gets query for [[MonitoringPlanner]].
     *
     * @return \yii\db\ActiveQuery|MonitoringPlannerQuery
     */
    public function getChildPlanner()
    {
        return $this->hasMany(MonitoringPlanner::class, ['monitoring_task_id' => 'id']);
    }

    /**
     * Gets query for [[MonitoringEksekutor]].
     *
     * @return \yii\db\ActiveQuery|MonitoringEksekutorQuery
     */
    public function getChildEksekutor()
    {
        return $this->hasMany(MonitoringEksekutor::class, ['monitoring_task_id' => 'id']);
    }

    /**
     * Gets query for [[MonitoringSupervisor]].
     *
     * @return \yii\db\ActiveQuery|MonitoringSupervisorQuery
     */
    public function getChildSupervisor()
    {
        return $this->hasMany(MonitoringSupervisor::class, ['monitoring_task_id' => 'id']);
    }

    /**
     * Get monitoring progress by month
     * @return MonitoringProgress|array
     */
    public function getMonitoringByMonth($monthint = 0)
    {
        if ($monthint && ($monthint != 0)) {
            $monthint = (int)$monthint;
            return MonitoringProgress::find()->where(['monitoring_task_id' => $this->id, 'date_month' => $monthint])
                ->orderBy([
                    'order_no' => SORT_DESC,
                    'progress_date' => SORT_DESC,
                    'id' => SORT_DESC,
                ])
                ->all();
        }

        $montharr = [];
        $months = $this->getMonthMonitoringAvaiable();
        foreach ($months as $month) {
            $montharr[$month] = MonitoringProgress::find()->where(['monitoring_task_id' => $this->id, 'date_month' => $month])
                ->orderBy([
                    'order_no' => SORT_DESC,
                    'progress_date' => SORT_DESC,
                    'id' => SORT_DESC,
                ])
                ->all();
        }
        return $montharr;
    }

    /**
     * @param int $number Number, can be month or week or neither for all
     * @param string $type Type, can be 'week / order_no', 'month', or 'all'
     * @return float Progress minimum range
     */
    public function getProgressMinRange($number = 1, $type = 'month')
    {
        if ($type == 'month') {
            if ($number && ($number != 0)) {
                $monthint = (int)$number;
                return (float)MonitoringProgress::find()
                    ->select('physical_percent')
                    ->where(['monitoring_task_id' => $this->id, 'date_month' => $monthint])
                    ->min('physical_percent');
            }
        } else if (($type == 'week') || ($type == 'order_no')) {
            if ($number && ($number != 0)) {
                $order_no = (int)$number;
                return (float)MonitoringProgress::find()
                    ->select('physical_percent')
                    ->where(['monitoring_task_id' => $this->id, 'order_no' => $order_no])
                    ->min('physical_percent');
            }
        } else {
            return (float)MonitoringProgress::find()
                ->select('physical_percent')
                ->where(['monitoring_task_id' => $this->id])
                ->min('physical_percent');
        }
        return null;
    }

    /**
     * @param int $number Number, can be month or week or neither for all
     * @param string $type Type, can be 'week / order_no', 'month', or 'all'
     * @return float Progress minimum range
     */
    public function getProgressMaxRange($number = 1, $type = 'month')
    {
        if ($type == 'month') {
            if ($number && ($number != 0)) {
                $monthint = (int)$number;
                return (float)MonitoringProgress::find()
                    ->select('physical_percent')
                    ->where(['monitoring_task_id' => $this->id, 'date_month' => $monthint])
                    ->max('physical_percent');
            }
        } else if (($type == 'week') || ($type == 'order_no')) {
            if ($number && ($number != 0)) {
                $order_no = (int)$number;
                return (float)MonitoringProgress::find()
                    ->select('physical_percent')
                    ->where(['monitoring_task_id' => $this->id, 'order_no' => $order_no])
                    ->max('physical_percent');
            }
        } else {
            return (float)MonitoringProgress::find()
                ->select('physical_percent')
                ->where(['monitoring_task_id' => $this->id])
                ->max('physical_percent');
        }
        return null;
    }

    /**
     * Get latest monitoring progress by month
     * @return MonitoringProgress|array
     */
    public function getLatestMonitoringByMonth($monthint = 0)
    {
        if ($monthint && ($monthint != 0)) {
            $monthint = (int)$monthint;
            return MonitoringProgress::find()->where(['monitoring_task_id' => $this->id, 'date_month' => $monthint])
                ->orderBy([
                    'order_no' => SORT_DESC,
                    'progress_date' => SORT_DESC,
                    'id' => SORT_DESC,
                ])
                ->one();
        }

        $montharr = [];
        $months = $this->getMonthMonitoringAvaiable();
        foreach ($months as $month) {
            $montharr[$month] = MonitoringProgress::find()->where(['monitoring_task_id' => $this->id, 'date_month' => $month])
                ->orderBy([
                    'order_no' => SORT_DESC,
                    'progress_date' => SORT_DESC,
                    'id' => SORT_DESC,
                ])
                ->one();
        }
        return $montharr;
    }

    public function getMonthMonitoringAvaiable()
    {
        $months = MonitoringProgress::find()->select('date_month')
            ->distinct()
            ->where(['monitoring_task_id' => $this->id])
            ->orderBy(['date_month' => SORT_DESC])
            ->column();
        // echo json_encode($months);exit;
        return $months;
    }

    public function getOrderMonitoringAvaiable()
    {
        $orders = MonitoringProgress::find()->select('order_no')
            ->distinct()
            ->where(['monitoring_task_id' => $this->id])
            ->orderBy(['order_no' => SORT_DESC])
            ->column();
        // echo json_encode($months);exit;
        return $orders;
    }

    public function getDayMonitoringAvaiable()
    {
        $orders = MonitoringProgress::find()->select('progress_date')
            ->distinct()
            ->where(['monitoring_task_id' => $this->id])
            ->orderBy(['progress_date' => SORT_DESC])
            ->column();
        // echo json_encode($months);exit;
        return $orders;
    }

    public function getOrderMonitoringAvaiableArrayKey()
    {
        $orders = $this->getOrderMonitoringAvaiable();
        $values = [];
        foreach ($orders as $value) {
            $values[$value] = $value;
        }
        return $values;
    }

    public function getDayMonitoringAvaiableArrayKey()
    {
        $days = $this->getDayMonitoringAvaiable();
        $values = [];
        foreach ($days as $value) {
            $values[$value] = Yii::$app->formatter->asDate($value);
        }
        return $values;
    }

    /**
     * Get monitoring progress by order
     * @return MonitoringProgress|array
     */
    public function getMonitoringByOrder($order_no = 0)
    {
        if ($order_no && ($order_no != 0)) {
            $order_no = (int)$order_no;
            return MonitoringProgress::find()->where(['monitoring_task_id' => $this->id, 'order_no' => $order_no])
                ->orderBy([
                    'order_no' => SORT_DESC,
                    'progress_date' => SORT_DESC,
                    'id' => SORT_DESC,
                ])
                ->all();
        }

        $order_no_arr = [];
        $orders = $this->getOrderMonitoringAvaiable();
        foreach ($orders as $order) {
            $order_no_arr[$order] = MonitoringProgress::find()->where(['monitoring_task_id' => $this->id, 'order_no' => $order])
                ->orderBy([
                    'order_no' => SORT_DESC,
                    'progress_date' => SORT_DESC,
                    'id' => SORT_DESC,
                ])
                ->all();
        }
        return $order_no_arr;
    }

    /**
     * Get monitoring progress by progress_date
     * @return MonitoringProgress|array
     */
    public function getMonitoringByProgressDate($progress_date = 0)
    {
        if ($progress_date && ($progress_date != 0)) {
            $progress_date = date('Y-m-d', strtotime($progress_date));
            return MonitoringProgress::find()->where(['monitoring_task_id' => $this->id, 'progress_date' => $progress_date])
                ->orderBy([
                    'progress_date' => SORT_DESC,
                    'order_no' => SORT_DESC,
                    'id' => SORT_DESC,
                ])
                ->all();
        }

        $day_no_arr = [];
        $days = $this->getDayMonitoringAvaiable();
        foreach ($days as $day) {
            $day_no_arr[$day] = MonitoringProgress::find()->where(['monitoring_task_id' => $this->id, 'progress_date' => $day])
                ->orderBy([
                    'progress_date' => SORT_DESC,
                    'order_no' => SORT_DESC,
                    'id' => SORT_DESC,
                ])
                ->all();
        }
        return $day_no_arr;
    }

    /**
     * Get latest monitoring progress by order avaiable
     * @return MonitoringProgress|array
     */
    public function getLatestMonitoringByOrder($order_no = 0)
    {
        if ($order_no && ($order_no != 0)) {
            $order_no = (int)$order_no;
            return MonitoringProgress::find()->where(['monitoring_task_id' => $this->id, 'order_no' => $order_no])
                ->orderBy([
                    'order_no' => SORT_DESC,
                    'progress_date' => SORT_DESC,
                    'id' => SORT_DESC,
                ])
                ->one();
        }

        $orderarr = [];
        $orders = $this->getOrderMonitoringAvaiable();
        foreach ($orders as $order) {
            $orderarr[$order] = MonitoringProgress::find()->where(['monitoring_task_id' => $this->id, 'order_no' => $order_no])
                ->orderBy([
                    'order_no' => SORT_DESC,
                    'progress_date' => SORT_DESC,
                    'id' => SORT_DESC,
                ])
                ->one();
        }
        return $orderarr;
    }

    public function getLatestMonitoring()
    {
        $monitoring = MonitoringProgress::find()
            ->where(['monitoring_task_id' => $this->id])
            ->orderBy([
                'progress_date' => SORT_DESC,
                'order_no' => SORT_DESC,
                'id' => SORT_DESC
            ])
            ->one();
        return $monitoring;
    }

    //Daerah Akhir Relation



    /**
     * {@inheritdoc}
     * @return MonitoringTaskQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MonitoringTaskQuery(get_called_class());
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
        if (isset($this->unit->namecode)) {
            $this->unit_name = $this->unit->namecode;
        }
        if (isset($this->team->name)) {
            $this->team_name = $this->team->name;
        }
        if (isset($this->objectType->name)) {
            $this->object_type_name = $this->objectType->name;
        }
        if (isset($this->program_id)) {
            $program = RefProgram::findOne(['id' => $this->program_id]);
            if ($program) {
                $this->program_name = $program->name;
            }
        }
        if (isset($this->kegiatan_id)) {
            $kegiatan = RefKegiatan::findOne(['id' => $this->kegiatan_id]);
            if ($kegiatan) {
                $this->kegiatan_name = $kegiatan->name;
            }
        }
        // $this->start_date = $this->start_date ? Yii::$app->formatter->asDate($this->start_date, 'yyyy-MM-dd') : null;
        // $this->end_date = $this->end_date ? Yii::$app->formatter->asDate($this->end_date, 'yyyy-MM-dd') : null;

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
        // $this->start_date = $this->start_date != '0000-00-00' ? Yii::$app->formatter->asDate($this->start_date, 'dd-MM-yyyy') : null;
        // $this->end_date = $this->end_date != '0000-00-00' ? Yii::$app->formatter->asDate($this->end_date, 'dd-MM-yyyy') : null;
        if ($this->default_lat && $this->default_lng) {
            $this->latlng = $this->default_lat . ',' . $this->default_lng;
        }
        if ($this->latest_plan_financial_budget) {
            $this->latest_plan_financial_budget_percent = $this->latest_plan_financial_budget / $this->final_price * 100;
        } else {
            $this->latest_plan_financial_budget_percent = 0;
        }
        if ($this->latest_monitored_financial_budget) {
            $this->latest_monitored_financial_budget_percent = $this->latest_monitored_financial_budget / $this->final_price * 100;
        } else {
            $this->latest_monitored_financial_budget_percent = 0;
        }
        // if ($this->idrup) {
        //     $this->idjenispengadaanrup = $this->rup->idjenispengadaan;
        // }
        // if ($this->pbj_id) {
        //     $this->idjenispengadaanpbj = $this->pbj->jenispengadaan;
        // }

        parent::afterFind();
        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            $newcco = new McoCco();
            $newcco->cco = 0;
            $newcco->monitoring_task_id = $this->id;
            $newcco->name = 'MCO';
            $newcco->save();
        }
    }

    /**
     * Sebelum hapus
     *
     * @return void
     */
    public function beforeDelete()
    {
        // monggo
        if (isset($this->progress[0]->id)) {
            if (isset(Yii::$app->session)) {
                Yii::$app->session->setFlash('warning', Yii::t('monitoring', 'Still have progress'));
            }
            return false;
        }
        if (isset($this->pictures[0]->id)) {
            if (isset(Yii::$app->session)) {
                Yii::$app->session->setFlash('warning', Yii::t('monitoring', 'Still have pictures'));
            }
            return false;
        }
        if (isset($this->locations[0]->id)) {
            if (isset(Yii::$app->session)) {
                Yii::$app->session->setFlash('warning', Yii::t('monitoring', 'Still have location'));
            }
            return false;
        }
        parent::beforeDelete();
        return true;
    }
    //Daerah Akhir Event Driven

}