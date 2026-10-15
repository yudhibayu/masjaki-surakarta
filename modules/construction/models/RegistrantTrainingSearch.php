<?php

namespace app\modules\construction\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\construction\models\RegistrantTraining;

/**
 * RegistrantTrainingSearch represents the model behind the search form of `app\modules\construction\models\RegistrantTraining`.
 */
class RegistrantTrainingSearch extends RegistrantTraining
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_training', 'id_labor', 'status'], 'integer'],
            [['name_training', 'name_labor', 'created_at', 'updated_at', 'created_by', 'updated_by', 'nik', 'subdistrict', 'ward'], 'safe'],
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
    public function search($params, $id_training = null, $status = null, $pagesize = null, $id_labor = null)
    {
        $query = RegistrantTraining::find();
        $query->joinWith('training');
        // add conditions that should always apply here
        if ($status !== null) {
            $query->andFilterWhere([
                'status' => $status,
            ]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);
        if ($id_labor) {
            $query->andWhere(['id_labor' => $id_labor]);
            $query->andWhere(['work_training.publication' => WorkTraining::PUBLICATED]);
        }
        if ($id_training) {
            $query->andWhere(['id_training' => $id_training]);
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
            'id_training' => $this->id_training,
            'id_labor' => $this->id_labor,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name_training', $this->name_training])
            ->andFilterWhere(['like', 'name_labor', $this->name_labor])
            ->andFilterWhere(['like', 'nik', $this->nik])
            ->andFilterWhere(['like', 'subdistrict', $this->subdistrict])
            ->andFilterWhere(['like', 'ward', $this->ward])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
