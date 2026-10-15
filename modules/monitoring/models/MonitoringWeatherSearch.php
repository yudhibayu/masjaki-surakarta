<?php

namespace app\modules\monitoring\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\monitoring\models\MonitoringWeather;
use yii\helpers\ArrayHelper;

/**
 * MonitoringWeatherSearch represents the model behind the search form of `app\modules\monitoring\models\MonitoringWeather`.
 */
class MonitoringWeatherSearch extends MonitoringWeather
{

    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), [
            'monitoringProgress.name',
            'refCuaca.name',
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ref_cuaca_id', 'monitoring_progress_id', 'created_by', 'updated_by'], 'integer'],
            [['name', 'note', 'time_from', 'time_to', 'created_at', 'updated_at'], 'safe'],
            [['monitoringProgress.name', 'refCuaca.name'], 'safe'],
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
    public function search($params, $monitoring_progress_id = null, $use_pagination = true)
    {
        $query = MonitoringWeather::find()
            ->alias('mw')
            ->joinWith(['monitoringProgress mp', 'refCuaca rc']);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if ($monitoring_progress_id) {
            $query->andWhere([
                'monitoring_progress_id' => $monitoring_progress_id,
            ]);
        }

        if (!$use_pagination) {
            $dataProvider->pagination = false;
            $dataProvider->sort = false;
            $query->orderBy(['time_from' => SORT_ASC]);
        }

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($use_pagination) {
            $dataProvider->sort->attributes['monitoringProgress.name'] = [
                'asc' => ['mp.name' => SORT_ASC],
                'desc' => ['mp.name' => SORT_DESC],
            ];
            $dataProvider->sort->attributes['refCuaca.name'] = [
                'asc' => ['rc.name' => SORT_ASC],
                'desc' => ['rc.name' => SORT_DESC],
            ];
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'mw.id' => $this->id,
            'mw.ref_cuaca_id' => $this->ref_cuaca_id,
            'mw.monitoring_progress_id' => $this->monitoring_progress_id,
            'mw.time_from' => $this->time_from,
            'mw.time_to' => $this->time_to,
            'mw.created_at' => $this->created_at,
            'mw.updated_at' => $this->updated_at,
            'mw.created_by' => $this->created_by,
            'mw.updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'mw.name', $this->name])
            ->andFilterWhere(['like', 'mw.note', $this->note]);

        $query->andFilterWhere(['like', 'mp.name', $this->getAttribute('monitoringProgress.name')]);
        $query->andFilterWhere(['like', 'rc.name', $this->getAttribute('refCuaca.name')]);

        return $dataProvider;
    }
}
