<?php

namespace app\modules\monitoring\models;

/**
 * This is the ActiveQuery class for [[MonitoringProgress]].
 *
 * @see MonitoringProgress
 */
class MonitoringProgressQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return MonitoringProgress[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return MonitoringProgress|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
