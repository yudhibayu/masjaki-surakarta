<?php

namespace app\modules\referencesMasjaki\controllers;

use app\modules\referencesMasjaki\models\HasilPembinaan;
use app\modules\referencesMasjaki\models\PesertaPembinaan;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class HasilPembinaanController extends PembinaanBaseController
{
    public function actionIndex($tahun = null)
    {
        if ($tahun !== null && $tahun !== '' && (!preg_match('/^\d{4}$/', (string) $tahun) || (int) $tahun < 1900 || (int) $tahun > 2100)) {
            throw new BadRequestHttpException('Filter tahun tidak valid.');
        }
        $query = PesertaPembinaan::find()->alias('p')->innerJoinWith('kegiatan k')
            ->with(['kegiatan', 'businessEntity', 'masyarakatJasaKonstruksi', 'hasil'])
            ->where(['p.deleted_at' => null, 'k.deleted_at' => null])->orderBy(['k.tahun_anggaran' => SORT_DESC, 'p.id' => SORT_DESC]);
        if ($tahun !== null && $tahun !== '') {
            $query->andWhere(['k.tahun_anggaran' => (int) $tahun]);
        }
        return $this->render('index', ['provider' => new ActiveDataProvider(['query' => $query, 'pagination' => ['pageSize' => 20]]), 'tahun' => $tahun]);
    }

    public function actionUpdate($peserta_id)
    {
        $participant = PesertaPembinaan::find()->where(['id' => $peserta_id, 'deleted_at' => null])
            ->with(['businessEntity', 'masyarakatJasaKonstruksi', 'kegiatan'])->one();
        if ($participant === null) {
            throw new NotFoundHttpException('Peserta pembinaan tidak ditemukan.');
        }
        $record = HasilPembinaan::findOne(['peserta_pembinaan_id' => $participant->id]);
        if ($record === null) {
            $record = new HasilPembinaan();
            $record->peserta_pembinaan_id = $participant->id;
        }
        if ($record->load(Yii::$app->request->post())) {
            $record->peserta_pembinaan_id = $participant->id;
            if ($record->save()) {
                Yii::$app->session->setFlash('success', 'Hasil pembinaan berhasil disimpan.');
                return $this->redirect(['/references-masjaki/peserta-pembinaan/view', 'id' => $participant->id]);
            }
        }
        return $this->render('form', ['record' => $record, 'participant' => $participant]);
    }
}
