<?php

namespace app\modules\technicalspec\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\technicalspec\models\McoCco;

/**
 * McoCcoSearch represents the model behind the search form of `app\modules\technicalspec\models\McoCco`.
 */
class McoCcoSearch extends McoCco
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'monitoring_task_id', 'cco', 'created_by', 'updated_by'], 'integer'],
            [['name', 'monitoring_task_name', 'file_name', 'created_at', 'updated_at'], 'safe'],
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
        $query = McoCco::find();
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

        if ($monitoring_task_id) {
            $query->andWhere([
                'monitoring_task_id' => $monitoring_task_id,
            ]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'monitoring_task_id' => $this->monitoring_task_id,
            'cco' => $this->cco,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'monitoring_task_name', $this->monitoring_task_name])
            ->andFilterWhere(['like', 'file_name', $this->file_name]);

        return $dataProvider;
    }
}
