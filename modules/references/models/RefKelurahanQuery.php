<?php

namespace app\modules\references\models;

/**
 * This is the ActiveQuery class for [[RefKelurahan]].
 *
 * @see RefKelurahan
 */
class RefKelurahanQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return RefKelurahan[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return RefKelurahan|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
