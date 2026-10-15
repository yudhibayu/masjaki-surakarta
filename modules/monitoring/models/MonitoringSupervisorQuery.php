<?php

namespace app\modules\monitoring\models;

/**
 * This is the ActiveQuery class for [[MonitoringSupervisor]].
 *
 * @see MonitoringSupervisor
 */
class MonitoringSupervisorQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return MonitoringSupervisor[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return MonitoringSupervisor|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
