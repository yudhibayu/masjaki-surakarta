<?php

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Master LSP';
$this->params['breadcrumbs'][] = ['label' => 'Rekap Sertifikasi TKK', 'url' => ['/references-masjaki/tkk']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card"><div class="card-header"><h3><?= Html::encode($this->title) ?></h3></div><div class="card-body">
    <p><?= Html::a('Tambah LSP', ['create'], ['class' => 'btn btn-success']) ?></p>
    <?= Html::beginForm(['index'], 'get', ['class' => 'row g-2 mb-3']) ?>
        <div class="col-auto"><?= Html::textInput('q', $q, ['class' => 'form-control', 'placeholder' => 'Cari LSP']) ?></div>
        <div class="col-auto"><?= Html::submitButton('Cari', ['class' => 'btn btn-primary']) ?></div>
    <?= Html::endForm() ?>
    <?= GridView::widget(['dataProvider' => $provider, 'tableOptions' => ['class' => 'table table-striped table-bordered'], 'columns' => [
        'nama_lsp',
        ['label' => 'Status', 'value' => function ($record) { return $record->is_active ? 'Aktif' : 'Nonaktif'; }],
        ['label' => 'Aksi', 'format' => 'raw', 'value' => function ($record) {
            return Html::a('Edit', ['update', 'id' => $record->id], ['class' => 'btn btn-sm btn-primary']) . ' '
                . Html::beginForm(['toggle', 'id' => $record->id], 'post', ['style' => 'display:inline'])
                . Html::submitButton($record->is_active ? 'Nonaktifkan' : 'Aktifkan', ['class' => 'btn btn-sm btn-outline-secondary'])
                . Html::endForm();
        }],
    ]]) ?>
</div></section>
