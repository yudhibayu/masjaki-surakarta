<?php

namespace app\modules\rup\models;

use Yii;
use yii\base\Model;
use app\modules\rup\models\Pbj;
use yii\data\ActiveDataProvider;

/**
 * PbjSearch represents the model behind the search form of `app\modules\rup\models\Pbj`.
 */
class PbjSearch extends Pbj
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kodelelang', 'pbj_type', 'rup_code', 'idmetodepengadaan', 'idjenispengadaan', 'ppk_id', 'fiscal_year', 'created_by', 'updated_by', 'source_of_funds'], 'integer'],
            [['name', 'vendor_name', 'sppbj_no', 'contract_no', 'contract_date', 'contract_duration', 'contract_start_date', 'contract_end_date', 'work_start_date', 'work_finished_date', 'program', 'activity', 'sub_activity', 'namasatker', 'kodesatker', 'metodepengadaan', 'jenispengadaan', 'ppk_name', 'ppk_nip', 'created_at', 'updated_at', 'step'], 'safe'],
            [['budget', 'hps', 'contract_price', 'bid_price', 'final_price'], 'number'],
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
    public function search($params, $type_pbj = null)
    {
        $query = Pbj::find();
        // add conditions that should always apply here
        if (isset(Yii::$app->session['year']['name']) || isset(Yii::$app->session['tahun'])) {
            $year = isset(Yii::$app->session['year']['name']) ? Yii::$app->session['year']['name'] : Yii::$app->session['tahun'];
            $query->andWhere(['like', 'fiscal_year', $year]);
        }

        if (isset(Yii::$app->session['badan_usaha']['id_business_entity'])) {
            $id_business_entity = Yii::$app->session['badan_usaha']['id_business_entity'];
            $query->joinWith('pbjBusinessEntity')
                ->andWhere(['pbj_business_entity.id_business_entity' => $id_business_entity]);
        }

        if ($type_pbj !== null) {
            $query->andFilterWhere([
                'pbj_type' => $type_pbj,
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
            'kodelelang' => $this->kodelelang,
            'pbj_type' => $this->pbj_type,
            'rup_code' => $this->rup_code,
            'budget' => $this->budget,
            'hps' => $this->hps,
            'bid_price' => $this->bid_price,
            'final_price' => $this->final_price,
            'contract_date' => $this->contract_date,
            'contract_price' => $this->contract_price,
            'contract_start_date' => $this->contract_start_date,
            'contract_end_date' => $this->contract_end_date,
            'work_start_date' => $this->work_start_date,
            'work_finished_date' => $this->work_finished_date,
            'idmetodepengadaan' => $this->idmetodepengadaan,
            'idjenispengadaan' => $this->idjenispengadaan,
            'ppk_id' => $this->ppk_id,
            'fiscal_year' => $this->fiscal_year,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'vendor_name', $this->vendor_name])
            ->andFilterWhere(['like', 'step', $this->step])
            ->andFilterWhere(['like', 'source_of_funds', $this->source_of_funds])
            ->andFilterWhere(['like', 'sppbj_no', $this->sppbj_no])
            ->andFilterWhere(['like', 'contract_no', $this->contract_no])
            ->andFilterWhere(['like', 'contract_duration', $this->contract_duration])
            ->andFilterWhere(['like', 'program', $this->program])
            ->andFilterWhere(['like', 'activity', $this->activity])
            ->andFilterWhere(['like', 'sub_activity', $this->sub_activity])
            ->andFilterWhere(['like', 'namasatker', $this->namasatker])
            ->andFilterWhere(['like', 'kodesatker', $this->kodesatker])
            ->andFilterWhere(['like', 'metodepengadaan', $this->metodepengadaan])
            ->andFilterWhere(['like', 'jenispengadaan', $this->jenispengadaan])
            ->andFilterWhere(['like', 'ppk_name', $this->ppk_name])
            ->andFilterWhere(['like', 'ppk_nip', $this->ppk_nip]);

        return $dataProvider;
    }
}
