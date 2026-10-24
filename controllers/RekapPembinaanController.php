<?php

namespace app\controllers;

use app\components\RekapPembinaan;
use app\components\Theming;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class RekapPembinaanController extends Controller
{
    public function beforeAction($action)
    {
        Theming::setThemes(Yii::$app->params['frontEndTheme']);
        return parent::beforeAction($action);
    }

    public function actionIndex($tahun = null)
    {
        if ($tahun === '') {
            $tahun = null;
        }
        if ($tahun !== null && (!preg_match('/^\d{4}$/', (string) $tahun) || (int) $tahun < 1900 || (int) $tahun > 2100)) {
            throw new BadRequestHttpException('Filter tahun tidak valid.');
        }
        return $this->render('index', [
            'selectedYear' => $tahun === null ? null : (int) $tahun,
            'report' => RekapPembinaan::summary($tahun, true),
        ]);
    }
}
