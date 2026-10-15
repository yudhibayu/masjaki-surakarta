<?php
namespace app\components;

use Yii;
use yii\web\NotFoundHttpException;
use app\modules\app_setting\models\App_setting;
use yii\helpers\ArrayHelper;

class Appsetting
{
	/**
     * Get App Setting Value
     *
     * @return string
     */
    public static function get($key)
    {
        $global_setting_db = App_setting::find()->select('key, value')->all();
        $global_setting = ArrayHelper::map($global_setting_db, 'key', 'value');
        if(isset($global_setting[$key])){
            return $global_setting[$key];
        }
        return null;
    }
}