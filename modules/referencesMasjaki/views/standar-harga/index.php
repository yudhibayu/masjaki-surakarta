<?php

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Peraturan Jasa Konstruksi';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header"><h3><?= Html::encode($this->title) ?></h3></div>
    <div class="card-body">
        <p>
            <?= Html::a('Tambah Dokumen', ['create'], ['class' => 'btn btn-success']) ?>
            <?= Html::a('Kelola Master Produk Hukum', ['/references-masjaki/master-produk-hukum'], ['class' => 'btn btn-outline-primary']) ?>
        </p>
        <?= Html::beginForm(['index'], 'get', ['class' => 'row g-2 mb-3']) ?>
            <div class="col-auto"><?= Html::input('number', 'tahun', $tahun, ['class' => 'form-control', 'placeholder' => 'Tahun', 'min' => 2000, 'max' => 2100]) ?></div>
            <div class="col-auto"><?= Html::dropDownList('master', $master, ['' => 'Semua Produk Hukum'] + $masters, ['class' => 'form-select']) ?></div>
            <div class="col-auto"><?= Html::dropDownList('is_public', $is_public, ['' => 'Semua Status', '0' => 'Draft', '1' => 'Publik'], ['class' => 'form-select']) ?></div>
            <div class="col-auto"><?= Html::submitButton('Cari', ['class' => 'btn btn-primary']) ?></div>
        <?= Html::endForm() ?>
        <div class="table-responsive">
            <?= GridView::widget([
                'dataProvider' => $provider,
                'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover'],
                'columns' => [
                    ['label' => 'Nama Produk Hukum', 'value' => function ($record) { return $record->master->nama; }],
                    'tahun',
                    ['label' => 'Status', 'value' => function ($record) { return $record->is_public ? 'Publik' : 'Draft'; }],
                    ['label' => 'File', 'format' => 'raw', 'value' => function ($record) { return Html::a(Html::encode($record->nama_file), ['unduh', 'id' => $record->id]); }],
                    'updated_at:datetime',
                    ['label' => 'Aksi', 'format' => 'raw', 'value' => function ($record) {
                        return Html::a('Detail', ['view', 'id' => $record->id], ['class' => 'btn btn-sm btn-outline-primary']) . ' '
                            . Html::a('Edit', ['update', 'id' => $record->id], ['class' => 'btn btn-sm btn-primary']) . ' '
                            . Html::beginForm(['delete', 'id' => $record->id], 'post', ['style' => 'display:inline'])
                            . Html::submitButton('Hapus', ['class' => 'btn btn-sm btn-danger', 'data-confirm' => 'Arsipkan dokumen ini?'])
                            . Html::endForm();
                    }],
                ],
            ]) ?>
        </div>
    </div>
</section>
