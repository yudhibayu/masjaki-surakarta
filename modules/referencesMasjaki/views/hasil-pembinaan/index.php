<?php

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Hasil Pembinaan';
$this->params['breadcrumbs'][] = ['label' => 'Dashboard Pembinaan', 'url' => ['/references-masjaki/pembinaan']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card"><div class="card-header"><h3><?= Html::encode($this->title) ?></h3></div><div class="card-body">
    <?= Html::beginForm(['index'], 'get', ['class' => 'row g-2 mb-3']) ?>
        <div class="col-md-3"><?= Html::textInput('tahun', $tahun, ['class' => 'form-control', 'placeholder' => 'Tahun']) ?></div>
        <div class="col-md-3"><button class="btn btn-outline-primary">Terapkan</button></div>
    <?= Html::endForm() ?>
    <?= GridView::widget(['dataProvider' => $provider, 'columns' => [
        ['class' => 'yii\grid\SerialColumn'], ['attribute' => 'kegiatan.tahun_anggaran', 'label' => 'Tahun'],
        ['attribute' => 'kegiatan.nama_kegiatan', 'label' => 'Kegiatan'],
        ['label' => 'Peserta', 'value' => function ($record) { return $record->businessEntity ? $record->businessEntity->company_name : ($record->masyarakatJasaKonstruksi ? $record->masyarakatJasaKonstruksi->nama : '—'); }],
        ['label' => 'Hasil', 'value' => function ($record) { return $record->hasil && $record->hasil->hasil_capaian ? $record->hasil->hasil_capaian : 'Belum diisi'; }],
        ['label' => 'Sertifikat', 'value' => function ($record) { return !$record->hasil || $record->hasil->memperoleh_sertifikat === null ? 'Belum diisi' : ($record->hasil->memperoleh_sertifikat ? 'Ya' : 'Tidak'); }],
        ['class' => 'yii\grid\ActionColumn', 'template' => '{edit}', 'buttons' => ['edit' => function ($url, $record) { return Html::a($record->hasil ? 'Ubah' : 'Isi Hasil', ['update', 'peserta_id' => $record->id], ['class' => 'btn btn-sm btn-outline-primary']); }]],
    ]]) ?>
</div></section>
