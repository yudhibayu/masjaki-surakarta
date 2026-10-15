<?php

namespace app\modules\references\models;

/**
 * This is the ActiveQuery class for [[ObjectType]].
 *
 * @see ObjectType
 */
class ObjectTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ObjectType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ObjectType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
