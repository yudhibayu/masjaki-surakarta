<?php

namespace app\modules\rup\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\rup\models\RefProgram;

/**
 * RefProgramSearch represents the model behind the search form of `app\modules\rup\models\RefProgram`.
 */
class RefProgramSearch extends RefProgram
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'kd_satker', 'kd_program', 'fiscal_year', 'is_deleted', 'created_by', 'updated_by'], 'integer'],
            [['name', 'kd_program_str', 'kd_program_lokal', 'created_at', 'updated_at'], 'safe'],
            [['pagu'], 'number'],
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
        $query = RefProgram::find();
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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'kd_satker' => $this->kd_satker,
            'kd_program' => $this->kd_program,
            'pagu' => $this->pagu,
            'fiscal_year' => $this->fiscal_year,
            'is_deleted' => $this->is_deleted,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'kd_program_str', $this->kd_program_str])
            ->andFilterWhere(['like', 'kd_program_lokal', $this->kd_program_lokal]);

        return $dataProvider;
    }
}
