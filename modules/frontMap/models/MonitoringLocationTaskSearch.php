<?php

namespace app\modules\frontMap\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\monitoring\models\MonitoringTask;
use Yii;

/**
 * MonitoringTaskSearch represents the model behind the search form of `app\modules\monitoring\models\MonitoringTask`.
 */
class MonitoringLocationTaskSearch extends MonitoringTask
{
    public $sumberdana;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'contractor_name', 'location_description', 'notes', 'program_name', 'kegiatan_name',], 'safe'],
            [['idrup', 'program_id', 'kegiatan_id', 'pbj_id', 'unit_id', 'team_id', 'supervisor_id', 'ppkom_id', 'executor_id', 'object_type_id', 'critical_day_at', 'fiscal_year', 'implementation_status', 'estimated_week_finished', 'is_active', 'is_bast_done', 'is_bpjstk', 'bpjstk_number_workers_protected', 'created_by', 'updated_by', 'kecamatan_id'], 'integer'],
            [['budget', 'hps', 'bid_price', 'final_price', 'latest_plan_financial_budget', 'latest_plan_physical_percent', 'latest_monitored_financial_budget', 'latest_monitored_physical_percent', 'bpjstk_contrib_amount'], 'number'],
            [['start_date', 'end_date', 'finally_finished_date', 'provider_selection_start_date', 'provider_selection_end_date', 'contract_date', 'spmk_date', 'created_at', 'updated_at'], 'safe'],
            [['unit_name', 'team_name', 'supervisor_name', 'ppkom_name', 'ppkom_nip', 'executor_name', 'object_type_name', 'contract_number', 'spmk_number', 'bpjstk_number'], 'safe'],
            [['work_range_start', 'work_range_end'], 'number'],
            [['sumberdana', 'progresspercentfilter'], 'safe'],
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

    public static function percentLabels()
    {
        $labels = [];
        $labels['0-25'] = '0-25%';
        $labels['25-50'] = '25-50%';
        $labels['50-75'] = '50-75%';
        $labels['75-99'] = '75-99%';
        $labels['99-100'] = '99-100%';
        return $labels;
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
        $query = MonitoringTask::find();
        // add conditions that should always apply here

        $year = isset(Yii::$app->session['year']['name']) ? Yii::$app->session['year']['name'] : null;
        if (!$year) {
            $year = isset(Yii::$app->session['tahun']) ? Yii::$app->session['tahun'] : date('Y');
        }

        // var_dump($params);exit;

        $paramReady = false;
        foreach ($params['MonitoringLocationTaskSearch'] as $key => $value) {
            if ($value != '') {
                $paramReady = true;
            }
        }
        // var_dump($paramReady);exit;

        if (!$paramReady) {
            $query->andWhere([
                'fiscal_year' => 1,
            ]);
        } else {
            // $query->andWhere([
            //     'fiscal_year' => $year,
            // ]);
            $this->load($params);

            $query->joinWith('rup');


            // grid filtering conditions
            $query->andFilterWhere([
                'id' => $this->id,
                'budget' => $this->budget,
                'idrup' => $this->idrup,
                'pbj_id' => $this->pbj_id,
                'unit_id' => $this->unit_id,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                // 'fiscal_year' => $this->fiscal_year,
                'latest_plan_financial_budget' => $this->latest_plan_financial_budget,
                'latest_plan_physical_percent' => $this->latest_plan_physical_percent,
                // 'latest_monitored_financial_budget' => $this->latest_monitored_financial_budget,
                // 'latest_monitored_physical_percent' => $this->latest_monitored_physical_percent,
                'implementation_status' => $this->implementation_status,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'created_by' => $this->created_by,
                'updated_by' => $this->updated_by,
            ]);

            $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['in', 'object_type_id', $this->object_type_id])
                ->andFilterWhere(['in', 'team_id', $this->team_id])
                ->andFilterWhere(['in', 'is_bast_done', $this->is_bast_done])
                ->andFilterWhere(['in', 'is_active', $this->is_active])
                ->andFilterWhere(['in', 'kecamatan_id', $this->kecamatan_id])
                ->andFilterWhere(['in', 'rup.sumberdana', $this->sumberdana])
                ->andFilterWhere(['in', 'fiscal_year', $this->fiscal_year])
                ->andFilterWhere(['like', 'unit_name', $this->unit_name]);

            // Realisasi Fisik
            if ($this->latest_monitored_physical_percent) {
                if (is_array($this->latest_monitored_physical_percent)) {
                    for ($i = 0; $i < sizeof($this->latest_monitored_physical_percent); $i++) {
                        $percentselect = $this->latest_monitored_physical_percent[$i];
                        $range = explode('-', $percentselect);
                        if (sizeof($range) == 2) {
                            $first = (float)$range[0];
                            $last = (float)$range[1];
                            if ($i == 0) {
                                $query->andFilterWhere(['between', 'latest_monitored_physical_percent', $first, $last]);
                            }
                            $query->orFilterWhere(['between', 'latest_monitored_physical_percent', $first, $last]);
                        }
                    }
                } else {
                    if (isset($this->percentLabels()[$this->latest_monitored_physical_percent])) {
                        $range = explode('-', $this->latest_monitored_physical_percent);
                        if (sizeof($range) == 2) {
                            $first = (float)$range[0];
                            $last = (float)$range[1];
                            $query->andFilterWhere(['between', 'latest_monitored_physical_percent', $first, $last]);
                        }
                    }
                }
            }
        }
        return $query->all();
    }
}
