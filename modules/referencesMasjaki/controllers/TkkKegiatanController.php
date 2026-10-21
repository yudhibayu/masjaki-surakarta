<?php

namespace app\modules\referencesMasjaki\controllers;

use app\components\TkkDateForm;
use app\modules\referencesMasjaki\models\KegiatanFasilitasiSertifikasi;
use app\modules\referencesMasjaki\models\MasterLsp;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class TkkKegiatanController extends TkkBaseController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verbs' => ['class' => VerbFilter::class, 'actions' => ['toggle-public' => ['POST']]],
        ]);
    }

    public function actionIndex($tahun = null, $lsp = null)
    {
        $query = KegiatanFasilitasiSertifikasi::find()->with(['lsp', 'peserta'])->orderBy(['tahun_anggaran' => SORT_DESC, 'tanggal_mulai' => SORT_DESC]);
        if ($tahun !== null && preg_match('/^\d{4}$/', (string) $tahun)) {
            $query->andWhere(['tahun_anggaran' => (int) $tahun]);
        }
        if ($lsp !== null && ctype_digit((string) $lsp)) {
            $query->andWhere(['lsp_id' => (int) $lsp]);
        }
        return $this->render('index', [
            'provider' => new ActiveDataProvider(['query' => $query, 'pagination' => ['pageSize' => 20], 'sort' => false]),
            'tahun' => $tahun, 'lsp' => $lsp,
            'lsps' => ArrayHelper::map(MasterLsp::find()->orderBy('nama_lsp')->all(), 'id', 'nama_lsp'),
        ]);
    }

    public function actionView($id)
    {
        $record = $this->findRecord($id);
        $participants = $record->getPeserta()->with('tenagaKerja')->orderBy(['id' => SORT_DESC])->all();
        return $this->render('view', ['record' => $record, 'participants' => $participants]);
    }

    public function actionCreate()
    {
        return $this->edit(new KegiatanFasilitasiSertifikasi());
    }

    public function actionUpdate($id)
    {
        return $this->edit($this->findRecord($id));
    }

    public function actionTogglePublic($id)
    {
        $record = $this->findRecord($id);
        $record->is_public = $record->is_public ? 0 : 1;
        if (!$record->save()) {
            throw new \RuntimeException('Status publikasi kegiatan gagal diubah.');
        }
        Yii::$app->session->setFlash('success', $record->is_public
            ? 'Kegiatan sudah tampil di web umum.' : 'Publikasi kegiatan sudah dicabut.');
        return $this->redirect(['view', 'id' => $record->id]);
    }

    private function edit(KegiatanFasilitasiSertifikasi $record)
    {
        if ($record->load(Yii::$app->request->post())) {
            $datesValid = TkkDateForm::normalize($record, ['tanggal_mulai', 'tanggal_selesai']);
            $lsp = MasterLsp::findOne($record->lsp_id);
            if ($lsp === null || (!$lsp->is_active && ($record->isNewRecord || $record->isAttributeChanged('lsp_id')))) {
                $record->addError('lsp_id', 'LSP tidak aktif atau tidak ditemukan.');
            } elseif ($datesValid && $record->save()) {
                Yii::$app->session->setFlash('success', 'Kegiatan berhasil disimpan.');
                return $this->redirect(['view', 'id' => $record->id]);
            }
        }
        $options = ArrayHelper::map(MasterLsp::find()->where(['is_active' => 1])->orderBy('nama_lsp')->all(), 'id', 'nama_lsp');
        if (!$record->isNewRecord && $record->lsp && !isset($options[$record->lsp_id])) {
            $options[$record->lsp_id] = $record->lsp->nama_lsp . ' (nonaktif)';
        }
        return $this->render('form', ['record' => $record, 'lsps' => $options]);
    }

    private function findRecord($id)
    {
        $record = KegiatanFasilitasiSertifikasi::find()->where(['id' => $id])->with('lsp')->one();
        if ($record === null) {
            throw new NotFoundHttpException('Kegiatan tidak ditemukan.');
        }
        return $record;
    }
}
