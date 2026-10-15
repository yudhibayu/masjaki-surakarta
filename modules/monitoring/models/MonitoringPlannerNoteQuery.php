<?php

namespace app\modules\monitoring\models;

/**
 * This is the ActiveQuery class for [[MonitoringPlannerNote]].
 *
 * @see MonitoringPlannerNote
 */
class MonitoringPlannerNoteQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return MonitoringPlannerNote[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return MonitoringPlannerNote|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
