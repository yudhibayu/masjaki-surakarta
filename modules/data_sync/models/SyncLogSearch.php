<?php

namespace app\modules\data_sync\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\data_sync\models\SyncLog;

/**
 * SyncLogSearch represents the model behind the search form of `app\modules\sync_rup_penyedia\models\SyncLog`.
 */
class SyncLogSearch extends SyncLog
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'record_total', 'queue_total', 'queue_finished'], 'integer'],
            [['name', 'created_at', 'updated_at'], 'safe'],
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
    public function search($params)
    {
        $query = SyncLog::find();
                // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['updated_at' => SORT_DESC]],
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
            'record_total' => $this->record_total,
            'queue_total' => $this->queue_total,
            'queue_finished' => $this->queue_finished,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
