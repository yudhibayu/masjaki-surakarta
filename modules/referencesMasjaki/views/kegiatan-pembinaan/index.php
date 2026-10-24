<?php

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Kegiatan Pembinaan';
$this->params['breadcrumbs'][] = ['label' => 'Dashboard Pembinaan', 'url' => ['/references-masjaki/pembinaan']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card"><div class="card-header d-flex justify-content-between align-items-center"><h3 class="mb-0"><?= Html::encode($this->title) ?></h3><?= Html::a('Tambah Kegiatan', ['create'], ['class' => 'btn btn-primary']) ?></div><div class="card-body">
    <?= Html::beginForm(['index'], 'get', ['class' => 'row g-2 mb-3']) ?>
        <div class="col-md-2"><?= Html::textInput('tahun', $tahun, ['class' => 'form-control', 'placeholder' => 'Tahun', 'inputmode' => 'numeric']) ?></div>
        <div class="col-md-4"><?= Html::dropDownList('jenis', $jenis, $jenisOptions, ['class' => 'form-select', 'prompt' => 'Semua jenis']) ?></div>
        <div class="col-md-3"><?= Html::dropDownList('publikasi', $publikasi, ['' => 'Semua status', '1' => 'Dipublikasikan', '0' => 'Draft'], ['class' => 'form-select']) ?></div>
        <div class="col-md-3"><button class="btn btn-outline-primary">Terapkan</button></div>
    <?= Html::endForm() ?>
    <?= GridView::widget(['dataProvider' => $provider, 'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'tahun_anggaran', ['attribute' => 'jenisKegiatan.nama', 'label' => 'Jenis Kegiatan'], 'nama_kegiatan',
        ['attribute' => 'tanggal_mulai', 'format' => ['date', 'php:d-m-Y']],
        ['attribute' => 'tanggal_selesai', 'format' => ['date', 'php:d-m-Y']],
        ['attribute' => 'is_published', 'label' => 'Publikasi', 'value' => function ($record) { return $record->is_published ? 'Terbit' : 'Draft'; }],
        ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {update} {publish} {delete}', 'buttons' => [
            'publish' => function ($url, $record) { return Html::a($record->is_published ? 'Cabut' : 'Terbitkan', ['publish', 'id' => $record->id], ['class' => 'btn btn-sm btn-outline-primary', 'data-method' => 'post', 'data-confirm' => $record->is_published ? 'Cabut publikasi kegiatan?' : 'Terbitkan kegiatan pada halaman publik?']); },
            'delete' => function ($url, $record) { return Html::a('Arsipkan', ['delete', 'id' => $record->id], ['class' => 'btn btn-sm btn-outline-danger', 'data-method' => 'post', 'data-confirm' => 'Arsipkan kegiatan ini? Data peserta tetap tersimpan.']); },
        ]],
    ]]) ?>
</div></section>
