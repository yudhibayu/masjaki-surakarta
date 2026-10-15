<?php

namespace app\modules\rup\models;

/**
 * This is the ActiveQuery class for [[RefProgram]].
 *
 * @see RefProgram
 */
class RefProgramQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return RefProgram[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return RefProgram|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
