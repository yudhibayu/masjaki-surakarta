<?php

namespace app\modules\technicalspec\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\technicalspec\models\McoCcoFile;

/**
 * McoCcoFileSearch represents the model behind the search form of `app\modules\technicalspec\models\McoCCoFile`.
 */
class McoCcoFileSearch extends McoCcoFile
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'mco_cco_id', 'monitoring_task_id', 'created_by', 'updated_by'], 'integer'],
            [['file_name', 'description', 'created_at', 'updated_at'], 'safe'],
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
    public function search($params, $mco_cco_id = null, $use_pagination = true)
    {
        $query = McoCCoFile::find();
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

        if ($mco_cco_id) {
            $query->andWhere([
                'mco_cco_id' => $mco_cco_id,
            ]);
        }

        if (!$use_pagination) {
            $dataProvider->pagination = false;
            $dataProvider->sort = false;
            $query->orderBy(['id' => SORT_ASC]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'mco_cco_id' => $this->mco_cco_id,
            'monitoring_task_id' => $this->monitoring_task_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'file_name', $this->file_name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
