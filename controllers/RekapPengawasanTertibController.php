<?php

namespace app\controllers;

use app\components\TertibPublikRekap;
use app\components\Theming;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class RekapPengawasanTertibController extends Controller
{
    public function beforeAction($action)
    {
        Theming::setThemes(Yii::$app->params['frontEndTheme']);
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $years = TertibPublikRekap::years();
        $requestedYear = Yii::$app->request->get('tahun');
        if ($requestedYear === null || $requestedYear === '') {
            $year = (int) (reset($years) ?: date('Y'));
        } elseif (is_scalar($requestedYear) && preg_match('/\A\d{4}\z/', (string) $requestedYear)) {
            $year = (int) $requestedYear;
        } else {
            throw new BadRequestHttpException('Tahun anggaran tidak valid.');
        }

        $yearOptions = array_combine($years, $years) ?: [];
        if (!isset($yearOptions[$year])) {
            $yearOptions[$year] = $year;
            krsort($yearOptions);
        }

        return $this->render('index', [
            'selectedYear' => $year,
            'yearOptions' => $yearOptions,
            'report' => TertibPublikRekap::summary($year),
        ]);
    }
}
