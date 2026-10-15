<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\overrides\user\models;

use dektrium\user\Finder;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
//use dektrium\user\models\UserSearch as ubah;
use app\overrides\user\models\User as OverrideUser;
/**
 * UserSearch represents the model behind the search form about User.
 */
class UserSearch extends OverrideUser
{

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = $this->finder->getUserQuery();
        
        $modelClass = $query->modelClass;
        $table_name = $modelClass::tableName();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        if (!Yii::$app->user->can('Super Administrator')){
            $query->andFilterWhere(['not in', $table_name . '.id', 1]);
        }

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }



        if ($this->created_at !== null) {
            $date = strtotime($this->created_at);
            $query->andFilterWhere(['between', $table_name . '.created_at', $date, $date + 3600 * 24]);
        }

        $query->andFilterWhere(['like', $table_name . '.username', $this->username])
              ->andFilterWhere(['like', $table_name . '.email', $this->email])
              ->andFilterWhere([$table_name . '.id' => $this->id])
              ->andFilterWhere([$table_name . 'registration_ip' => $this->registration_ip]);

        return $dataProvider;
    }
}
