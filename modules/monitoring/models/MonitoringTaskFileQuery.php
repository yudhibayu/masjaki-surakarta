<?php

namespace app\modules\monitoring\models;

/**
 * This is the ActiveQuery class for [[MonitoringTaskFile]].
 *
 * @see MonitoringTaskFile
 */
class MonitoringTaskFileQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return MonitoringTaskFile[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return MonitoringTaskFile|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
