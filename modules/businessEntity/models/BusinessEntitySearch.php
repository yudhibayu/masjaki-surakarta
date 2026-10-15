<?php

namespace app\modules\businessEntity\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\businessEntity\models\BusinessEntity;

/**
 * BusinessEntitySearch represents the model behind the search form of `app\modules\businessEntity\models\BusinessEntity`.
 */
class BusinessEntitySearch extends BusinessEntity
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'file_size', 'pkp_confirmation_number', 'postal_code'], 'integer'],
            [['username', 'npwp', 'phone_number', 'password_hash', 'email', 'company_name', 'form_of_business', 'photo', 'website', 'fax', 'company_address', 'country', 'province', 'regency', 'subdistrict', 'ward', 'id_country', 'id_province', 'id_regency', 'id_subdistrict', 'id_ward', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by', 'file_extension', 'file_name', 'file_path'], 'safe'],
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
    public function search($params, $verificationStatus = null)
    {
        $query = BusinessEntity::find();
        // add conditions that should always apply here

        if (isset(Yii::$app->session['badan_usaha']['id_business_entity'])) {
            $businessEntity = Yii::$app->session['badan_usaha']['id_business_entity'];
            $query->andFilterWhere([
                'id' => $businessEntity,
            ]);
        }

        if ($verificationStatus !== null) {
            $query->andFilterWhere([
                'account_verification' => $verificationStatus,
            ]);
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
            'npwp' => $this->npwp,
            'phone_number' => $this->phone_number,
            'pkp_confirmation_number' => $this->pkp_confirmation_number,
            'postal_code' => $this->postal_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'file_size' => $this->file_size,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterWhere(['like', 'form_of_business', $this->form_of_business])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'file_path', $this->file_path])
            ->andFilterWhere(['like', 'file_extension', $this->file_extension])
            ->andFilterWhere(['like', 'file_name', $this->file_name])
            ->andFilterWhere(['like', 'website', $this->website])
            ->andFilterWhere(['like', 'fax', $this->fax])
            ->andFilterWhere(['like', 'company_address', $this->company_address])
            ->andFilterWhere(['like', 'id_country', $this->id_country])
            ->andFilterWhere(['like', 'id_province', $this->id_province])
            ->andFilterWhere(['like', 'id_regency', $this->id_regency])
            ->andFilterWhere(['like', 'id_subdistrict', $this->id_subdistrict])
            ->andFilterWhere(['like', 'id_ward', $this->id_ward])
            ->andFilterWhere(['like', 'country', $this->country])
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