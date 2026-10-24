<?php

namespace app\modules\referencesMasjaki\controllers;

use yii\filters\VerbFilter;

abstract class PembinaanBaseController extends TkkBaseController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'toggle-active' => ['POST'],
                    'publish' => ['POST'],
                ],
            ],
        ]);
    }
}
