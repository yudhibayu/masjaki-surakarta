<?php

use app\modules\referencesMasjaki\models\PesertaFasilitasiSertifikasi;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Keikutsertaan Sertifikasi TKK';
$this->params['breadcrumbs'][] = ['label' => 'Rekap Sertifikasi TKK', 'url' => ['/references-masjaki/tkk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card"><div class="card-header"><h3><?= Html::encode($this->title) ?></h3></div><div class="card-body">
    <p><?= Html::a('Tambah Keikutsertaan', ['create'], ['class' => 'btn btn-success']) ?></p>
    <?= Html::beginForm(['index'], 'get', ['class' => 'row g-2 mb-3']) ?>
        <div class="col-auto"><?= Html::input('number', 'tahun', $filter->tahun, ['class' => 'form-control', 'placeholder' => 'Tahun Anggaran']) ?></div>
        <div class="col-auto"><?= Html::dropDownList('kegiatan', $filter->kegiatan, ['' => 'Semua Kegiatan'] + $activities, ['class' => 'form-select']) ?></div>
        <div class="col-auto"><?= Html::dropDownList('status', $filter->status, ['' => 'Semua Status'] + PesertaFasilitasiSertifikasi::STATUS, ['class' => 'form-select']) ?></div>
        <div class="col-auto"><?= Html::textInput('jabatan', $filter->jabatan, ['class' => 'form-control', 'placeholder' => 'Jabatan Kerja']) ?></div>
        <div class="col-auto"><?= Html::textInput('skema', $filter->skema, ['class' => 'form-control', 'placeholder' => 'Skema Sertifikasi']) ?></div>
        <div class="col-auto"><?= Html::submitButton('Filter', ['class' => 'btn btn-primary']) ?></div>
    <?= Html::endForm() ?>
    <div class="table-responsive"><?= GridView::widget(['dataProvider' => $provider, 'tableOptions' => ['class' => 'table table-striped table-bordered'], 'columns' => [
        ['label' => 'Tenaga Kerja', 'value' => function ($record) { return $record->tenagaKerja->nama_lengkap; }],
        ['label' => 'Tahun', 'value' => function ($record) { return $record->kegiatan->tahun_anggaran; }],
        ['label' => 'Kegiatan', 'value' => function ($record) { return $record->kegiatan->nama_kegiatan; }],
        'jabatan_kerja', 'skema_sertifikasi', 'status_sertifikasi',
        ['label' => 'SKK', 'value' => function ($record) { return $record->nomor_skk && $record->tanggal_terbit_skk ? 'Terbit' : 'Belum terbit'; }],
        ['label' => 'Aksi', 'format' => 'raw', 'value' => function ($record) {
            return Html::a('Detail', ['view', 'id' => $record->id], ['class' => 'btn btn-sm btn-outline-primary']) . ' '
                . Html::a('Edit', ['update', 'id' => $record->id], ['class' => 'btn btn-sm btn-primary']);
        }],
    ]]) ?></div>
</div></section>
