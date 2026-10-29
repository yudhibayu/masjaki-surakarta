<?php

namespace app\modules\referencesMasjaki\controllers;

use app\components\TkkDateForm;
use app\modules\referencesMasjaki\models\JenisKegiatanPembinaan;
use app\modules\referencesMasjaki\models\KegiatanPembinaan;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class KegiatanPembinaanController extends PembinaanBaseController
{
    public function actionIndex($tahun = null, $jenis = null, $publikasi = null)
    {
        if ($tahun !== null && $tahun !== '' && (!preg_match('/^\d{4}$/', (string) $tahun) || (int) $tahun < 1900 || (int) $tahun > 2100)) {
            throw new BadRequestHttpException('Filter tahun tidak valid.');
        }
        if ($jenis !== null && $jenis !== '' && !ctype_digit((string) $jenis)) {
            throw new BadRequestHttpException('Filter jenis kegiatan tidak valid.');
        }
        if ($publikasi !== null && $publikasi !== '' && !in_array((string) $publikasi, ['0', '1'], true)) {
            throw new BadRequestHttpException('Filter publikasi tidak valid.');
        }
        $query = KegiatanPembinaan::find()->alias('k')->with('jenisKegiatan')
            ->where(['k.deleted_at' => null])->orderBy(['k.tahun_anggaran' => SORT_DESC, 'k.id' => SORT_DESC]);
        if ($tahun !== null && $tahun !== '') {
            $query->andWhere(['k.tahun_anggaran' => (int) $tahun]);
        }
        if ($jenis !== null && $jenis !== '') {
            $query->andWhere(['k.jenis_kegiatan_id' => (int) $jenis]);
        }
        if (in_array((string) $publikasi, ['0', '1'], true)) {
            $query->andWhere(['k.is_published' => (int) $publikasi]);
        }
        return $this->render('index', [
            'provider' => new ActiveDataProvider(['query' => $query, 'pagination' => ['pageSize' => 20]]),
            'tahun' => $tahun, 'jenis' => $jenis, 'publikasi' => $publikasi,
            'jenisOptions' => ArrayHelper::map(JenisKegiatanPembinaan::find()->where(['deleted_at' => null])->orderBy('nama')->all(), 'id', 'nama'),
        ]);
    }

    public function actionView($id)
    {
        $record = $this->findRecord($id);
        $participants = $record->getPeserta()->with(['businessEntity', 'masyarakatJasaKonstruksi', 'hasil'])->orderBy(['id' => SORT_DESC])->all();
        return $this->render('view', ['record' => $record, 'participants' => $participants]);
    }

    public function actionCreate()
    {
        return $this->edit(new KegiatanPembinaan());
    }

    public function actionUpdate($id)
    {
        return $this->edit($this->findRecord($id));
    }

    public function actionPublish($id)
    {
        $record = $this->findRecord($id);
        $record->is_published = $record->is_published ? 0 : 1;
        if (!$record->save()) {
            throw new \RuntimeException('Status publikasi kegiatan gagal diubah.');
        }
        Yii::$app->session->setFlash('success', $record->is_published
            ? 'Kegiatan tampil pada rekap publik.' : 'Publikasi kegiatan dicabut.');
        return $this->redirect(['view', 'id' => $record->id]);
    }

    public function actionDelete($id)
    {
        $record = $this->findRecord($id);
        $record->is_published = 0;
        $record->deleted_at = date('Y-m-d H:i:s');
        if (!$record->save(false)) {
            throw new \RuntimeException('Kegiatan gagal diarsipkan.');
        }
        Yii::$app->session->setFlash('success', 'Kegiatan diarsipkan dan dikeluarkan dari rekap publik.');
        return $this->redirect(['index']);
    }

    private function edit(KegiatanPembinaan $record)
    {
        if ($record->load(Yii::$app->request->post())) {
            $datesValid = TkkDateForm::normalize($record, ['tanggal_mulai', 'tanggal_selesai']);
            if ($datesValid && $record->save()) {
                Yii::$app->session->setFlash('success', 'Kegiatan pembinaan berhasil disimpan.');
                return $this->redirect(['view', 'id' => $record->id]);
            }
        }
        $options = ArrayHelper::map(JenisKegiatanPembinaan::find()->where(['is_active' => 1, 'deleted_at' => null])->orderBy('nama')->all(), 'id', 'nama');
        if (!$record->isNewRecord && $record->jenisKegiatan && !isset($options[$record->jenis_kegiatan_id])) {
            $options[$record->jenis_kegiatan_id] = $record->jenisKegiatan->nama . ' (nonaktif)';
        }
        return $this->render('form', ['record' => $record, 'jenisOptions' => $options]);
    }

    private function findRecord($id)
    {
        $record = KegiatanPembinaan::find()->where(['id' => $id, 'deleted_at' => null])->with('jenisKegiatan')->one();
        if ($record === null) {
            throw new NotFoundHttpException('Kegiatan pembinaan tidak ditemukan.');
        }
        return $record;
    }
}
