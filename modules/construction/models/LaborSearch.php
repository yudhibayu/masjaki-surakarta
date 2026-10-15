<?php

namespace app\modules\construction\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\construction\models\Labor;

/**
 * LaborSearch represents the model behind the search form of `app\modules\construction\models\Labor`.
 */
class LaborSearch extends Labor
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'phone_number', 'long_work_experience'], 'integer'],
            [['name', 'email', 'username', 'password_hash', 'account_verification', 'skilled_power_type', 'citizenship', 'last_education', 'employment_status', 'professional_expertise', 'address', 'country', 'province', 'regency', 'subdistrict', 'ward', 'id_country', 'id_province', 'id_regency', 'id_subdistrict', 'id_ward', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by', 'description', 'nik_passport_number', 'bpjs_health_number', 'employment_bpjs_number', 'npwp',], 'safe'],
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
    public function search($params, $verificationStatus = null, $menganggur = false, $kebekerjaan = null)
    {
        $query = Labor::find();
        if (isset(Yii::$app->session['tenaga_kerja']['id_labor'])) {
            $labor = Yii::$app->session['tenaga_kerja']['id_labor'];
            $query->andFilterWhere([
                'id' => $labor,
            ]);
        }
        if ($menganggur == true) {
            $subquery = RegistrantVacancies::find()
                ->joinWith('vacancies')
                ->where(['construction_vacancies.publication' => ConstructionVacancies::PUBLICATED])
                ->select('registrant_vacancies.id_labor');
            $query->andWhere(['not in', 'id', $subquery]);
        }
        if ($kebekerjaan !== null) {
            $subquery = RegistrantVacancies::find()
                ->joinWith('vacancies')
                ->where([
                    'status' => $kebekerjaan,
                    'construction_vacancies.publication' => ConstructionVacancies::PUBLICATED
                ])
                ->select('id_labor');
            $query->andWhere(['id' => $subquery]);
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
            'nik_passport_number' => $this->nik_passport_number,
            'npwp' => $this->npwp,
            'phone_number' => $this->phone_number,
            'bpjs_health_number' => $this->bpjs_health_number,
            'employment_bpjs_number' => $this->employment_bpjs_number,
            'long_work_experience' => $this->long_work_experience,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'account_verification', $this->account_verification])
            ->andFilterWhere(['like', 'skilled_power_type', $this->skilled_power_type])
            ->andFilterWhere(['like', 'citizenship', $this->citizenship])
            ->andFilterWhere(['like', 'last_education', $this->last_education])
            ->andFilterWhere(['like', 'employment_status', $this->employment_status])
            ->andFilterWhere(['like', 'professional_expertise', $this->professional_expertise])
            ->andFilterWhere(['like', 'address', $this->address])
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
