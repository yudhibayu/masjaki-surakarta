<?php

namespace app\modules\businessEntity\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\businessEntity\models\BusinessClassification;

/**
 * BusinessClassificationSearch represents the model behind the search form of `app\modules\businessEntity\models\BusinessClassification`.
 */
class BusinessClassificationSearch extends BusinessClassification
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_business_permit', 'id_kbli'], 'integer'],
            [['kbli', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'safe'],
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
    public function search($params, $id_business_permit = null, $pagesize = null)
    {
        $query = BusinessClassification::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        if ($id_business_permit) {
            $query->andWhere(['id_business_permit' => $id_business_permit]);
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
            'id_business_permit' => $this->id_business_permit,
            'id_kbli' => $this->id_kbli,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'kbli', $this->kbli])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
