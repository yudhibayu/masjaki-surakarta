<?php

namespace app\modules\rup\models;

/**
 * This is the ActiveQuery class for [[Rupjenispengadaan]].
 *
 * @see Rupjenispengadaan
 */
class RupjenispengadaanQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Rupjenispengadaan[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Rupjenispengadaan|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
