<?php

namespace app\modules\construction\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\construction\models\ConstructionAccidentFile;

/**
 * ConstructionAccidentFileSearch represents the model behind the search form of `app\modules\construction\models\ConstructionAccidentFile`.
 */
class ConstructionAccidentFileSearch extends ConstructionAccidentFile
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'construction_accident_id', 'created_by','updated_by'], 'integer'],
            [['name','description','file_name', 'file_extension','created_at','updated_at'], 'safe'],
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
    public function search($params, $id_construction_accident = null)
    {
        $query = ConstructionAccidentFile::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        if ($id_construction_accident) {
            $query->andWhere(['construction_accident_id' => $id_construction_accident]);
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
            'construction_accident_id' => $this->construction_accident_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'file_name', $this->file_name])
            ->andFilterWhere(['like', 'file_extension', $this->file_extension])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
