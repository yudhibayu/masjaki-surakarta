<?php

namespace app\modules\technicalspec\models;

/**
 * This is the ActiveQuery class for [[RefComponent]].
 *
 * @see RefComponent
 */
class RefComponentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return RefComponent[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return RefComponent|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
