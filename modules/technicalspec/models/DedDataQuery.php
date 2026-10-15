<?php

namespace app\modules\technicalspec\models;

/**
 * This is the ActiveQuery class for [[DedData]].
 *
 * @see DedData
 */
class DedDataQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return DedData[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return DedData|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
