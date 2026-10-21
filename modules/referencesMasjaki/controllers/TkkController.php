<?php

namespace app\modules\referencesMasjaki\controllers;

use app\modules\referencesMasjaki\models\KegiatanFasilitasiSertifikasi;
use app\modules\referencesMasjaki\models\MasterLsp;
use app\modules\referencesMasjaki\models\PesertaFasilitasiSertifikasi;
use app\modules\referencesMasjaki\models\TkkReportFilter;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class TkkController extends TkkBaseController
{
    public function actionIndex()
    {
        $filter = new TkkReportFilter();
        $filter->load(Yii::$app->request->queryParams, '');
        if (!$filter->validate()) {
            $filter = new TkkReportFilter();
        }
        $base = PesertaFasilitasiSertifikasi::find()->alias('p')
            ->innerJoin('{{%kegiatan_fasilitasi_sertifikasi}} k', 'k.id = p.kegiatan_id');
        $filter->apply($base);

        $stats = (clone $base)->select([
            'orang' => 'COUNT(DISTINCT p.tenaga_kerja_id)',
            'keikutsertaan' => 'COUNT(*)',
            'mengikuti_uji' => "SUM(CASE WHEN p.status_sertifikasi IN ('MENGIKUTI_UJI','KOMPETEN','BELUM_KOMPETEN') THEN 1 ELSE 0 END)",
            'kompeten' => "SUM(CASE WHEN p.status_sertifikasi='KOMPETEN' THEN 1 ELSE 0 END)",
            'belum_kompeten' => "SUM(CASE WHEN p.status_sertifikasi='BELUM_KOMPETEN' THEN 1 ELSE 0 END)",
            'tidak_hadir' => "SUM(CASE WHEN p.status_sertifikasi='TIDAK_HADIR' THEN 1 ELSE 0 END)",
            'skk' => "SUM(CASE WHEN p.nomor_skk IS NOT NULL AND p.nomor_skk<>'' AND p.tanggal_terbit_skk IS NOT NULL THEN 1 ELSE 0 END)",
        ])->asArray()->one();

        if ($filter->jabatan || $filter->jenjang || $filter->skema || $filter->status) {
            $kegiatanCount = (clone $base)->select('COUNT(DISTINCT p.kegiatan_id)')->scalar();
        } else {
            $activities = KegiatanFasilitasiSertifikasi::find();
            if ($filter->tahun) { $activities->andWhere(['tahun_anggaran' => $filter->tahun]); }
            if ($filter->lsp) { $activities->andWhere(['lsp_id' => $filter->lsp]); }
            if ($filter->kegiatan) { $activities->andWhere(['id' => $filter->kegiatan]); }
            $kegiatanCount = $activities->count();
        }
        $stats['kegiatan'] = (int) $kegiatanCount;

        $tableQuery = clone $base;
        $tableQuery->select('p.*')->with(['tenagaKerja', 'kegiatan.lsp'])
            ->orderBy(['p.id' => SORT_DESC]);
        $provider = new ActiveDataProvider(['query' => $tableQuery, 'pagination' => ['pageSize' => 20], 'sort' => false]);
        $years = KegiatanFasilitasiSertifikasi::find()->select('tahun_anggaran')->distinct()
            ->orderBy(['tahun_anggaran' => SORT_DESC])->column();
        return $this->render('index', [
            'filter' => $filter, 'stats' => $stats, 'provider' => $provider,
            'years' => array_combine($years, $years) ?: [],
            'activities' => ArrayHelper::map(KegiatanFasilitasiSertifikasi::find()->orderBy(['tahun_anggaran' => SORT_DESC, 'nama_kegiatan' => SORT_ASC])->all(), 'id', 'nama_kegiatan'),
            'lsps' => ArrayHelper::map(MasterLsp::find()->orderBy('nama_lsp')->all(), 'id', 'nama_lsp'),
            'jabatanOptions' => $this->distinctOptions('jabatan_kerja'),
            'jenjangOptions' => $this->distinctOptions('jenjang_kualifikasi'),
            'skemaOptions' => $this->distinctOptions('skema_sertifikasi'),
        ]);
    }

    private function distinctOptions($column)
    {
        $values = PesertaFasilitasiSertifikasi::find()->select($column)->distinct()
            ->where(['not', [$column => null]])->andWhere(['<>', $column, ''])
            ->orderBy([$column => SORT_ASC])->column();
        return array_combine($values, $values) ?: [];
    }
}
