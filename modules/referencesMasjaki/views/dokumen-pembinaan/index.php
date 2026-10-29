<?php

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Dokumen Pendukung Pembinaan';
$this->params['breadcrumbs'][] = ['label' => 'Dashboard Pembinaan', 'url' => ['/references-masjaki/pembinaan']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card"><div class="card-header"><h3><?= Html::encode($this->title) ?></h3></div><div class="card-body">
    <?php if ($kegiatan_id !== null && ctype_digit((string) $kegiatan_id) && (int) $kegiatan_id > 0): ?>
        <p><?= Html::a('Unggah Dokumen', ['upload', 'kegiatan_id' => (int) $kegiatan_id], ['class' => 'btn btn-success']) ?></p>
    <?php endif; ?>
    <?= Html::beginForm(['index'], 'get', ['class' => 'row g-2 mb-3']) ?>
        <div class="col-md-6">
            <?= Html::label('Pilih kegiatan', 'filter-kegiatan', ['class' => 'form-label']) ?>
            <?= Html::dropDownList('kegiatan_id', $kegiatan_id, ['' => 'Semua Kegiatan'] + $kegiatanOptions, [
                'id' => 'filter-kegiatan',
                'class' => 'form-select',
            ]) ?>
        </div>
        <div class="col-md-3 align-self-end"><button class="btn btn-outline-primary">Terapkan</button></div>
    <?= Html::endForm() ?>
    <?= GridView::widget(['dataProvider' => $provider, 'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'kegiatan.nama_kegiatan', 'label' => 'Kegiatan'],
        ['attribute' => 'jenis_dokumen', 'label' => 'Jenis', 'value' => function ($record) { return isset($record::JENIS[$record->jenis_dokumen]) ? $record::JENIS[$record->jenis_dokumen] : $record->jenis_dokumen; }],
        'nama_file', ['attribute' => 'file_size', 'label' => 'Ukuran', 'value' => function ($record) { return Yii::$app->formatter->asShortSize($record->file_size); }],
        ['class' => 'yii\grid\ActionColumn', 'template' => '{download} {delete}', 'buttons' => [
            'download' => function ($url, $record) { return Html::a('Unduh', ['download', 'id' => $record->id], ['class' => 'btn btn-sm btn-outline-primary']); },
            'delete' => function ($url, $record) { return Html::a('Hapus', ['delete', 'id' => $record->id], ['class' => 'btn btn-sm btn-outline-danger', 'data-method' => 'post', 'data-confirm' => 'Hapus dokumen ini?']); },
        ]],
    ]]) ?>
    <?php if (!$kegiatan_id): ?><p class="text-muted">Untuk mengunggah dokumen, buka kegiatan lalu pilih “Dokumen Pendukung”.</p><?php endif; ?>
</div></section>
