<?php

namespace app\modules\technicalspec\models;

/**
 * This is the ActiveQuery class for [[McoCco]].
 *
 * @see McoCco
 */
class McoCcoQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return McoCco[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return McoCco|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
