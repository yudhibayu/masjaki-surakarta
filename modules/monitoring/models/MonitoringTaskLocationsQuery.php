<?php

namespace app\modules\monitoring\models;

/**
 * This is the ActiveQuery class for [[MonitoringTaskLocations]].
 *
 * @see MonitoringTaskLocations
 */
class MonitoringTaskLocationsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return MonitoringTaskLocations[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return MonitoringTaskLocations|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
