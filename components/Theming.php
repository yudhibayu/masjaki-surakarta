<?php
namespace app\components;

use Yii;
use yii\base\Theme;

class Theming
{
	/**
     * Set Themes.
     *
     * @return string
     */
    public static function setThemes($theme_name)
    {
        if ($theme_name) {
            Yii::$app->getView()->theme = new Theme([
                'basePath' => '@app/themes/'.$theme_name,
                'baseUrl' => '@web/themes/'.$theme_name,
                'pathMap' => [
                    '@app/views' => '@app/themes/'.$theme_name.'/views',
					'@infinindotech/user/views' => '@app/overrides/user/views'
                ],
            ]);
        }
    }
}