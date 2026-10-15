<?php

namespace app\modules\referencesMasjaki;

use Yii;

/**
 * setting module definition class
 */
class References extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\referencesMasjaki\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }


    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/references/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-EN',
            'basePath' => '@app/modules/references/messages',
            'fileMap' => [
                'modules/references/year_lang' => 'year_lang.php',
            ],
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/references/' . $category, $message, $params, $language);
    }
}
