<?php

namespace app\modules\businessEntity\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\businessEntity\models\ExperienceBusinessEntity;

/**
 * ExperienceBusinessEntitySearch represents the model behind the search form of `app\modules\businessEntity\models\ExperienceBusinessEntity`.
 */
class ExperienceBusinessEntitySearch extends ExperienceBusinessEntity
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'contract_number', 'id_business_entity'], 'integer'],
            [['work_package_name', 'field_work', 'assignor_name_work', 'assignor_address_work', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'safe'],
            [['contract_value'], 'number'],
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
        $query = ExperienceBusinessEntity::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'contract_number' => $this->contract_number,
            'contract_value' => $this->contract_value,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id_business_entity' => $this->id_business_entity,
        ]);

        $query->andFilterWhere(['like', 'work_package_name', $this->work_package_name])
            ->andFilterWhere(['like', 'field_work', $this->field_work])
            ->andFilterWhere(['like', 'assignor_name_work', $this->assignor_name_work])
            ->andFilterWhere(['like', 'assignor_address_work', $this->assignor_address_work])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}