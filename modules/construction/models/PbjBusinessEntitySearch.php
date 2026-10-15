<?php

namespace app\modules\construction\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\construction\models\PbjBusinessEntity;

/**
 * PbjBusinessEntitySearch represents the model behind the search form of `app\modules\construction\models\PbjBusinessEntity`.
 */
class PbjBusinessEntitySearch extends PbjBusinessEntity
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'kode_rup', 'kode_pbj', 'id_business_entity', 'user_id'], 'integer'],
            [['name_business_entity', 'created_at', 'updated_at', 'created_by', 'updated_by', 'nama_paket'], 'safe'],
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
    public function search($params, $kode_pbj = null, $id_business_entity = null)
    {
        $query = PbjBusinessEntity::find();
        // add conditions that should always apply here

        if ($kode_pbj) {
            $query->andWhere(['kode_pbj' => $kode_pbj]);
        }
        if ($id_business_entity) {
            $query->andWhere(['id_business_entity' => $id_business_entity]);
        }
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
            'id' => $this->id,
            'kode_rup' => $this->kode_rup,
            'kode_pbj' => $this->kode_pbj,
            'id_business_entity' => $this->id_business_entity,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name_business_entity', $this->name_business_entity])
            ->andFilterWhere(['like', 'nama_paket', $this->nama_paket])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}