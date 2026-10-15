<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserSatker]].
 *
 * @see UserSatker
 */
class UserSatkerQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return UserSatker[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return UserSatker|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
