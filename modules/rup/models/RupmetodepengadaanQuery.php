<?php

namespace app\modules\rup\models;

/**
 * This is the ActiveQuery class for [[Rupmetodepengadaan]].
 *
 * @see Rupmetodepengadaan
 */
class RupmetodepengadaanQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Rupmetodepengadaan[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Rupmetodepengadaan|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
