<?php

namespace app\modules\references\models;

/**
 * This is the ActiveQuery class for [[RefSatuan]].
 *
 * @see RefSatuan
 */
class RefSatuanQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return RefSatuan[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return RefSatuan|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
