<?php

namespace app\modules\monitoring\models;

/**
 * This is the ActiveQuery class for [[MonitoringTagging]].
 *
 * @see MonitoringTagging
 */
class MonitoringTaggingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return MonitoringTagging[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return MonitoringTagging|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
