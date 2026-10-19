<?php

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Master Produk Hukum';
$this->params['breadcrumbs'][] = ['label' => 'Peraturan Jasa Konstruksi', 'url' => ['/references-masjaki/standar-harga']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header"><h3><?= Html::encode($this->title) ?></h3></div>
    <div class="card-body">
        <p>
            <?= Html::a('Tambah Master', ['create'], ['class' => 'btn btn-success']) ?>
            <?= Html::a('Kembali ke Dokumen', ['/references-masjaki/standar-harga'], ['class' => 'btn btn-secondary']) ?>
        </p>
        <?= Html::beginForm(['index'], 'get', ['class' => 'row g-2 mb-3']) ?>
            <div class="col-auto"><?= Html::textInput('nama', $nama, ['class' => 'form-control', 'placeholder' => 'Cari nama']) ?></div>
            <div class="col-auto"><?= Html::submitButton('Cari', ['class' => 'btn btn-primary']) ?></div>
        <?= Html::endForm() ?>
        <?= GridView::widget([
            'dataProvider' => $provider,
            'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover'],
            'columns' => [
                'nama',
                ['label' => 'Status', 'value' => function ($record) { return $record->is_active ? 'Aktif' : 'Nonaktif'; }],
                'updated_at:datetime',
                ['label' => 'Aksi', 'format' => 'raw', 'value' => function ($record) {
                    return Html::a('Edit', ['update', 'id' => $record->id], ['class' => 'btn btn-sm btn-primary']) . ' '
                        . Html::beginForm(['toggle', 'id' => $record->id], 'post', ['style' => 'display:inline'])
                        . Html::submitButton($record->is_active ? 'Nonaktifkan' : 'Aktifkan', ['class' => 'btn btn-sm btn-outline-secondary'])
                        . Html::endForm();
                }],
            ],
        ]) ?>
    </div>
</section>
