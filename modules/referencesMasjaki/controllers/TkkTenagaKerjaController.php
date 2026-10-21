<?php

namespace app\modules\referencesMasjaki\controllers;

use app\components\TkkDateForm;
use app\modules\referencesMasjaki\models\TenagaKerjaKonstruksi;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class TkkTenagaKerjaController extends TkkBaseController
{
    public function actionIndex($q = null)
    {
        $query = TenagaKerjaKonstruksi::find()->orderBy(['nama_lengkap' => SORT_ASC]);
        if (trim((string) $q) !== '') {
            $query->andWhere(['or', ['like', 'nama_lengkap', trim($q)], ['like', 'nik', trim($q)]]);
        }
        return $this->render('index', [
            'provider' => new ActiveDataProvider(['query' => $query, 'pagination' => ['pageSize' => 20], 'sort' => false]),
            'q' => $q,
        ]);
    }

    public function actionView($id)
    {
        $record = $this->findRecord($id);
        $history = $record->getKeikutsertaan()->with('kegiatan')->orderBy(['id' => SORT_DESC])->all();
        return $this->render('view', ['record' => $record, 'history' => $history]);
    }

    public function actionCreate()
    {
        return $this->edit(new TenagaKerjaKonstruksi());
    }

    public function actionUpdate($id)
    {
        return $this->edit($this->findRecord($id));
    }

    private function edit(TenagaKerjaKonstruksi $record)
    {
        if ($record->load(Yii::$app->request->post())) {
            $datesValid = TkkDateForm::normalize($record, ['tanggal_lahir']);
            if ($datesValid && $record->save()) {
                Yii::$app->session->setFlash('success', 'Identitas tenaga kerja berhasil disimpan.');
                return $this->redirect(['view', 'id' => $record->id]);
            }
        }
        return $this->render('form', ['record' => $record]);
    }

    private function findRecord($id)
    {
        $record = TenagaKerjaKonstruksi::findOne($id);
        if ($record === null) {
            throw new NotFoundHttpException('Tenaga kerja tidak ditemukan.');
        }
        return $record;
    }
}
