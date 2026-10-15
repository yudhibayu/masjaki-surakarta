<?php

namespace app\modules\rup\models;

/**
 * This is the ActiveQuery class for [[RefKegiatan]].
 *
 * @see RefKegiatan
 */
class RefKegiatanQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return RefKegiatan[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return RefKegiatan|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
