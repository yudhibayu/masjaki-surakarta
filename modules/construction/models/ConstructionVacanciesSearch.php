<?php

namespace app\modules\construction\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\construction\models\ConstructionVacancies;

/**
 * ConstructionVacanciesSearch represents the model behind the search form of `app\modules\construction\models\ConstructionVacancies`.
 */
class ConstructionVacanciesSearch extends ConstructionVacancies
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'construction_work', 'skills', 'education', 'age_range', 'age_range_upto', 'work_experience', 'quota', 'salary_range', 'salary_range_upto', 'created_by', 'updated_by', 'deleted_by', 'id_year'], 'integer'],
            [['name', 'description', 'start_date', 'date_of_completion', 'type_of_work', 'publication', 'created_at', 'updated_at', 'deleted_at', 'year_name', 'nama_business_entity'], 'safe'],
            [['salary_range', 'salary_range_upto',], 'number']
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
    public function search($params, $publication = null, $filterRoleLabor = null, $id_business_entity = null)
    {
        $query = ConstructionVacancies::find();
        // add conditions that should always apply here

        if (isset(Yii::$app->session['year']['name']) || isset(Yii::$app->session['tahun'])) {
            $year = isset(Yii::$app->session['year']['name']) ? Yii::$app->session['year']['name'] : Yii::$app->session['tahun'];
            $query->andWhere(['like', 'year_name', $year]);
        }
        if ($filterRoleLabor !== null) {
            $query->andFilterWhere(['not in', 'publication', $filterRoleLabor]);
        }
        if ($id_business_entity !== null) {
            $query->andFilterWhere([
                'id_business_entity' => $id_business_entity,
            ]);
        }
        if ($publication !== null) {
            $query->andFilterWhere([
                'publication' => $publication,
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
            'construction_work' => $this->construction_work,
            'skills' => $this->skills,
            'education' => $this->education,
            'age_range' => $this->age_range,
            'age_range_upto' => $this->age_range_upto,
            'work_experience' => $this->work_experience,
            'quota' => $this->quota,
            'salary_range' => $this->salary_range,
            'salary_range_upto' => $this->salary_range_upto,
            'start_date' => $this->start_date,
            'id_year' => $this->id_year,
            'year_name' => $this->year_name,
            'date_of_completion' => $this->date_of_completion,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'type_of_work', $this->type_of_work])
            ->andFilterWhere(['like', 'nama_business_entity', $this->nama_business_entity])
            ->andFilterWhere(['like', 'publication', $this->publication]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchDashboard($params, $publication = null, $filterRoleLabor = null, $id_business_entity = null)
    {
        $query = ConstructionVacancies::find();
        // add conditions that should always apply here

        if (isset(Yii::$app->session['year']['name']) || isset(Yii::$app->session['tahun'])) {
            $year = isset(Yii::$app->session['year']['name']) ? Yii::$app->session['year']['name'] : Yii::$app->session['tahun'];
            $query->andWhere(['like', 'year_name', $year]);
        }
        if ($filterRoleLabor !== null) {
            $query->andWhere(['not in', 'publication', $filterRoleLabor]);
        }
        if ($id_business_entity !== null) {
            $query->andWhere([
                'id_business_entity' => $id_business_entity,
            ]);
        }

        $query->andWhere(['publication' => 1]);
        
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
            'construction_work' => $this->construction_work,
            'skills' => $this->skills,
            'education' => $this->education,
            'age_range' => $this->age_range,
            'age_range_upto' => $this->age_range_upto,
            'work_experience' => $this->work_experience,
            'quota' => $this->quota,
            'salary_range' => $this->salary_range,
            'salary_range_upto' => $this->salary_range_upto,
            'start_date' => $this->start_date,
            'id_year' => $this->id_year,
            'year_name' => $this->year_name,
            'date_of_completion' => $this->date_of_completion,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'type_of_work', $this->type_of_work])
            ->andFilterWhere(['like', 'nama_business_entity', $this->nama_business_entity])
            ->andFilterWhere(['like', 'publication', $this->publication]);

        return $dataProvider;
    }
}
