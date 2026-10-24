<?php

use app\modules\referencesMasjaki\models\MasyarakatJasaKonstruksi;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Data Masyarakat Jasa Konstruksi';
$this->params['breadcrumbs'][] = ['label' => 'Dashboard Pembinaan', 'url' => ['/references-masjaki/pembinaan']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card"><div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="mb-0"><?= Html::encode($this->title) ?></h3>
    <?= Html::a('Tambah Data', ['create'], ['class' => 'btn btn-primary']) ?>
</div><div class="card-body">
    <?= Html::beginForm(['index'], 'get', ['class' => 'row g-2 mb-3']) ?>
        <div class="col-md-6"><?= Html::textInput('q', $q, ['class' => 'form-control', 'placeholder' => 'Cari nama, instansi, atau NIK']) ?></div>
        <div class="col-md-3"><?= Html::dropDownList('status', $status, ['' => 'Semua status', '1' => 'Aktif', '0' => 'Nonaktif'], ['class' => 'form-select']) ?></div>
        <div class="col-md-3"><button class="btn btn-outline-primary">Cari</button></div>
    <?= Html::endForm() ?>
    <?= GridView::widget([
        'dataProvider' => $provider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'jenis_subjek', 'label' => 'Jenis'],
            'nama', 'instansi', 'jabatan',
            ['attribute' => 'nik', 'label' => 'NIK', 'value' => function ($record) { return $record->jenis_subjek === MasyarakatJasaKonstruksi::PERSEORANGAN ? ($record->nik ?: '—') : '—'; }],
            ['attribute' => 'is_active', 'label' => 'Status', 'value' => function ($record) { return $record->is_active ? 'Aktif' : 'Nonaktif'; }],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {toggle} {delete}', 'buttons' => [
                'toggle' => function ($url, $record) {
                    return Html::a($record->is_active ? 'Nonaktifkan' : 'Aktifkan', ['toggle-active', 'id' => $record->id], [
                        'class' => 'btn btn-sm btn-outline-secondary', 'data-method' => 'post',
                        'data-confirm' => 'Ubah status data ini?', 'title' => 'Ubah status',
                    ]);
                },
                'delete' => function ($url, $record) {
                    return Html::a('Arsipkan', ['delete', 'id' => $record->id], [
                        'class' => 'btn btn-sm btn-outline-danger', 'data-method' => 'post',
                        'data-confirm' => 'Arsipkan data ini? Riwayat pembinaan akan tetap tersimpan.', 'title' => 'Arsipkan',
                    ]);
                },
            ]],
        ],
    ]) ?>
</div></section>
