<?php

use yii\grid\GridView;
use yii\helpers\Html;
use app\modules\referencesMasjaki\models\MasyarakatJasaKonstruksi;

$this->title = 'Peserta Pembinaan';
$this->params['breadcrumbs'][] = ['label' => 'Dashboard Pembinaan', 'url' => ['/references-masjaki/pembinaan']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card"><div class="card-header d-flex justify-content-between align-items-center"><h3 class="mb-0"><?= Html::encode($this->title) ?></h3><?= Html::a('Tambah Peserta', ['create'], ['class' => 'btn btn-primary']) ?></div><div class="card-body">
    <?= Html::beginForm(['index'], 'get', ['class' => 'row g-2 mb-3']) ?>
        <div class="col-md-2"><?= Html::textInput('tahun', $tahun, ['class' => 'form-control', 'placeholder' => 'Tahun']) ?></div>
        <div class="col-md-7"><?= Html::dropDownList('kegiatan', $kegiatan, $kegiatanOptions, ['class' => 'form-select', 'prompt' => 'Semua kegiatan']) ?></div>
        <div class="col-md-3"><button class="btn btn-outline-primary">Terapkan</button></div>
    <?= Html::endForm() ?>
    <?= GridView::widget(['dataProvider' => $provider, 'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'kegiatan.tahun_anggaran', 'label' => 'Tahun'], ['attribute' => 'kegiatan.nama_kegiatan', 'label' => 'Kegiatan'],
        ['label' => 'Jenis Peserta', 'value' => function ($record) {
            if ($record->id_business_entity) {
                return 'BUJK';
            }
            return $record->masyarakatJasaKonstruksi && $record->masyarakatJasaKonstruksi->jenis_subjek === MasyarakatJasaKonstruksi::KELOMPOK
                ? 'Kelompok'
                : 'Masyarakat';
        }],
        ['label' => 'Nama Peserta', 'value' => function ($record) { return $record->businessEntity ? $record->businessEntity->company_name : ($record->masyarakatJasaKonstruksi ? $record->masyarakatJasaKonstruksi->nama : '—'); }],
        ['label' => 'Hasil', 'value' => function ($record) { return $record->hasil && $record->hasil->hasil_capaian ? $record->hasil->hasil_capaian : 'Belum diisi'; }],
        ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {hasil} {delete}', 'buttons' => [
            'hasil' => function ($url, $record) { return Html::a($record->hasil ? 'Ubah Hasil' : 'Isi Hasil', ['/references-masjaki/hasil-pembinaan/update', 'peserta_id' => $record->id], ['class' => 'btn btn-sm btn-outline-primary']); },
            'delete' => function ($url, $record) { return Html::a('Arsipkan', ['delete', 'id' => $record->id], ['class' => 'btn btn-sm btn-outline-danger', 'data-method' => 'post', 'data-confirm' => 'Arsipkan keikutsertaan peserta ini?']); },
        ]],
    ]]) ?>
</div></section>
