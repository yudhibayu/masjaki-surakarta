<?php

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Master Jenis Kegiatan Pembinaan';
$this->params['breadcrumbs'][] = ['label' => 'Dashboard Pembinaan', 'url' => ['/references-masjaki/pembinaan']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card"><div class="card-header d-flex justify-content-between align-items-center"><h3 class="mb-0"><?= Html::encode($this->title) ?></h3><?= Html::a('Tambah Jenis', ['create'], ['class' => 'btn btn-primary']) ?></div><div class="card-body">
    <?= GridView::widget(['dataProvider' => $provider, 'columns' => [
        ['class' => 'yii\grid\SerialColumn'], 'nama',
        ['attribute' => 'is_active', 'label' => 'Status', 'value' => function ($record) { return $record->is_active ? 'Aktif' : 'Nonaktif'; }],
        ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {toggle} {delete}', 'buttons' => [
            'toggle' => function ($url, $record) { return Html::a($record->is_active ? 'Nonaktifkan' : 'Aktifkan', ['toggle-active', 'id' => $record->id], ['class' => 'btn btn-sm btn-outline-secondary', 'data-method' => 'post', 'data-confirm' => 'Ubah status jenis kegiatan?']); },
            'delete' => function ($url, $record) { return Html::a('Arsipkan', ['delete', 'id' => $record->id], ['class' => 'btn btn-sm btn-outline-danger', 'data-method' => 'post', 'data-confirm' => 'Arsipkan jenis ini? Kegiatan yang sudah tercatat tetap tersimpan.']); },
        ]],
    ]]) ?>
</div></section>
