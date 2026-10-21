<?php

namespace app\modules\referencesMasjaki\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

abstract class TkkBaseController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => ['class' => AccessControl::class, 'rules' => [['allow' => true, 'roles' => ['Administrator']]]],
        ];
    }
}
