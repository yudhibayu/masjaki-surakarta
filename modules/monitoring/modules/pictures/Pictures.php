<?php

namespace app\modules\monitoring\modules\pictures;

/**
 * pictures module definition class
 */
class Pictures extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\monitoring\modules\pictures\controllers';
    public $uploadFilesPath = '@upload';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
