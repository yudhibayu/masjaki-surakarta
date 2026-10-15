<?php

namespace app\modules\references\models;

/**
 * This is the ActiveQuery class for [[RefTagging]].
 *
 * @see RefTagging
 */
class RefTaggingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return RefTagging[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return RefTagging|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
