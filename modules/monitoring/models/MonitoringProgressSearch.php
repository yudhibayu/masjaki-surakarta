<?php

namespace app\modules\monitoring\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\monitoring\models\MonitoringProgress;

/**
 * MonitoringProgressSearch represents the model behind the search form of `app\modules\monitoring\models\MonitoringProgress`.
 */
class MonitoringProgressSearch extends MonitoringProgress
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'order_no', 'monitoring_task_id', 'created_by', 'updated_by', 'num_minor_accident', 'num_moderate_accident', 'num_serious_accident',], 'integer'],
            [['name', 'monitoring_task_name', 'progress_date', 'description', 'created_at', 'updated_at', 'work_items_of_potential_accident', 'work_items_of_occured_accident', 'verified',], 'safe'],
            [['planned_financial_budget', 'planned_physical_percent', 'financial_budget', 'physical_percent'], 'number'],
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
    public function search($params, $task_id = null, $pagesize = null)
    {
        $query = MonitoringProgress::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if ($task_id) {
            $query->andWhere(['monitoring_task_id' => $task_id]);
            $dataProvider->sort->defaultOrder = ['progress_date' => SORT_DESC, 'id' => SORT_DESC];
        }
        if ($pagesize) {
            $dataProvider->pagination->pageSize = $pagesize;
        }

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'order_no' => $this->order_no,
            'monitoring_task_id' => $this->monitoring_task_id,
            'progress_date' => $this->progress_date,
            'planned_financial_budget' => $this->planned_financial_budget,
            'planned_physical_percent' => $this->planned_physical_percent,
            'financial_budget' => $this->financial_budget,
            'physical_percent' => $this->physical_percent,
            'num_minor_accident' => $this->num_minor_accident,
            'num_moderate_accident' => $this->num_moderate_accident,
            'num_serious_accident' => $this->num_serious_accident,
            'verified' => $this->verified,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'monitoring_task_name', $this->monitoring_task_name])
            ->andFilterWhere(['like', 'work_items_of_potential_accident', $this->work_items_of_potential_accident])
            ->andFilterWhere(['like', 'work_items_of_occured_accident', $this->work_items_of_occured_accident])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
