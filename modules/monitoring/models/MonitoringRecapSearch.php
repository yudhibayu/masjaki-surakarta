<?php

namespace app\modules\monitoring\models;

use app\models\Satker;
use app\models\Teamlist;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\monitoring\models\MonitoringTask;
use app\modules\references\models\ObjectType;
use Yii;
use yii\data\SqlDataProvider;
use yii\db\Expression;

/**
 * MonitoringRecapSearch represents the model behind the search form of `app\modules\monitoring\models\MonitoringTask`.
 */
class MonitoringRecapSearch extends MonitoringTask
{
    // public $name;
    public $team_id;
    public $team_name;
    public $unit_id;
    public $unit_name;
    public $object_type_id;
    public $object_type_name;
    public $implementation_status;
    public $sd_bulan;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'team_id', 'unit_id', 'object_type_id', 'sd_bulan'], 'integer'],
            [['name', 'team_name', 'unit_name', 'object_type_name', 'implementation_status'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        if (isset(Yii::$app->session['satker']['satker_id'])) {
            $this->unit_id = Yii::$app->session['satker']['satker_id'];
        }
        if (isset(Yii::$app->session['team']['team_id'])) {
            $this->team_id = Yii::$app->session['team']['team_id'];
        }
        // $query = MonitoringTask::find()->joinWith('progress')->groupBy('monitoring_progress.monitoring_task_id')->orderBy('monitoring_progress.order_no DESC');
        // add conditions that should always apply here
        $pgtbl = MonitoringProgress::tableName();
        $tktbl = MonitoringTask::tableName();
        $uttbl = Satker::tableName();
        $tmtbl = Teamlist::tableName();
        $obtbl = ObjectType::tableName();
        $query = MonitoringTask::find()
            ->select([
                "id" => "MAX($pgtbl.id)",
                "name" => "$tktbl.name",
                "object_type_name" => "$obtbl.name",
                "unit_name" => "$uttbl.name",
                "team_name" => "$tmtbl.name",
                "date_month" => "MAX($pgtbl.date_month)",
                "order_no" => "MAX($pgtbl.order_no)",
                "final_price" => "$tktbl.final_price",
                "contractor_name" => "$tktbl.contractor_name",
                // "fullRegistrationBPJS" => "$tktbl.contractor_name",
                // "targetandrealisasi" => "MAX($pgtbl.physical_percent)",
                "deviasi" => "MAX($pgtbl.physical_percent) - MAX($pgtbl.planned_physical_percent)",
                "end_date" => "$tktbl.end_date",
                "description" => "MAX($pgtbl.description)",

                //
                "monitoring_task_id" => "$tktbl.id",
                "team_id" => "$tmtbl.id",
                "unit_id" => "$uttbl.id",
                "object_type_id" => "$obtbl.id",
                // "implementation_status" => "$tktbl.implementation_status",
            ])
            ->joinWith('progress')
            ->joinWith('objectType')
            ->joinWith('unit')
            ->joinWith('team')
            ->groupBy($tktbl . '.id');


        if (isset(Yii::$app->session['year']['name']) || isset(Yii::$app->session['tahun'])) {
            $year = isset(Yii::$app->session['year']['name']) ? Yii::$app->session['year']['name'] : Yii::$app->session['tahun'];
            $query->andWhere([
                "$tktbl.fiscal_year" => $year,
            ]);
            $getAllTask = MonitoringTask::find()->select('id')->where(['fiscal_year' => $year])->column();
            $query->andWhere(['in', "$tktbl.id", $getAllTask]);

        }

        if(isset($params["MonitoringRecapSearch"]["implementation_status"])){
            if ($params["MonitoringRecapSearch"]["implementation_status"] == "0") {
                $query->andWhere([
                    'and',
                    ["$tktbl.latest_plan_physical_percent" => 100],
                    ["$tktbl.latest_monitored_physical_percent" => 100]
                ]);
            }
            if ($params["MonitoringRecapSearch"]["implementation_status"] == "1") {
                $query->andWhere([
                    'and',
                    ['<', "$tktbl.latest_plan_physical_percent", 100],
                    ['<', "$tktbl.latest_monitored_physical_percent", 100]
                ]);
            }
            if ($params["MonitoringRecapSearch"]["implementation_status"] == "2") {
                $query->andWhere('monitoring_task.latest_monitored_physical_percent < monitoring_task.latest_plan_physical_percent');
            }
            if ($params["MonitoringRecapSearch"]["implementation_status"] == "3") {
                $monitoring_task_id = MonitoringTask::find()->select('id')->column();
                $monitoring_progress_task_id = MonitoringProgress::find()->select('monitoring_task_id')->column();
                $result = array_diff($monitoring_task_id, $monitoring_progress_task_id);
                $query->andWhere([
                    'in', "$tktbl.id", $result
                ]);
            }
            if ($params["MonitoringRecapSearch"]["implementation_status"] == "4") {
                $query->andWhere(["$tktbl.is_bpjstk" => 0]);
            }
            if ($params["MonitoringRecapSearch"]["implementation_status"] == "5") {
                if($params["MonitoringRecapSearch"]["sd_bulan"] == NULL){
                    $query->andWhere([
                        'and',
                        ["$tktbl.latest_plan_physical_percent" => 100],
                        ["$tktbl.latest_monitored_physical_percent" => 100]
                    ]);
                    $query->andWhere(["$pgtbl.date_month" => date('n')]);
                } else {
                    $query->andWhere([
                        'and',
                        ["$tktbl.latest_plan_physical_percent" => 100],
                        ["$tktbl.latest_monitored_physical_percent" => 100]
                    ]);
                    $query->andWhere(["$pgtbl.date_month" => $params["MonitoringRecapSearch"]["sd_bulan"]]);
                }
            }
            if ($params["MonitoringRecapSearch"]["implementation_status"] == "6") {
                if ($params["MonitoringRecapSearch"]["sd_bulan"] == NULL) {
                    $query->andWhere([
                        'and',
                        ['<', "$tktbl.latest_plan_physical_percent", 100],
                        ['<', "$tktbl.latest_monitored_physical_percent", 100]
                    ]);
                    $query->andWhere(["$pgtbl.date_month" => date('n')]);
                } else {
                    $query->andWhere([
                        'and',
                        ['<', "$tktbl.latest_plan_physical_percent", 100],
                        ['<', "$tktbl.latest_monitored_physical_percent", 100]
                    ]);
                    $query->andWhere(["$pgtbl.date_month" => $params["MonitoringRecapSearch"]["sd_bulan"]]);
                }
            }
            if ($params["MonitoringRecapSearch"]["implementation_status"] == "7") {
                $query->andWhere(['between', "$tktbl.latest_plan_physical_percent", 0, 69.99]);
                $query->andWhere('monitoring_task.latest_monitored_physical_percent - monitoring_task.latest_plan_physical_percent <= -10');
            }
            if ($params["MonitoringRecapSearch"]["implementation_status"] == "8") {
                $query->andWhere(['between', "$tktbl.latest_plan_physical_percent", 70, 100]);
                $query->andWhere('monitoring_task.latest_monitored_physical_percent - monitoring_task.latest_plan_physical_percent <= -5');
            }
        }

        $this->load($params);

        // grid filtering conditions
        $query->andFilterHaving([
            'id' => $this->id,
            'team_id' => $this->team_id,
            'unit_id' => $this->unit_id,
            'object_type_id' => $this->object_type_id,
            // 'implementation_status' => $this->implementation_status,
        ]);

        $query->andFilterHaving(['like', 'name', $this->name])
            ->andFilterHaving(['<=', 'date_month', $this->sd_bulan])
            ->andFilterHaving(['like', 'object_type_name', $this->object_type_name])
            ->andFilterHaving(['like', 'unit_name', $this->unit_name])
            ->andFilterHaving(['like', 'team_name', $this->team_name]);

        $countquery = clone $query;

        $sqlProvider = new SqlDataProvider([
            'sql' => $query->createCommand()->rawSql,
            'db' => MonitoringTask::getDb(),
            'totalCount' => $countquery->count('id'),
            'sort' => [
                'attributes' => [
                    "id",
                    "name",
                    "object_type_name",
                    "unit_name",
                    "team_name",
                    "date_month",
                    "order_no",
                    "final_price",
                    "contractor_name",
                    // "fullRegistrationBPJS",
                    // "targetandrealisasi",
                    "deviasi",
                    "end_date",
                    // "description",
                    // "monitoring_task_id",
                    "team_id",
                    "unit_id",
                    "object_type_id",
                ],
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $sqlProvider;
    }
}
