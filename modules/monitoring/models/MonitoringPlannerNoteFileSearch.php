<?php

namespace app\modules\monitoring\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\monitoring\models\MonitoringPlannerNoteFile;

/**
 * MonitoringPlannerNoteFileSearch represents the model behind the search form of `app\modules\monitoring\models\MonitoringPlannerNoteFile`.
 */
class MonitoringPlannerNoteFileSearch extends MonitoringPlannerNoteFile
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'monitoring_planner_note_id', 'file_size', 'created_by', 'updated_by'], 'integer'],
            [['name', 'monitoring_planner_note_name', 'description', 'file_name', 'file_path', 'file_extension', 'created_at', 'updated_at'], 'safe'],
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
    public function search($params, $pnid = null, $use_pagination = true)
    {
        $query = MonitoringPlannerNoteFile::find();
        // add conditions that should always apply here
        if ($pnid) {
            $query->andWhere([
                'monitoring_planner_note_id' => $pnid,
            ]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$use_pagination) {
            $dataProvider->pagination = false;
            $dataProvider->sort = false;
            $query->orderBy(['name' => SORT_ASC]);
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
            'monitoring_planner_note_id' => $this->monitoring_planner_note_id,
            'file_size' => $this->file_size,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'monitoring_planner_note_name', $this->monitoring_planner_note_name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'file_name', $this->file_name])
            ->andFilterWhere(['like', 'file_path', $this->file_path])
            ->andFilterWhere(['like', 'file_extension', $this->file_extension]);

        return $dataProvider;
    }
}
