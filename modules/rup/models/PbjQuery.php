<?php

namespace app\modules\rup\models;

/**
 * This is the ActiveQuery class for [[Pbj]].
 *
 * @see Pbj
 */
class PbjQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Pbj[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Pbj|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
