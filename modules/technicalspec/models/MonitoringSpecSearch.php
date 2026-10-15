<?php

namespace app\modules\technicalspec\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\technicalspec\models\MonitoringSpec;

/**
 * MonitoringSpecSearch represents the model behind the search form of `app\modules\technicalspec\models\MonitoringSpec`.
 */
class MonitoringSpecSearch extends MonitoringSpec
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ded_data_id', 'monitoring_progress_id', 'monitoring_task_id', 'created_by', 'updated_by'], 'integer'],
            [['name', 'description', 'created_at', 'updated_at'], 'safe'],
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
    public function search($params, $monitoring_progress_id = null, $use_pagination = true)
    {
        $query = MonitoringSpec::find();
                // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($monitoring_progress_id) {
            $query->andWhere([
                'monitoring_progress_id' => $monitoring_progress_id,
            ]);
        }

        if (!$use_pagination) {
            $dataProvider->pagination = false;
            $dataProvider->sort = false;
            $query->orderBy(['name' => SORT_ASC]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'ded_data_id' => $this->ded_data_id,
            'monitoring_progress_id' => $this->monitoring_progress_id,
            'monitoring_task_id' => $this->monitoring_task_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
