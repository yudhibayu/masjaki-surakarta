<?php

namespace app\modules\technicalspec\models;

/**
 * This is the ActiveQuery class for [[McoCcoFile]].
 *
 * @see McoCcoFile
 */
class McoCcoFileQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return McoCcoFile[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return McoCcoFile|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
