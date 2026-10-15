<?php

namespace app\modules\technicalspec\models;

/**
 * This is the ActiveQuery class for [[DedFiles]].
 *
 * @see DedFiles
 */
class DedFilesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return DedFiles[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return DedFiles|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
