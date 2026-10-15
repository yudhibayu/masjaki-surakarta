<?php

namespace app\modules\technicalspec\models;

/**
 * This is the ActiveQuery class for [[MonitoringSpec]].
 *
 * @see MonitoringSpec
 */
class MonitoringSpecQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return MonitoringSpec[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return MonitoringSpec|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
