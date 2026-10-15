<?php

namespace app\modules\technicalspec\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\technicalspec\models\MonitoringSpecFiles;

/**
 * MonitoringSpecFilesSearch represents the model behind the search form of `app\modules\technicalspec\models\MonitoringSpecFiles`.
 */
class MonitoringSpecFilesSearch extends MonitoringSpecFiles
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'monitoring_spec_id', 'file_size', 'created_by', 'updated_by'], 'integer'],
            [['name', 'description', 'file_name', 'file_path', 'file_extension', 'created_at', 'updated_at'], 'safe'],
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
    public function search($params, $monitoring_spec_id = null)
    {
        $query = MonitoringSpecFiles::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($monitoring_spec_id) {
            $query->andWhere([
                'monitoring_spec_id' => $monitoring_spec_id,
            ]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'monitoring_spec_id' => $this->monitoring_spec_id,
            'file_size' => $this->file_size,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'file_name', $this->file_name])
            ->andFilterWhere(['like', 'file_path', $this->file_path])
            ->andFilterWhere(['like', 'file_extension', $this->file_extension]);

        return $dataProvider;
    }
}
