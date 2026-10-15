<?php

namespace app\modules\api\v1\models;

/**
 * This is the ActiveQuery class for [[ApiAccessToken]].
 *
 * @see ApiAccessToken
 */
class ApiAccessTokenQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ApiAccessToken[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ApiAccessToken|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
