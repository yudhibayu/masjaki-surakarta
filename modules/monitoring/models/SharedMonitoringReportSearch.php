<?php

namespace app\modules\monitoring\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\monitoring\models\SharedMonitoringReport;
use yii\helpers\ArrayHelper;

/**
 * SharedMonitoringReportSearch represents the model behind the search form of `app\modules\monitoring\models\SharedMonitoringReport`.
 */
class SharedMonitoringReportSearch extends SharedMonitoringReport
{
    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), [
            'monitoringTask.name',
            'monitoringTask.id',
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'task_id', 'is_spec', 'created_by', 'updated_by'], 'integer'],
            [['type', 'month', 'week', 'day', 'expired_at', 'created_at', 'updated_at', 'monitoringTask.name', 'monitoringTask.id'], 'safe'],
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
    public function search($params, $year = null, $creator = null)
    {
        $query = SharedMonitoringReport::find()->alias('smr')->joinWith('monitoringTask mt');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        $this->load($params);

        $dataProvider->sort->attributes['monitoringTask.name'] = [
            'asc' => ['mt.name' => SORT_ASC],
            'desc' => ['mt.name' => SORT_DESC],
        ];

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($year) {
            $query->andWhere([
                'mt.fiscal_year' => $year,
            ]);
        }
        if ($creator) {
            $query->andWhere([
                'smr.created_by' => $creator,
            ]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'smr.id' => $this->id,
            'smr.task_id' => $this->task_id,
            'smr.is_spec' => $this->is_spec,
            'smr.expired_at' => $this->expired_at,
            'smr.created_at' => $this->created_at,
            'smr.updated_at' => $this->updated_at,
            'smr.created_by' => $this->created_by,
            'smr.updated_by' => $this->updated_by,
            'mt.id' => $this->getAttribute('monitoringTask.id'),
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'month', $this->month])
            ->andFilterWhere(['like', 'week', $this->week])
            ->andFilterWhere(['like', 'day', $this->day]);

        $query->andFilterWhere(['like', 'mt.name', $this->getAttribute('monitoringTask.name')]);

        return $dataProvider;
    }
}
