<?php

namespace app\modules\referencesMasjaki\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\referencesMasjaki\models\RefNegara;

/**
 * RefNegaraSearch represents the model behind the search form of `app\modules\referencesMasjaki\models\RefNegara`.
 */
class RefNegaraSearch extends RefNegara
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'name', 'uc_name', 'iso', 'iso3', 'created_at', 'updated_at'], 'safe'],
            [['numcode', 'phonecode', 'created_by', 'updated_by'], 'integer'],
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
        $query = RefNegara::find();
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
            'numcode' => $this->numcode,
            'phonecode' => $this->phonecode,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'uc_name', $this->uc_name])
            ->andFilterWhere(['like', 'iso', $this->iso])
            ->andFilterWhere(['like', 'iso3', $this->iso3]);

        return $dataProvider;
    }
}
