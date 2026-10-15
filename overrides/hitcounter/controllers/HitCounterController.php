<?php
/*
 * @copyright Copyright (C) 2019 Sergio coderius <coderius>
 * @license This program is free software: the MIT License (MIT)
 */

namespace app\overrides\hitcounter\controllers;

use app\overrides\hitcounter\models\HitCounter;
use yii\web\Controller;
use Yii;
use coderius\hitCounter\services\HitCounterService;

/**
 * Default controller.
 */
class HitCounterController extends Controller
{
    private $service;

    public function __construct($id, $module, HitCounterService $service, $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    public function actionIndex()
    {
        $request = Yii::$app->request;
        $modelHitCounter = $this->service->loadModel($request);

        if ($modelHitCounter && $modelHitCounter->validate()) {
            try {
                $hit = $this->service->create($modelHitCounter, HitCounter::class);
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['status' => 'success'];
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'status' => 'error',
                    // 'errors' => $e->getMessage(),
                ];
            }
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $modelHitCounter->errors;
        // return $modelHitCounter->getAttributes();
    }
}
