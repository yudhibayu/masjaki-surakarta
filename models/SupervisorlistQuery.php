<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Supervisorlist]].
 *
 * @see Supervisorlist
 */
class SupervisorlistQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Supervisorlist[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Supervisorlist|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
