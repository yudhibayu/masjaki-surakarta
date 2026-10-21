<?php

use app\modules\referencesMasjaki\models\PesertaFasilitasiSertifikasi;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Rekap Sertifikasi Tenaga Kerja Konstruksi';
$this->params['breadcrumbs'][] = $this->title;
$cards = [
    'orang' => 'Tenaga Kerja Unik', 'keikutsertaan' => 'Keikutsertaan',
    'mengikuti_uji' => 'Mengikuti Uji', 'kompeten' => 'Kompeten',
    'belum_kompeten' => 'Belum Kompeten', 'tidak_hadir' => 'Tidak Hadir',
    'skk' => 'SKK Terbit', 'kegiatan' => 'Kegiatan',
];
?>
<section class="card"><div class="card-header"><h3><?= Html::encode($this->title) ?></h3></div><div class="card-body">
    <p>
        <?= Html::a('Tenaga Kerja', ['/references-masjaki/tkk-tenaga-kerja'], ['class' => 'btn btn-outline-primary']) ?>
        <?= Html::a('Kegiatan', ['/references-masjaki/tkk-kegiatan'], ['class' => 'btn btn-outline-primary']) ?>
        <?= Html::a('Keikutsertaan', ['/references-masjaki/tkk-peserta'], ['class' => 'btn btn-outline-primary']) ?>
        <?= Html::a('Master LSP', ['/references-masjaki/tkk-lsp'], ['class' => 'btn btn-outline-primary']) ?>
    </p>
    <?= Html::beginForm(['index'], 'get', ['class' => 'row g-2 mb-4']) ?>
        <div class="col-md-3"><?= Html::label('Tahun Anggaran', 'filter-tahun', ['class' => 'form-label']) ?><?= Html::dropDownList('tahun', $filter->tahun, ['' => 'Semua Tahun'] + $years, ['id' => 'filter-tahun', 'class' => 'form-select']) ?></div>
        <div class="col-md-3"><?= Html::label('Kegiatan', 'filter-kegiatan', ['class' => 'form-label']) ?><?= Html::dropDownList('kegiatan', $filter->kegiatan, ['' => 'Semua Kegiatan'] + $activities, ['id' => 'filter-kegiatan', 'class' => 'form-select']) ?></div>
        <div class="col-md-3"><?= Html::label('LSP', 'filter-lsp', ['class' => 'form-label']) ?><?= Html::dropDownList('lsp', $filter->lsp, ['' => 'Semua LSP'] + $lsps, ['id' => 'filter-lsp', 'class' => 'form-select']) ?></div>
        <div class="col-md-3"><?= Html::label('Jabatan Kerja', 'filter-jabatan', ['class' => 'form-label']) ?><?= Html::dropDownList('jabatan', $filter->jabatan, ['' => 'Semua Jabatan'] + $jabatanOptions, ['id' => 'filter-jabatan', 'class' => 'form-select']) ?></div>
        <div class="col-md-3"><?= Html::label('Jenjang', 'filter-jenjang', ['class' => 'form-label']) ?><?= Html::dropDownList('jenjang', $filter->jenjang, ['' => 'Semua Jenjang'] + $jenjangOptions, ['id' => 'filter-jenjang', 'class' => 'form-select']) ?></div>
        <div class="col-md-3"><?= Html::label('Skema', 'filter-skema', ['class' => 'form-label']) ?><?= Html::dropDownList('skema', $filter->skema, ['' => 'Semua Skema'] + $skemaOptions, ['id' => 'filter-skema', 'class' => 'form-select']) ?></div>
        <div class="col-md-3"><?= Html::label('Status', 'filter-status', ['class' => 'form-label']) ?><?= Html::dropDownList('status', $filter->status, ['' => 'Semua Status'] + PesertaFasilitasiSertifikasi::STATUS, ['id' => 'filter-status', 'class' => 'form-select']) ?></div>
        <div class="col-md-3 align-self-end"><?= Html::submitButton('Terapkan Filter', ['class' => 'btn btn-primary']) ?> <?= Html::a('Reset', ['index'], ['class' => 'btn btn-secondary']) ?></div>
    <?= Html::endForm() ?>
    <div class="row g-3 mb-4">
        <?php foreach ($cards as $key => $label): ?>
            <div class="col-6 col-md-3"><div class="card h-100 border"><div class="card-body">
                <div class="text-muted"><?= Html::encode($label) ?></div>
                <div class="fs-3 fw-bold" data-tkk-stat="<?= Html::encode($key) ?>"><?= Yii::$app->formatter->asInteger((int) ($stats[$key] ?? 0)) ?></div>
            </div></div></div>
        <?php endforeach; ?>
    </div>
    <h4>Data Keikutsertaan</h4>
    <div class="table-responsive"><?= GridView::widget(['dataProvider' => $provider, 'tableOptions' => ['class' => 'table table-striped table-bordered'], 'columns' => [
        ['label' => 'Tenaga Kerja', 'value' => function ($record) { return $record->tenagaKerja->nama_lengkap; }],
        ['label' => 'Tahun', 'value' => function ($record) { return $record->kegiatan->tahun_anggaran; }],
        ['label' => 'Kegiatan', 'value' => function ($record) { return $record->kegiatan->nama_kegiatan; }],
        'jabatan_kerja', 'skema_sertifikasi', 'status_sertifikasi',
        ['label' => 'SKK', 'value' => function ($record) { return $record->nomor_skk && $record->tanggal_terbit_skk ? 'Terbit' : 'Belum terbit'; }],
        ['label' => 'Aksi', 'format' => 'raw', 'value' => function ($record) {
            return Html::a('Detail', ['/references-masjaki/tkk-peserta/view', 'id' => $record->id]);
        }],
    ]]) ?></div>
</div></section>
