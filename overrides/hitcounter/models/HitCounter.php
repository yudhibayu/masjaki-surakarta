<?php

namespace app\overrides\hitcounter\models;

use coderius\hitCounter\entities\HitCounter as EntitiesHitCounter;
use Yii;
use yii\data\ActiveDataProvider;

class HitCounter extends EntitiesHitCounter
{
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }
}