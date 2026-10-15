<?php

namespace app\modules\references\models;

/**
 * This is the ActiveQuery class for [[RefCuaca]].
 *
 * @see RefCuaca
 */
class RefCuacaQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return RefCuaca[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return RefCuaca|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
