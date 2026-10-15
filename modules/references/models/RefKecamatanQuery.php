<?php

namespace app\modules\references\models;

/**
 * This is the ActiveQuery class for [[RefKecamatan]].
 *
 * @see RefKecamatan
 */
class RefKecamatanQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return RefKecamatan[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return RefKecamatan|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
