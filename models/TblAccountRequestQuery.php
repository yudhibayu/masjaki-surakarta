<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TblAccountRequest]].
 *
 * @see TblAccountRequest
 */
class TblAccountRequestQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return TblAccountRequest[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TblAccountRequest|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
