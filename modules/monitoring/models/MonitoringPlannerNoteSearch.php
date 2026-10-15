<?php

namespace app\modules\monitoring\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\monitoring\models\MonitoringPlannerNote;

/**
 * MonitoringPlannerNoteSearch represents the model behind the search form of `app\modules\monitoring\models\MonitoringPlannerNote`.
 */
class MonitoringPlannerNoteSearch extends MonitoringPlannerNote
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'order_no', 'monitoring_task_id', 'date_day', 'date_month', 'date_year', 'created_by', 'updated_by'], 'integer'],
            [['name', 'monitoring_task_name', 'note_date', 'description', 'problem', 'solution', 'created_at', 'updated_at'], 'safe'],
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
    public function search($params, $monitoring_task_id = null)
    {
        $query = MonitoringPlannerNote::find();
        // add conditions that should always apply here
        if ($monitoring_task_id) {
            $query->andWhere([
                'monitoring_task_id' => $monitoring_task_id,
            ]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

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
            'note_date' => $this->note_date,
            'date_day' => $this->date_day,
            'date_month' => $this->date_month,
            'date_year' => $this->date_year,
            'planned_financial_budget' => $this->planned_financial_budget,
            'planned_physical_percent' => $this->planned_physical_percent,
            'financial_budget' => $this->financial_budget,
            'physical_percent' => $this->physical_percent,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'monitoring_task_name', $this->monitoring_task_name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'problem', $this->problem])
            ->andFilterWhere(['like', 'solution', $this->solution]);

        return $dataProvider;
    }
}
