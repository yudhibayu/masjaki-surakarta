<?php

namespace app\modules\referencesMasjaki\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\referencesMasjaki\models\RefProvinsi;

/**
 * RefProvinsiSearch represents the model behind the search form of `app\modules\referencesMasjaki\models\RefProvinsi`.
 */
class RefProvinsiSearch extends RefProvinsi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ref_negara_id', 'ref_negara_name', 'name', 'created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
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
        $query = RefProvinsi::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'ref_negara_id', $this->ref_negara_id])
            ->andFilterWhere(['like', 'ref_negara_name', $this->ref_negara_name])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
