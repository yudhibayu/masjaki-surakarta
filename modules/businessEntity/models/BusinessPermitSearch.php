<?php

namespace app\modules\businessEntity\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\businessEntity\models\BusinessPermit;

/**
 * BusinessPermitSearch represents the model behind the search form of `app\modules\businessEntity\models\BusinessPermit`.
 */
class BusinessPermitSearch extends BusinessPermit
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'business_permit_type', 'reference_number', 'id_business_qualification'], 'integer'],
            [['valid_until', 'business_qualification', 'grantor_institution', 'business_permit_file', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'], 'safe'],
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
    public function search($params, $id_business_entity = null, $pagesize = null)
    {
        $query = BusinessPermit::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        if ($id_business_entity) {
            $query->andWhere(['id_business_entity' => $id_business_entity]);
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
            'business_permit_type' => $this->business_permit_type,
            'reference_number' => $this->reference_number,
            'valid_until' => $this->valid_until,
            'id_business_qualification' => $this->id_business_qualification,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'grantor_institution', $this->grantor_institution])
            ->andFilterWhere(['like', 'business_permit_file', $this->business_permit_file])
            ->andFilterWhere(['like', 'business_qualification', $this->business_qualification])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by]);

        return $dataProvider;
    }
}
