<?php

namespace app\modules\referencesMasjaki\controllers;

use app\modules\referencesMasjaki\models\MasyarakatJasaKonstruksi;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class MasyarakatJasaKonstruksiController extends PembinaanBaseController
{
    public function actionIndex($q = null, $status = null)
    {
        $query = MasyarakatJasaKonstruksi::find()->where(['deleted_at' => null])->orderBy(['nama' => SORT_ASC]);
        if (trim((string) $q) !== '') {
            $query->andWhere(['or', ['like', 'nama', trim($q)], ['like', 'instansi', trim($q)], ['like', 'nik', trim($q)]]);
        }
        if (in_array((string) $status, ['0', '1'], true)) {
            $query->andWhere(['is_active' => (int) $status]);
        }
        return $this->render('index', [
            'provider' => new ActiveDataProvider(['query' => $query, 'pagination' => ['pageSize' => 20]]),
            'q' => $q, 'status' => $status,
        ]);
    }

    public function actionCreate()
    {
        return $this->edit(new MasyarakatJasaKonstruksi());
    }

    public function actionUpdate($id)
    {
        return $this->edit($this->findRecord($id));
    }

    public function actionToggleActive($id)
    {
        $record = $this->findRecord($id);
        $record->is_active = $record->is_active ? 0 : 1;
        if (!$record->save(false)) {
            throw new \RuntimeException('Status data masyarakat gagal diubah.');
        }
        Yii::$app->session->setFlash('success', 'Status data masyarakat berhasil diubah.');
        return $this->redirect(['index']);
    }

    public function actionDelete($id)
    {
        $record = $this->findRecord($id);
        $record->is_active = 0;
        $record->deleted_at = date('Y-m-d H:i:s');
        if (!$record->save(false)) {
            throw new \RuntimeException('Data masyarakat gagal dihapus.');
        }
        Yii::$app->session->setFlash('success', 'Data masyarakat diarsipkan. Riwayat kegiatan tetap tersimpan.');
        return $this->redirect(['index']);
    }

    private function edit(MasyarakatJasaKonstruksi $record)
    {
        if ($record->load(Yii::$app->request->post()) && $record->save()) {
            Yii::$app->session->setFlash('success', 'Data Masyarakat Jasa Konstruksi berhasil disimpan.');
            return $this->redirect(['index']);
        }
        return $this->render('form', ['record' => $record]);
    }

    private function findRecord($id)
    {
        $record = MasyarakatJasaKonstruksi::find()->where(['id' => $id, 'deleted_at' => null])->one();
        if ($record === null) {
            throw new NotFoundHttpException('Data Masyarakat Jasa Konstruksi tidak ditemukan.');
        }
        return $record;
    }
}
