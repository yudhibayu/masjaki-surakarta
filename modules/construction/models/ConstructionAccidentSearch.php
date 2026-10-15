<?php

namespace app\modules\construction\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\construction\models\ConstructionAccident;

/**
 * ConstructionAccidentSearch represents the model behind the search form of `app\modules\construction\models\ConstructionAccident`.
 */
class ConstructionAccidentSearch extends ConstructionAccident
{
    public $pbj_name;
    public $file_accident;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_construction_work', 'file_size_before', 'file_size_after', 'amount_victims_minor_injuries', 'amount_victims_moderate_injuries', 'amount_victims_serious_injuries', 'amount_victims_die','bpjs','total_pekerja','accident'], 'integer'],
            [['date', 'place', 'file_extension_before', 'file_name_before', 'file_path_before', 'file_extension_after', 'file_name_after', 'file_path_after', 'photo_before_repair', 'photo_after_repair', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by', 'job_executor', 'accident_category', 'description','no_bpjs', 'iuran_pekerja','pbj_name', 'file_accident'], 'safe'],
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
    public function search($params, $id_construction_work = null, $pagesize = null)
    {
        $query = ConstructionAccident::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        if ($id_construction_work) {
            $query->andWhere(['id_construction_work' => $id_construction_work]);
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
            'id_construction_work' => $this->id_construction_work,
            'accident' => $this->accident,
            'date' => $this->date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'file_size_before' => $this->file_size_before,
            'file_size_after' => $this->file_size_after,
            'amount_victims_minor_injuries' => $this->amount_victims_minor_injuries,
            'amount_victims_moderate_injuries' => $this->amount_victims_moderate_injuries,
            'amount_victims_serious_injuries' => $this->amount_victims_serious_injuries,
            'amount_victims_die' => $this->amount_victims_die,
            'bpjs' => $this->bpjs,
            'total_pekerja' => $this->total_pekerja,
        ]);

        $query->andFilterWhere(['like', 'place', $this->place])
            ->andFilterWhere(['like', 'job_executor', $this->job_executor])
            ->andFilterWhere(['like', 'accident_category', $this->accident_category])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'photo_before_repair', $this->photo_before_repair])
            ->andFilterWhere(['like', 'file_path_before', $this->file_path_before])
            ->andFilterWhere(['like', 'file_extension_before', $this->file_extension_before])
            ->andFilterWhere(['like', 'file_name_before', $this->file_name_before])
            ->andFilterWhere(['like', 'photo_after_repair', $this->photo_after_repair])
            ->andFilterWhere(['like', 'file_path_after', $this->file_path_after])
            ->andFilterWhere(['like', 'file_extension_after', $this->file_extension_after])
            ->andFilterWhere(['like', 'file_name_after', $this->file_name_after])
            ->andFilterWhere(['like', 'no_bpjs', $this->no_bpjs])
            ->andFilterWhere(['like', 'iuran_pekerja', $this->iuran_pekerja])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchReport($params)
    {
        $query = ConstructionAccident::find()->joinWith('constructionWork');

        // add conditions that should always apply here
 
        if (isset(Yii::$app->session['year']['name']) || isset(Yii::$app->session['tahun'])) {
            $year = isset(Yii::$app->session['year']['name']) ? Yii::$app->session['year']['name'] : Yii::$app->session['tahun'];
            $query->where(['like', 'pbj.fiscal_year', $year]);
        }

        if (isset(Yii::$app->session['badan_usaha']['id_business_entity'])) {
            $id_business_entity = Yii::$app->session['badan_usaha']['id_business_entity'];
            $getkodelelang = PbjBusinessEntity::find()->select('kode_pbj')->where(['id_business_entity' => $id_business_entity])->column();
            $query->andWhere(['in', 'id_construction_work', $getkodelelang]);
        }

        $query->orderBy('pbj.name');
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'construction_accident.id' => $this->id,
            'id_construction_work' => $this->id_construction_work,
            'construction_accident.date' => $this->date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'file_size_before' => $this->file_size_before,
            'file_size_after' => $this->file_size_after,
            'amount_victims_minor_injuries' => $this->amount_victims_minor_injuries,
            'amount_victims_moderate_injuries' => $this->amount_victims_moderate_injuries,
            'amount_victims_serious_injuries' => $this->amount_victims_serious_injuries,
            'amount_victims_die' => $this->amount_victims_die,
            'bpjs' => $this->bpjs,
            'total_pekerja' => $this->total_pekerja,
        ]);

        $query->andFilterWhere(['like', 'place', $this->place])
            ->andFilterWhere(['like', 'pbj.name', $this->pbj_name])
            ->andFilterWhere(['like', 'job_executor', $this->job_executor])
            ->andFilterWhere(['like', 'accident_category', $this->accident_category])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'photo_before_repair', $this->photo_before_repair])
            ->andFilterWhere(['like', 'file_path_before', $this->file_path_before])
            ->andFilterWhere(['like', 'file_extension_before', $this->file_extension_before])
            ->andFilterWhere(['like', 'file_name_before', $this->file_name_before])
            ->andFilterWhere(['like', 'photo_after_repair', $this->photo_after_repair])
            ->andFilterWhere(['like', 'file_path_after', $this->file_path_after])
            ->andFilterWhere(['like', 'file_extension_after', $this->file_extension_after])
            ->andFilterWhere(['like', 'file_name_after', $this->file_name_after])
            ->andFilterWhere(['like', 'no_bpjs', $this->no_bpjs])
            ->andFilterWhere(['like', 'iuran_pekerja', $this->iuran_pekerja])
            ->andFilterWhere(['like', 'file_accident', $this->file_accident])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by]);

        return $dataProvider;
    }
}
