<?php

namespace app\modules\referencesMasjaki\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

abstract class TkkRecord extends ActiveRecord
{
    public function behaviors()
    {
        return [[
            'class' => TimestampBehavior::class,
            'attributes' => [
                self::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                self::EVENT_BEFORE_UPDATE => ['updated_at'],
            ],
            'value' => function () { return date('Y-m-d H:i:s'); },
        ]];
    }
}
