<?php

namespace app\modules\referencesMasjaki\controllers;

use app\components\TkkDateForm;
use app\modules\referencesMasjaki\models\KegiatanFasilitasiSertifikasi;
use app\modules\referencesMasjaki\models\PesertaFasilitasiSertifikasi;
use app\modules\referencesMasjaki\models\TenagaKerjaKonstruksi;
use app\modules\referencesMasjaki\models\TkkReportFilter;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class TkkPesertaController extends TkkBaseController
{
    public function actionIndex()
    {
        $filter = new TkkReportFilter();
        $filter->load(Yii::$app->request->queryParams, '');
        if (!$filter->validate()) { $filter = new TkkReportFilter(); }
        $query = PesertaFasilitasiSertifikasi::find()->alias('p')
            ->innerJoin('{{%kegiatan_fasilitasi_sertifikasi}} k', 'k.id=p.kegiatan_id')
            ->select('p.*')->with(['tenagaKerja', 'kegiatan.lsp'])->orderBy(['p.id' => SORT_DESC]);
        $filter->apply($query);
        return $this->render('index', [
            'provider' => new ActiveDataProvider(['query' => $query, 'pagination' => ['pageSize' => 20], 'sort' => false]),
            'filter' => $filter,
            'activities' => ArrayHelper::map(KegiatanFasilitasiSertifikasi::find()->orderBy(['tahun_anggaran' => SORT_DESC])->all(), 'id', 'nama_kegiatan'),
        ]);
    }

    public function actionView($id)
    {
        $record = $this->findRecord($id);
        return $this->render('view', ['record' => $record]);
    }

    public function actionCreate($kegiatan_id = null, $tenaga_kerja_id = null)
    {
        $record = new PesertaFasilitasiSertifikasi();
        $record->kegiatan_id = $kegiatan_id;
        $record->tenaga_kerja_id = $tenaga_kerja_id;
        $record->jenis_fasilitasi = 'SERTIFIKASI_BARU';
        $record->status_sertifikasi = 'TERDAFTAR';
        return $this->edit($record);
    }

    public function actionUpdate($id)
    {
        return $this->edit($this->findRecord($id));
    }

    public function actionCariTenagaKerja($q = '')
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $q = trim((string) $q);
        if (mb_strlen($q) < 2) {
            return ['results' => []];
        }
        $people = TenagaKerjaKonstruksi::find()
            ->where(['or', ['like', 'nama_lengkap', $q], ['like', 'nik', $q]])
            ->orderBy('nama_lengkap')->limit(20)->all();
        $results = [];
        foreach ($people as $person) {
            $results[] = ['id' => $person->id, 'text' => $person->nama_lengkap . ' — ' . $person->nikTersamar];
        }
        return ['results' => $results];
    }

    private function edit(PesertaFasilitasiSertifikasi $record)
    {
        if ($record->load(Yii::$app->request->post())) {
            $datesValid = TkkDateForm::normalize($record, ['tanggal_terbit_skk', 'masa_berlaku_skk_hingga']);
            if ($datesValid && $record->save()) {
                Yii::$app->session->setFlash('success', 'Keikutsertaan sertifikasi berhasil disimpan.');
                return $this->redirect(['view', 'id' => $record->id]);
            }
        }
        $selectedPerson = $record->tenaga_kerja_id ? TenagaKerjaKonstruksi::findOne($record->tenaga_kerja_id) : null;
        return $this->render('form', [
            'record' => $record, 'selectedPerson' => $selectedPerson,
            'activities' => ArrayHelper::map(KegiatanFasilitasiSertifikasi::find()
                ->orderBy(['tahun_anggaran' => SORT_DESC, 'nama_kegiatan' => SORT_ASC])->all(), 'id',
                function ($activity) { return $activity->tahun_anggaran . ' — ' . $activity->nama_kegiatan; }),
        ]);
    }

    private function findRecord($id)
    {
        $record = PesertaFasilitasiSertifikasi::find()->where(['id' => $id])
            ->with(['tenagaKerja', 'kegiatan.lsp', 'dokumen'])->one();
        if ($record === null) {
            throw new NotFoundHttpException('Keikutsertaan tidak ditemukan.');
        }
        return $record;
    }
}
