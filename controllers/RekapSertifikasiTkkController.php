<?php

namespace app\controllers;

use app\components\Theming;
use app\components\TkkPublikRekap;
use app\modules\referencesMasjaki\models\TkkPublikFilter;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class RekapSertifikasiTkkController extends Controller
{
    public function beforeAction($action)
    {
        Theming::setThemes(Yii::$app->params['frontEndTheme']);
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $filter = new TkkPublikFilter();
        $filter->tahun = Yii::$app->request->get('tahun');
        if (!$filter->validate()) {
            throw new BadRequestHttpException('Filter rekap tidak valid.');
        }
        return $this->render('index', ['filter' => $filter, 'report' => TkkPublikRekap::summary($filter)]);
    }

    public function actionDetail($id)
    {
        if ((!is_string($id) && !is_int($id)) || !ctype_digit((string) $id) || (int) $id < 1) {
            throw new NotFoundHttpException('Kegiatan tidak ditemukan.');
        }
        $report = TkkPublikRekap::detail((int) $id);
        if ($report === null) {
            throw new NotFoundHttpException('Kegiatan tidak ditemukan.');
        }
        return $this->render('detail', ['report' => $report]);
    }
}
