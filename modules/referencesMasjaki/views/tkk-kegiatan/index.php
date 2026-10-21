<?php

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Kegiatan Fasilitasi Sertifikasi';
$this->params['breadcrumbs'][] = ['label' => 'Rekap Sertifikasi TKK', 'url' => ['/references-masjaki/tkk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card"><div class="card-header"><h3><?= Html::encode($this->title) ?></h3></div><div class="card-body">
    <p><?= Html::a('Tambah Kegiatan', ['create'], ['class' => 'btn btn-success']) ?></p>
    <?= Html::beginForm(['index'], 'get', ['class' => 'row g-2 mb-3']) ?>
        <div class="col-auto"><?= Html::input('number', 'tahun', $tahun, ['class' => 'form-control', 'placeholder' => 'Tahun Anggaran', 'min' => 2000, 'max' => 2100]) ?></div>
        <div class="col-auto"><?= Html::dropDownList('lsp', $lsp, ['' => 'Semua LSP'] + $lsps, ['class' => 'form-select']) ?></div>
        <div class="col-auto"><?= Html::submitButton('Cari', ['class' => 'btn btn-primary']) ?></div>
    <?= Html::endForm() ?>
    <div class="table-responsive"><?= GridView::widget(['dataProvider' => $provider, 'tableOptions' => ['class' => 'table table-striped table-bordered'], 'columns' => [
        'tahun_anggaran', 'nama_kegiatan', 'tanggal_mulai:date', 'tanggal_selesai:date',
        ['label' => 'LSP', 'value' => function ($record) { return $record->lsp->nama_lsp; }],
        ['label' => 'Peserta', 'value' => function ($record) { return count($record->peserta); }],
        ['label' => 'Publikasi', 'value' => function ($record) { return $record->is_public ? 'Tampil di web umum' : 'Belum diterbitkan'; }],
        ['label' => 'Aksi', 'format' => 'raw', 'value' => function ($record) {
            return Html::a('Detail', ['view', 'id' => $record->id], ['class' => 'btn btn-sm btn-outline-primary']) . ' '
                . Html::a('Edit', ['update', 'id' => $record->id], ['class' => 'btn btn-sm btn-primary']);
        }],
    ]]) ?></div>
</div></section>
