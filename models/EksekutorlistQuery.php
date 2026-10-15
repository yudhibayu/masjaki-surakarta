<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Eksekutorlist]].
 *
 * @see Eksekutorlist
 */
class EksekutorlistQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Eksekutorlist[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Eksekutorlist|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
