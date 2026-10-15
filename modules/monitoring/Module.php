<?php

namespace app\modules\monitoring;

use Yii;

/**
 * monitoring module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\monitoring\controllers';

    public $userClass = 'app\models\User';
    public $userObject;

    public $unitClass = 'app\models\Unit';
    public $unitPrimaryKey = 'id';
    public $unitNameKey = 'name';
    public $unitObject;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        $this->userObject = Yii::createObject([
            'class' => $this->userClass,
        ]);
        // $this->unitObject = Yii::createObject([
        //     'class' => $this->unitClass,
        // ]);
        $this->modules = [
            // 'locations' => [
            //     'class' => 'app\modules\monitoring\modules\locations\Locations',
            // ],
            'pictures' => [
                'class' => 'app\modules\monitoring\modules\pictures\Pictures',
            ],
        ];
    }
}
