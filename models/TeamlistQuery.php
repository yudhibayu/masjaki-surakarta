<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Teamlist]].
 *
 * @see Teamlist
 */
class TeamlistQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Teamlist[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Teamlist|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
