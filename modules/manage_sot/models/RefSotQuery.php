<?php

namespace app\modules\manage_sot\models;

/**
 * This is the ActiveQuery class for [[RefSot]].
 *
 * @see RefSot
 */
class RefSotQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return RefSot[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return RefSot|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
