<?php

namespace app\modules\monitoring\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\monitoring\models\MonitoringTaskPictures;

/**
 * MonitoringProgressPicturesSearch represents the model behind the search form of `app\modules\monitoring\modules\pictures\models\MonitoringTaskPictures`.
 */
class MonitoringProgressPicturesSearch extends MonitoringProgressPictures
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'monitoring_task_id', 'monitoring_progress_id', 'monitoring_task_locations_id', 'created_by', 'updated_by'], 'integer'],
            [['name', 'monitoring_task_name', 'monitoring_progress_name', 'monitoring_task_locations_name', 'file_name', 'file_url', 'description', 'created_at', 'updated_at', 'file_thumbnail'], 'safe'],
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
    public function search($params, $progress_id = null, $pagesize = null, $task_id = null, $use_pagination = true)
    {
        $query = MonitoringProgressPictures::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if ($progress_id) {
            $query->andWhere(['monitoring_progress_id' => $progress_id]);
            // $dataProvider->sort->defaultOrder = ['order_no' => SORT_ASC];
        }
        if (!$use_pagination) {
            $dataProvider->pagination = false;
            $dataProvider->sort = false;
            $query->orderBy(['id' => SORT_ASC]);
        } else {
            if ($pagesize) {
                $dataProvider->pagination->pageSize = $pagesize;
            }
        }
        if ($task_id) {
            $query->andWhere(['monitoring_task_id' => $task_id]);
            // $dataProvider->sort->defaultOrder = ['order_no' => SORT_ASC];
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
            'monitoring_task_id' => $this->monitoring_task_id,
            'monitoring_task_locations_id' => $this->monitoring_task_locations_id,
            'monitoring_progress_id' => $this->monitoring_progress_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'monitoring_task_name', $this->monitoring_task_name])
            ->andFilterWhere(['like', 'monitoring_task_locations_name', $this->monitoring_task_locations_name])
            ->andFilterWhere(['like', 'monitoring_progress_name', $this->monitoring_progress_name])
            ->andFilterWhere(['like', 'file_name', $this->file_name])
            ->andFilterWhere(['like', 'file_url', $this->file_url])
            ->andFilterWhere(['like', 'file_thumbnail', $this->file_thumbnail])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
