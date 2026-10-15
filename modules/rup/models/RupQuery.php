<?php

namespace app\modules\rup\models;

/**
 * This is the ActiveQuery class for [[Rup]].
 *
 * @see Rup
 */
class RupQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Rup[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Rup|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
