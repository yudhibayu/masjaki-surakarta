<?php

namespace app\modules\businessEntity\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\businessEntity\models\Administrator;

/**
 * AdministratorSearch represents the model behind the search form of `app\modules\businessEntity\models\Administrator`.
 */
class AdministratorSearch extends Administrator
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'type_of_management'], 'integer'],
            [['name', 'position', 'served_since', 'served_arrived', 'citizenship', 'address', 'province', 'regency', 'subdistrict', 'ward', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by', 'nik_passport_number', 'npwp', 'employment_bpjs_number', 'number_bpjs_health',], 'safe'],
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
        $query = Administrator::find();
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
            'nik_passport_number' => $this->nik_passport_number,
            'npwp' => $this->npwp,
            'employment_bpjs_number' => $this->employment_bpjs_number,
            'number_bpjs_health' => $this->number_bpjs_health,
            'status' => $this->status,
            'served_since' => $this->served_since,
            'served_arrived' => $this->served_arrived,
            'type_of_management' => $this->type_of_management,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'position', $this->position])
            ->andFilterWhere(['like', 'citizenship', $this->citizenship])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'regency', $this->regency])
            ->andFilterWhere(['like', 'subdistrict', $this->subdistrict])
            ->andFilterWhere(['like', 'ward', $this->ward])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by]);

        return $dataProvider;
    }
}
