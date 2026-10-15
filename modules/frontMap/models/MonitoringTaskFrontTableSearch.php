<?php

namespace app\modules\frontMap\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\monitoring\models\MonitoringTask;
use Yii;

/**
 * MonitoringTaskSearch represents the model behind the search form of `app\modules\monitoring\models\MonitoringTask`.
 */
class MonitoringTaskFrontTableSearch extends MonitoringTask
{
    public $formNameParam = "MonitoringTaskSearch";
    public $typeobject = '';
    public $typeobject_id = '';
    public $kecamatan = '';
    public $kecamatan_id = '';
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'unit_id', 'fiscal_year', 'created_by', 'updated_by', 'idrup', 'pbj_id',], 'integer'],
            [['name', 'unit_name', 'start_date', 'end_date', 'created_at', 'updated_at', 'typeobject', 'typeobject_id', 'kecamatan', 'kecamatan_id'], 'safe'],
            [['budget', 'latest_plan_financial_budget', 'latest_plan_physical_percent', 'latest_monitored_financial_budget', 'latest_monitored_physical_percent', 'latest_monitored_financial_budget_percent'], 'number'],
            [['implementation_status',], 'safe'],
        ];
    }

    /**
     * @return string — the form name of this model class.
     */
    public function formName()
    {
        return $this->formNameParam;
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
    public function search($params)
    {
        $query = MonitoringTask::find()->alias('mt');
        // add conditions that should always apply here
        $query->joinWith(['objectType ot', 'kecamatan k']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['latest_monitored_financial_budget_percent'] = [
            'asc' => ['IF((latest_monitored_financial_budget > 0),(latest_monitored_financial_budget / final_price * 100),0)' => SORT_ASC],
            'desc' => ['IF((latest_monitored_financial_budget > 0),(latest_monitored_financial_budget / final_price * 100),0)' => SORT_DESC],
        ];

        $dataProvider->sort->defaultOrder = ['fiscal_year' => SORT_DESC, 'id' => SORT_ASC];

        $dataProvider->sort->attributes['typeobject'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['ot.name' => SORT_ASC],
            'desc' => ['ot.name' => SORT_DESC],
        ];
        // Lets do the same with country now
        $dataProvider->sort->attributes['kecamatan'] = [
            'asc' => ['k.name' => SORT_ASC],
            'desc' => ['k.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'mt.id' => $this->id,
            'mt.budget' => $this->budget,
            'mt.idrup' => $this->idrup,
            'mt.pbj_id' => $this->pbj_id,
            'mt.unit_id' => $this->unit_id,
            'mt.start_date' => $this->start_date,
            'mt.end_date' => $this->end_date,
            'mt.fiscal_year' => $this->fiscal_year,
            'mt.latest_plan_financial_budget' => $this->latest_plan_financial_budget,
            'mt.latest_plan_physical_percent' => $this->latest_plan_physical_percent,
            'mt.latest_monitored_financial_budget' => $this->latest_monitored_financial_budget,
            'mt.latest_monitored_physical_percent' => $this->latest_monitored_physical_percent,
            'mt.implementation_status' => $this->implementation_status,
            'mt.created_at' => $this->created_at,
            'mt.updated_at' => $this->updated_at,
            'mt.created_by' => $this->created_by,
            'mt.updated_by' => $this->updated_by,
            'k.id' => $this->kecamatan_id,
            'ot.id' => $this->typeobject_id,
        ]);

        $query->andFilterWhere(['like', 'mt.name', $this->name])
            ->andFilterWhere(['like', 'ot.name', $this->typeobject])
            ->andFilterWhere(['like', 'k.name', $this->kecamatan])
            ->andFilterWhere(['like', 'mt.unit_name', $this->unit_name]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchPaket($params, $monitoring_task_id = null)
    {
        $query = MonitoringTask::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if ($monitoring_task_id) {
            $query->andWhere(['in', 'id', $monitoring_task_id]);
        } else {
            $query->andWhere(['id' => null]);
        }

        if (isset(Yii::$app->session['year']['name']) || isset(Yii::$app->session['tahun'])) {
            $year = isset(Yii::$app->session['year']['name']) ? Yii::$app->session['year']['name'] : Yii::$app->session['tahun'];
            $query->andWhere([
                'fiscal_year' => $year,
            ]);
        }

        if (isset(Yii::$app->session['satker']['satker_id'])) {
            $query->andWhere([
                'unit_id' => Yii::$app->session['satker']['satker_id'],
            ]);
        }
        if (isset(Yii::$app->session['team']['team_id'])) {
            $query->andWhere([
                'team_id' => Yii::$app->session['team']['team_id'],
            ]);
        }

        $dataProvider->sort->defaultOrder = ['id' => SORT_ASC];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'budget' => $this->budget,
            'idrup' => $this->idrup,
            'pbj_id' => $this->pbj_id,
            'unit_id' => $this->unit_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'fiscal_year' => $this->fiscal_year,
            'latest_plan_financial_budget' => $this->latest_plan_financial_budget,
            'latest_plan_physical_percent' => $this->latest_plan_physical_percent,
            'latest_monitored_financial_budget' => $this->latest_monitored_financial_budget,
            'latest_monitored_physical_percent' => $this->latest_monitored_physical_percent,
            'implementation_status' => $this->implementation_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'unit_name', $this->unit_name]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchPaketTr($params, $monitoring_task_id = null)
    {
        $query = MonitoringTask::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if ($monitoring_task_id) {
            $query->andWhere(['in', 'id', $monitoring_task_id]);
        } else {
            $query->andWhere(['id' => null]);
        }

        if (isset(Yii::$app->session['year']['name']) || isset(Yii::$app->session['tahun'])) {
            $year = isset(Yii::$app->session['year']['name']) ? Yii::$app->session['year']['name'] : Yii::$app->session['tahun'];
            $query->andWhere([
                'fiscal_year' => $year,
            ]);
        }

        if (isset(Yii::$app->session['satker']['satker_id'])) {
            $query->andWhere([
                'unit_id' => Yii::$app->session['satker']['satker_id'],
            ]);
        }
        if (isset(Yii::$app->session['team']['team_id'])) {
            $query->andWhere([
                'team_id' => Yii::$app->session['team']['team_id'],
            ]);
        }

        $dataProvider->sort->defaultOrder = ['id' => SORT_ASC];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'budget' => $this->budget,
            'idrup' => $this->idrup,
            'pbj_id' => $this->pbj_id,
            'unit_id' => $this->unit_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'fiscal_year' => $this->fiscal_year,
            'latest_plan_financial_budget' => $this->latest_plan_financial_budget,
            'latest_plan_physical_percent' => $this->latest_plan_physical_percent,
            'latest_monitored_financial_budget' => $this->latest_monitored_financial_budget,
            'latest_monitored_physical_percent' => $this->latest_monitored_physical_percent,
            'implementation_status' => $this->implementation_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'unit_name', $this->unit_name]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchPaketS($params, $monitoring_task_id = null)
    {
        $query = MonitoringTask::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if ($monitoring_task_id) {
            $query->andWhere(['in', 'id', $monitoring_task_id]);
        } else {
            $query->andWhere(['id' => null]);
        }

        if (isset(Yii::$app->session['year']['name']) || isset(Yii::$app->session['tahun'])) {
            $year = isset(Yii::$app->session['year']['name']) ? Yii::$app->session['year']['name'] : Yii::$app->session['tahun'];
            $query->andWhere([
                'fiscal_year' => $year,
            ]);
        }

        if (isset(Yii::$app->session['satker']['satker_id'])) {
            $query->andWhere([
                'unit_id' => Yii::$app->session['satker']['satker_id'],
            ]);
        }
        if (isset(Yii::$app->session['team']['team_id'])) {
            $query->andWhere([
                'team_id' => Yii::$app->session['team']['team_id'],
            ]);
        }

        $dataProvider->sort->defaultOrder = ['id' => SORT_ASC];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'budget' => $this->budget,
            'idrup' => $this->idrup,
            'pbj_id' => $this->pbj_id,
            'unit_id' => $this->unit_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'fiscal_year' => $this->fiscal_year,
            'latest_plan_financial_budget' => $this->latest_plan_financial_budget,
            'latest_plan_physical_percent' => $this->latest_plan_physical_percent,
            'latest_monitored_financial_budget' => $this->latest_monitored_financial_budget,
            'latest_monitored_physical_percent' => $this->latest_monitored_physical_percent,
            'implementation_status' => $this->implementation_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'unit_name', $this->unit_name]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchPaketDp($params, $monitoring_task_id = null)
    {
        $query = MonitoringTask::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if ($monitoring_task_id) {
            $query->andWhere(['in', 'id', $monitoring_task_id]);
        } else {
            $query->andWhere(['id' => null]);
        }

        if (isset(Yii::$app->session['year']['name']) || isset(Yii::$app->session['tahun'])) {
            $year = isset(Yii::$app->session['year']['name']) ? Yii::$app->session['year']['name'] : Yii::$app->session['tahun'];
            $query->andWhere([
                'fiscal_year' => $year,
            ]);
        }

        if (isset(Yii::$app->session['satker']['satker_id'])) {
            $query->andWhere([
                'unit_id' => Yii::$app->session['satker']['satker_id'],
            ]);
        }
        if (isset(Yii::$app->session['team']['team_id'])) {
            $query->andWhere([
                'team_id' => Yii::$app->session['team']['team_id'],
            ]);
        }

        $dataProvider->sort->defaultOrder = ['id' => SORT_ASC];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'budget' => $this->budget,
            'idrup' => $this->idrup,
            'pbj_id' => $this->pbj_id,
            'unit_id' => $this->unit_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'fiscal_year' => $this->fiscal_year,
            'latest_plan_financial_budget' => $this->latest_plan_financial_budget,
            'latest_plan_physical_percent' => $this->latest_plan_physical_percent,
            'latest_monitored_financial_budget' => $this->latest_monitored_financial_budget,
            'latest_monitored_physical_percent' => $this->latest_monitored_physical_percent,
            'implementation_status' => $this->implementation_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'unit_name', $this->unit_name]);

        return $dataProvider;
    }
}
