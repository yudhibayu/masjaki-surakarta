<?php

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Tenaga Kerja Konstruksi';
$this->params['breadcrumbs'][] = ['label' => 'Rekap Sertifikasi TKK', 'url' => ['/references-masjaki/tkk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card"><div class="card-header"><h3><?= Html::encode($this->title) ?></h3></div><div class="card-body">
    <p><?= Html::a('Tambah Tenaga Kerja', ['create'], ['class' => 'btn btn-success']) ?></p>
    <?= Html::beginForm(['index'], 'get', ['class' => 'row g-2 mb-3']) ?>
        <div class="col-auto"><?= Html::textInput('q', $q, ['class' => 'form-control', 'placeholder' => 'Cari nama atau NIK']) ?></div>
        <div class="col-auto"><?= Html::submitButton('Cari', ['class' => 'btn btn-primary']) ?></div>
    <?= Html::endForm() ?>
    <div class="table-responsive"><?= GridView::widget([
        'dataProvider' => $provider,
        'tableOptions' => ['class' => 'table table-striped table-bordered'],
        'columns' => [
            'nama_lengkap',
            ['label' => 'NIK', 'value' => function ($record) { return $record->nikTersamar; }],
            'asal_instansi',
            ['label' => 'Aksi', 'format' => 'raw', 'value' => function ($record) {
                return Html::a('Detail', ['view', 'id' => $record->id], ['class' => 'btn btn-sm btn-outline-primary']) . ' '
                    . Html::a('Edit', ['update', 'id' => $record->id], ['class' => 'btn btn-sm btn-primary']);
            }],
        ],
    ]) ?></div>
</div></section>
