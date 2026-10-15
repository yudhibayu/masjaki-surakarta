<?php

namespace app\modules\businessEntity\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\businessEntity\models\PhotoBusinessPermit;

/**
 * PhotoBusinessPermitSearch represents the model behind the search form of `app\modules\businessEntity\models\PhotoBusinessPermit`.
 */
class PhotoBusinessPermitSearch extends PhotoBusinessPermit
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'file_size', 'id_business_permit'], 'integer'],
            [['photo', 'file_extension', 'file_name', 'file_path', 'description', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'], 'safe'],
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
    public function search($params, $id_business_permit = null, $pagesize = null)
    {
        $query = PhotoBusinessPermit::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        if ($id_business_permit) {
            $query->andWhere(['id_business_permit' => $id_business_permit]);
        }
        if ($pagesize) {
            $dataProvider->pagination->pageSize = $pagesize;
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
            'id_business_permit' => $this->id_business_permit,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'file_size' => $this->file_size,
        ]);

        $query->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'file_path', $this->file_path])
            ->andFilterWhere(['like', 'file_extension', $this->file_extension])
            ->andFilterWhere(['like', 'file_name', $this->file_name])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by]);

        return $dataProvider;
    }
}
