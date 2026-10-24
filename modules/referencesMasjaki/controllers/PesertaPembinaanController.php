<?php

namespace app\modules\referencesMasjaki\controllers;

use app\modules\businessEntity\models\BusinessEntity;
use app\modules\referencesMasjaki\models\KegiatanPembinaan;
use app\modules\referencesMasjaki\models\MasyarakatJasaKonstruksi;
use app\modules\referencesMasjaki\models\PesertaPembinaan;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use kartik\select2\Select2;

class PesertaPembinaanController extends PembinaanBaseController
{
    public function actionIndex($tahun = null, $kegiatan = null)
    {
        if ($tahun !== null && $tahun !== '' && (!preg_match('/^\d{4}$/', (string) $tahun) || (int) $tahun < 1900 || (int) $tahun > 2100)) {
            throw new BadRequestHttpException('Filter tahun tidak valid.');
        }
        if ($kegiatan !== null && $kegiatan !== '' && !ctype_digit((string) $kegiatan)) {
            throw new BadRequestHttpException('Filter kegiatan tidak valid.');
        }
        $query = PesertaPembinaan::find()->alias('p')->innerJoinWith('kegiatan k')
            ->with(['businessEntity', 'masyarakatJasaKonstruksi', 'kegiatan.jenisKegiatan', 'hasil'])
            ->where(['p.deleted_at' => null, 'k.deleted_at' => null])->orderBy(['k.tahun_anggaran' => SORT_DESC, 'p.id' => SORT_DESC]);
        if ($tahun !== null && $tahun !== '') {
            $query->andWhere(['k.tahun_anggaran' => (int) $tahun]);
        }
        if ($kegiatan !== null && $kegiatan !== '') {
            $query->andWhere(['p.kegiatan_id' => (int) $kegiatan]);
        }
        return $this->render('index', [
            'provider' => new ActiveDataProvider(['query' => $query, 'pagination' => ['pageSize' => 20]]),
            'tahun' => $tahun, 'kegiatan' => $kegiatan,
            'kegiatanOptions' => ArrayHelper::map(KegiatanPembinaan::find()->where(['deleted_at' => null])->orderBy(['tahun_anggaran' => SORT_DESC, 'nama_kegiatan' => SORT_ASC])->all(), 'id', function ($item) {
                return $item->tahun_anggaran . ' — ' . $item->nama_kegiatan;
            }),
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', ['record' => $this->findRecord($id)]);
    }

    public function actionCreate($kegiatan_id = null)
    {
        $record = new PesertaPembinaan();
        $record->kegiatan_id = $kegiatan_id;
        return $this->edit($record);
    }

    public function actionUpdate($id)
    {
        return $this->edit($this->findRecord($id));
    }

    public function actionDelete($id)
    {
        $record = $this->findRecord($id);
        $record->deleted_at = date('Y-m-d H:i:s');
        if (!$record->save(false)) {
            throw new \RuntimeException('Keikutsertaan gagal diarsipkan.');
        }
        Yii::$app->session->setFlash('success', 'Peserta diarsipkan; hasil pembinaan tetap tersimpan sebagai riwayat.');
        return $this->redirect(['index']);
    }

    public function actionCariBujk($q = '')
    {
        return $this->searchResults($q, PesertaPembinaan::BUJK);
    }

    public function actionCariMasyarakat($q = '', $jenis_subjek = MasyarakatJasaKonstruksi::PERSEORANGAN)
    {
        if (!in_array($jenis_subjek, [MasyarakatJasaKonstruksi::PERSEORANGAN, MasyarakatJasaKonstruksi::KELOMPOK], true)) {
            throw new BadRequestHttpException('Jenis peserta tidak valid.');
        }
        return $this->searchResults($q, PesertaPembinaan::MASYARAKAT, $jenis_subjek);
    }

    private function searchResults($q, $type, $jenisSubjek = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $q = trim((string) $q);
        if (mb_strlen($q) < 2) {
            return ['results' => []];
        }
        if ($type === PesertaPembinaan::BUJK) {
            $records = BusinessEntity::find()->select(['id', 'company_name'])
                ->where(['account_verification' => BusinessEntity::VERIFICATION, 'deleted_at' => null])
                ->andWhere(['like', 'company_name', $q])->orderBy(['company_name' => SORT_ASC])->limit(20)->all();
            $results = [];
            foreach ($records as $record) {
                $results[] = ['id' => $record->id, 'text' => $record->company_name];
            }
            return ['results' => $results];
        }
        $records = MasyarakatJasaKonstruksi::find()->select(['id', 'nama'])
            ->where(['is_active' => 1, 'deleted_at' => null, 'jenis_subjek' => $jenisSubjek])->andWhere(['like', 'nama', $q])
            ->orderBy(['nama' => SORT_ASC])->limit(20)->all();
        $results = [];
        foreach ($records as $record) {
            $results[] = ['id' => $record->id, 'text' => $record->nama];
        }
        return ['results' => $results];
    }

    private function edit(PesertaPembinaan $record)
    {
        if ($record->load(Yii::$app->request->post()) && $record->save()) {
            Yii::$app->session->setFlash('success', 'Keikutsertaan peserta berhasil disimpan.');
            return $this->redirect(['view', 'id' => $record->id]);
        }
        $selectedBujk = [];
        if ($record->id_business_entity) {
            $business = BusinessEntity::findOne($record->id_business_entity);
            if ($business) {
                $selectedBujk[$business->id] = $business->company_name;
            }
        }
        $selectedMasyarakat = [];
        if ($record->masyarakat_jasa_konstruksi_id) {
            $society = MasyarakatJasaKonstruksi::findOne($record->masyarakat_jasa_konstruksi_id);
            if ($society) {
                $record->jenis_entitas = $society->jenis_subjek;
                $selectedMasyarakat[$society->id] = ($society->jenis_subjek === MasyarakatJasaKonstruksi::KELOMPOK ? 'Kelompok — ' : 'Masyarakat — ') . $society->nama;
            }
        }
        return $this->render('form', [
            'record' => $record,
            'activities' => ArrayHelper::map(KegiatanPembinaan::find()->where(['deleted_at' => null])
                ->orderBy(['tahun_anggaran' => SORT_DESC, 'nama_kegiatan' => SORT_ASC])->all(), 'id', function ($item) {
                    return $item->tahun_anggaran . ' — ' . $item->nama_kegiatan;
                }),
            'availableBujkCount' => (int) BusinessEntity::find()
                ->where(['account_verification' => BusinessEntity::VERIFICATION, 'deleted_at' => null])
                ->count(),
            'selectedBujk' => $selectedBujk, 'selectedMasyarakat' => $selectedMasyarakat,
        ]);
    }

    private function findRecord($id)
    {
        $record = PesertaPembinaan::find()->where(['id' => $id, 'deleted_at' => null])
            ->with(['businessEntity', 'masyarakatJasaKonstruksi', 'kegiatan.jenisKegiatan', 'hasil'])->one();
        if ($record === null) {
            throw new NotFoundHttpException('Peserta pembinaan tidak ditemukan.');
        }
        return $record;
    }
}
