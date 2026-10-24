<?php

namespace app\modules\referencesMasjaki\controllers;

use app\modules\referencesMasjaki\models\JenisKegiatanPembinaan;
use app\modules\referencesMasjaki\models\KegiatanPembinaan;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class JenisKegiatanPembinaanController extends PembinaanBaseController
{
    public function actionIndex()
    {
        $query = JenisKegiatanPembinaan::find()->where(['deleted_at' => null])->orderBy(['nama' => SORT_ASC]);
        return $this->render('index', ['provider' => new ActiveDataProvider(['query' => $query, 'pagination' => ['pageSize' => 20]])]);
    }

    public function actionCreate()
    {
        return $this->edit(new JenisKegiatanPembinaan());
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
            throw new \RuntimeException('Status jenis kegiatan gagal diubah.');
        }
        return $this->redirect(['index']);
    }

    public function actionDelete($id)
    {
        $record = $this->findRecord($id);
        if (KegiatanPembinaan::find()->where(['jenis_kegiatan_id' => $record->id])->exists()) {
            $record->is_active = 0;
            if (!$record->save(false)) {
                throw new \RuntimeException('Jenis kegiatan gagal dinonaktifkan.');
            }
            Yii::$app->session->setFlash('success', 'Jenis kegiatan dinonaktifkan karena sudah digunakan. Riwayat kegiatan tetap dapat dikelola.');
            return $this->redirect(['index']);
        }
        $record->is_active = 0;
        $record->deleted_at = date('Y-m-d H:i:s');
        if (!$record->save(false)) {
            throw new \RuntimeException('Jenis kegiatan gagal diarsipkan.');
        }
        Yii::$app->session->setFlash('success', 'Jenis kegiatan diarsipkan; riwayat kegiatan tidak dihapus.');
        return $this->redirect(['index']);
    }

    private function edit(JenisKegiatanPembinaan $record)
    {
        if ($record->load(Yii::$app->request->post()) && $record->save()) {
            Yii::$app->session->setFlash('success', 'Jenis kegiatan berhasil disimpan.');
            return $this->redirect(['index']);
        }
        return $this->render('form', ['record' => $record]);
    }

    private function findRecord($id)
    {
        $record = JenisKegiatanPembinaan::find()->where(['id' => $id, 'deleted_at' => null])->one();
        if ($record === null) {
            throw new NotFoundHttpException('Jenis kegiatan tidak ditemukan.');
        }
        return $record;
    }
}
