<?php

namespace app\modules\technicalspec\models;

use app\modules\monitoring\models\MonitoringTask;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\technicalspec\models\DedData;
use Yii;

/**
 * DedDataSearch represents the model behind the search form of `app\modules\technicalspec\models\DedData`.
 */
class DedDataSearch extends DedData
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'monitoring_task_id', 'ref_component_category_id', 'ref_component_id', 'cco', 'created_by', 'updated_by', 'mco_cco_id',], 'integer'],
            [['name', 'monitoring_task_name', 'ref_component_category_name', 'ref_component_name', 'specification', 'quantity', 'notes', 'created_at', 'updated_at', 'mco_cco_name',], 'safe'],
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
    public function search($params, $monitoring_task_id = null, $mco_cco_id = null)
    {
        $query = DedData::find();
        // add conditions that should always apply here
        $dedDataTb = DedData::tableName();
        $mTaskTb = MonitoringTask::tableName();

        $query->joinWith('monitoringTask');

        if (isset(Yii::$app->session['year']['name']) || isset(Yii::$app->session['tahun'])) {
            $year = isset(Yii::$app->session['year']['name']) ? Yii::$app->session['year']['name'] : Yii::$app->session['tahun'];
            $query->andWhere(['like', $mTaskTb . '.fiscal_year', $year]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($monitoring_task_id) {
            $query->andWhere([
                $dedDataTb . '.monitoring_task_id' => $monitoring_task_id,
            ]);
        }

        if ($mco_cco_id) {
            $query->andWhere([
                $dedDataTb . '.mco_cco_id' => $mco_cco_id,
            ]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            $dedDataTb . '.id' => $this->id,
            $dedDataTb . '.monitoring_task_id' => $this->monitoring_task_id,
            $dedDataTb . '.ref_component_category_id' => $this->ref_component_category_id,
            $dedDataTb . '.ref_component_id' => $this->ref_component_id,
            $dedDataTb . '.mco_cco_id' => $this->mco_cco_id,
            $dedDataTb . '.cco' => $this->cco,
            $dedDataTb . '.created_at' => $this->created_at,
            $dedDataTb . '.created_by' => $this->created_by,
            $dedDataTb . '.updated_at' => $this->updated_at,
            $dedDataTb . '.updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', $dedDataTb . '.name', $this->name])
            ->andFilterWhere(['like', $dedDataTb . '.mco_cco_name', $this->mco_cco_name])
            ->andFilterWhere(['like', $dedDataTb . '.monitoring_task_name', $this->monitoring_task_name])
            ->andFilterWhere(['like', $dedDataTb . '.ref_component_category_name', $this->ref_component_category_name])
            ->andFilterWhere(['like', $dedDataTb . '.ref_component_name', $this->ref_component_name])
            ->andFilterWhere(['like', $dedDataTb . '.specification', $this->specification])
            ->andFilterWhere(['like', $dedDataTb . '.quantity', $this->quantity])
            ->andFilterWhere(['like', $dedDataTb . '.notes', $this->notes]);

        return $dataProvider;
    }
}
