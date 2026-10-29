<?php

namespace app\modules\referencesMasjaki\controllers;

use app\components\PembinaanDokumenService;
use app\components\PembinaanDokumenStorage;
use app\modules\referencesMasjaki\models\DokumenKegiatanPembinaan;
use app\modules\referencesMasjaki\models\KegiatanPembinaan;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class DokumenPembinaanController extends PembinaanBaseController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verbs' => ['class' => \yii\filters\VerbFilter::class, 'actions' => ['delete' => ['POST']]],
        ]);
    }

    public function actionIndex($kegiatan_id = null)
    {
        $kegiatanOptions = [];
        $activities = KegiatanPembinaan::find()
            ->select(['id', 'nama_kegiatan', 'tahun_anggaran'])
            ->where(['deleted_at' => null])
            ->orderBy(['tahun_anggaran' => SORT_DESC, 'nama_kegiatan' => SORT_ASC])
            ->asArray()
            ->all();

        foreach ($activities as $activity) {
            $kegiatanOptions[$activity['id']] = $activity['tahun_anggaran'] . ' — ' . $activity['nama_kegiatan'];
        }

        if ($kegiatan_id === '') {
            $kegiatan_id = null;
        } elseif ($kegiatan_id !== null && !ctype_digit((string) $kegiatan_id)) {
            throw new BadRequestHttpException('Filter kegiatan tidak valid.');
        }

        $selectedActivityId = $kegiatan_id === null ? null : (int) $kegiatan_id;
        if ($selectedActivityId !== null && !isset($kegiatanOptions[$selectedActivityId])) {
            throw new NotFoundHttpException('Kegiatan pembinaan tidak ditemukan.');
        }

        $query = DokumenKegiatanPembinaan::find()->alias('d')->innerJoinWith('kegiatan k')->with('kegiatan')
            ->where(['k.deleted_at' => null])->orderBy(['d.created_at' => SORT_DESC]);
        if ($selectedActivityId !== null) {
            $query->andWhere(['d.kegiatan_id' => $selectedActivityId]);
        }

        return $this->render('index', [
            'provider' => new ActiveDataProvider(['query' => $query, 'pagination' => ['pageSize' => 20]]),
            'kegiatan_id' => $selectedActivityId,
            'kegiatanOptions' => $kegiatanOptions,
        ]);
    }

    public function actionUpload($kegiatan_id)
    {
        $activity = KegiatanPembinaan::find()->where(['id' => $kegiatan_id, 'deleted_at' => null])->one();
        if ($activity === null) {
            throw new NotFoundHttpException('Kegiatan pembinaan tidak ditemukan.');
        }
        $record = new DokumenKegiatanPembinaan(['kegiatan_id' => $activity->id]);
        if ($record->load(Yii::$app->request->post())) {
            $record->kegiatan_id = $activity->id;
            $record->upload = UploadedFile::getInstance($record, 'upload');
            if ((new PembinaanDokumenService())->upload($record)) {
                Yii::$app->session->setFlash('success', 'Dokumen kegiatan berhasil diunggah.');
                return $this->redirect(['index', 'kegiatan_id' => $activity->id]);
            }
        }
        return $this->render('upload', ['record' => $record, 'activity' => $activity]);
    }

    public function actionDownload($id)
    {
        $record = $this->findRecord($id);
        $response = Yii::$app->response->sendFile(
            PembinaanDokumenStorage::path($record->file_path),
            PembinaanDokumenStorage::safeName($record->nama_file),
            ['inline' => false, 'mimeType' => $record->mime_type]
        );
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Cache-Control', 'no-store');
        return $response;
    }

    public function actionDelete($id)
    {
        $record = $this->findRecord($id);
        (new PembinaanDokumenService())->delete($record);
        Yii::$app->session->setFlash('success', 'Dokumen berhasil dihapus.');
        return $this->redirect(['index', 'kegiatan_id' => $record->kegiatan_id]);
    }

    private function findRecord($id)
    {
        $record = DokumenKegiatanPembinaan::find()->where(['id' => $id])->with('kegiatan')->one();
        if ($record === null || $record->kegiatan === null || $record->kegiatan->deleted_at !== null) {
            throw new NotFoundHttpException('Dokumen pembinaan tidak ditemukan.');
        }
        return $record;
    }
}
