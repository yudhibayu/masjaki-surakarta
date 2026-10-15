<?php

namespace app\modules\technicalspec\models;

/**
 * This is the ActiveQuery class for [[RefComponentCategory]].
 *
 * @see RefComponentCategory
 */
class RefComponentCategoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return RefComponentCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return RefComponentCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
