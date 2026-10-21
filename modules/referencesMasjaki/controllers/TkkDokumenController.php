<?php

namespace app\modules\referencesMasjaki\controllers;

use app\components\TkkDokumenService;
use app\components\TkkDokumenStorage;
use app\modules\referencesMasjaki\models\DokumenPesertaSertifikasi;
use app\modules\referencesMasjaki\models\PesertaFasilitasiSertifikasi;
use app\modules\referencesMasjaki\models\TkkDokumenForm;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class TkkDokumenController extends TkkBaseController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verbs' => ['class' => VerbFilter::class, 'actions' => ['delete' => ['POST']]],
        ]);
    }

    public function actionUpload($peserta_id)
    {
        $peserta = PesertaFasilitasiSertifikasi::findOne($peserta_id);
        if ($peserta === null) { throw new NotFoundHttpException('Keikutsertaan tidak ditemukan.'); }
        $form = new TkkDokumenForm();
        if ($form->load(Yii::$app->request->post())) {
            $form->files = UploadedFile::getInstances($form, 'files');
            if ((new TkkDokumenService())->upload($peserta, $form)) {
                Yii::$app->session->setFlash('success', 'Dokumen berhasil diunggah.');
                return $this->redirect(['/references-masjaki/tkk-peserta/view', 'id' => $peserta->id]);
            }
        }
        return $this->render('upload', ['formModel' => $form, 'peserta' => $peserta]);
    }

    public function actionUnduh($id)
    {
        return TkkDokumenStorage::download($id);
    }

    public function actionDelete($id)
    {
        $record = DokumenPesertaSertifikasi::findOne($id);
        if ($record === null) { throw new NotFoundHttpException('Dokumen tidak ditemukan.'); }
        $pesertaId = $record->peserta_sertifikasi_id;
        (new TkkDokumenService())->delete($record);
        Yii::$app->session->setFlash('success', 'Dokumen berhasil dihapus.');
        return $this->redirect(['/references-masjaki/tkk-peserta/view', 'id' => $pesertaId]);
    }
}
