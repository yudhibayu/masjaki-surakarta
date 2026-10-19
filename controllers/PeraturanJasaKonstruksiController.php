<?php

namespace app\controllers;

use app\components\StandarHargaStorage;
use app\components\Theming;
use app\modules\referencesMasjaki\models\ProdukHukumTahunan;
use Yii;
use yii\web\Controller;

class PeraturanJasaKonstruksiController extends Controller
{
    public function beforeAction($action)
    {
        Theming::setThemes(Yii::$app->params['frontEndTheme']);
        return parent::beforeAction($action);
    }

    public function actionIndex($tahun = null)
    {
        $years = ProdukHukumTahunan::find()->select('tahun')
            ->where(['is_public' => 1, 'deleted_at' => null])
            ->distinct()->orderBy(['tahun' => SORT_DESC])->column();
        $selectedYear = $tahun !== null && preg_match('/^\d{4}$/', (string) $tahun) ? (int) $tahun : (isset($years[0]) ? (int) $years[0] : null);
        $documents = $selectedYear === null ? [] : ProdukHukumTahunan::find()
            ->alias('d')->joinWith('master m')
            ->where(['d.tahun' => $selectedYear, 'd.is_public' => 1, 'd.deleted_at' => null])
            ->orderBy(['m.nama' => SORT_ASC, 'd.id' => SORT_ASC])->all();
        return $this->render('index', ['years' => $years, 'selectedYear' => $selectedYear, 'documents' => $documents]);
    }

    public function actionLihat($id)
    {
        return StandarHargaStorage::send($id, true, true);
    }

    public function actionUnduh($id)
    {
        return StandarHargaStorage::send($id, false, true);
    }
}
