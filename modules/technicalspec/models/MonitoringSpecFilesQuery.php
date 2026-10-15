<?php

namespace app\modules\technicalspec\models;

/**
 * This is the ActiveQuery class for [[MonitoringSpecFiles]].
 *
 * @see MonitoringSpecFiles
 */
class MonitoringSpecFilesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return MonitoringSpecFiles[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return MonitoringSpecFiles|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
