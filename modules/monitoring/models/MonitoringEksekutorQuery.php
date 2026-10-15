<?php

namespace app\modules\monitoring\models;

/**
 * This is the ActiveQuery class for [[MonitoringEksekutor]].
 *
 * @see MonitoringEksekutor
 */
class MonitoringEksekutorQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return MonitoringEksekutor[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return MonitoringEksekutor|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
