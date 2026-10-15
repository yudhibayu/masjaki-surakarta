<?php

namespace app\modules\technicalspec\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\technicalspec\models\RefComponent;

/**
 * RefComponentSearch represents the model behind the search form of `app\modules\technicalspec\models\RefComponent`.
 */
class RefComponentSearch extends RefComponent
{
    public $kodeLengkap;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ref_component_category_id', 'created_by', 'updated_by'], 'integer'],
            [['name', 'code', 'description', 'created_at', 'updated_at', 'kodeLengkap'], 'safe'],
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
        $query = RefComponent::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['kodeLengkap'] = [
            'asc' => ['CONCAT(ref_component_category.code, "-" , ref_component.code)' => SORT_ASC],
            'desc' => ['CONCAT(ref_component_category.code, "-" , ref_component.code)' => SORT_DESC],
        ];

        // var_dump($dataProvider->sort->attributes);
        // exit;

        $this->load($params);

        $query->joinWith('category');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'ref_component_category_id' => $this->ref_component_category_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        $query->andFilterWhere(['like', 'ref_component_category.code', $this->kodeLengkap])
            ->orFilterWhere(['like', 'ref_component.code', $this->kodeLengkap])
            ->orFilterWhere(['like', 'CONCAT(ref_component_category.code, "-" , ref_component.code)', $this->kodeLengkap]);

        return $dataProvider;
    }
}
