<?php

namespace app\modules\monitoring\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\monitoring\models\MonitoringSupervisor;

/**
 * MonitoringSupervisorSearch represents the model behind the search form of `app\modules\monitoring\models\MonitoringSupervisor`.
 */
class MonitoringSupervisorSearch extends MonitoringSupervisor
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'monitoring_task_id', 'supervisor_id', 'user_id', 'created_by', 'updated_by', 'is_active'], 'integer'],
            [['supervisor_name', 'created_at', 'updated_at', 'description'], 'safe'],
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
        $query = MonitoringSupervisor::find();
                // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        if ($task_id) {
            $query->andWhere(['monitoring_task_id' => $task_id]);
            $dataProvider->sort->defaultOrder = ['id' => SORT_ASC];
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
            'monitoring_task_id' => $this->monitoring_task_id,
            'supervisor_id' => $this->supervisor_id,
            'user_id' => $this->user_id,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'supervisor_name', $this->supervisor_name]);
        $query->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
