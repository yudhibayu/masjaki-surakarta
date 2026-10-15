<?php

namespace app\modules\construction\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\construction\models\WorkTraining;

/**
 * WorkTrainingSearch represents the model behind the search form of `app\modules\construction\models\WorkTraining`.
 */
class WorkTrainingSearch extends WorkTraining
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'skills', 'kuota', 'id_year'], 'integer'],
            [['training_name', 'person_responsible_name', 'implementation_date', 'finish_date', 'finish_time', 'implementation_time', 'description', 'id_country', 'id_province', 'id_regency', 'id_subdistrict', 'id_ward', 'country', 'province', 'regency',  'subdistrict', 'publication', 'ward', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by', 'year_name'], 'safe'],
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
    public function search($params, $publication = null, $filterRoleLabor = null)
    {
        $query = WorkTraining::find();
        // add conditions that should always apply here

        if (isset(Yii::$app->session['year']['name']) || isset(Yii::$app->session['tahun'])) {
            $year = isset(Yii::$app->session['year']['name']) ? Yii::$app->session['year']['name'] : Yii::$app->session['tahun'];
            $query->andWhere(['like', 'year_name', $year]);
        }

        if ($filterRoleLabor !== null) {
            $query->andFilterWhere(['not in', 'publication', $filterRoleLabor]);
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
            'skills' => $this->skills,
            'implementation_date' => $this->implementation_date,
            'kuota' => $this->kuota,
            'finish_date' => $this->finish_date,
            'implementation_time' => $this->implementation_time,
            'id_year' => $this->id_year,
            'year_name' => $this->year_name,
            'finish_time' => $this->finish_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'training_name', $this->training_name])
            ->andFilterWhere(['like', 'person_responsible_name', $this->person_responsible_name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'id_country', $this->id_country])
            ->andFilterWhere(['like', 'id_province', $this->id_province])
            ->andFilterWhere(['like', 'id_regency', $this->id_regency])
            ->andFilterWhere(['like', 'id_subdistrict', $this->id_subdistrict])
            ->andFilterWhere(['like', 'id_ward', $this->id_ward])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'regency', $this->regency])
            ->andFilterWhere(['like', 'subdistrict', $this->subdistrict])
            ->andFilterWhere(['like', 'publication', $this->publication])
            ->andFilterWhere(['like', 'ward', $this->ward])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by]);

        return $dataProvider;
    }
}