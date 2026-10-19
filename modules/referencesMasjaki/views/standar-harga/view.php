<?php

use yii\helpers\Html;

$this->title = $record->master->nama . ' ' . $record->tahun;
$this->params['breadcrumbs'][] = ['label' => 'Peraturan Jasa Konstruksi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header"><h3><?= Html::encode($this->title) ?></h3></div>
    <div class="card-body">
        <p>Status: <strong><?= $record->is_public ? 'Publik' : 'Draft' ?></strong></p>
        <p>Master: <?= Html::encode($record->master->nama) ?><?= $record->master->is_active ? '' : ' (nonaktif)' ?></p>
        <p>File: <?= Html::encode($record->nama_file) ?></p>
        <?php if ($record->mime_type === 'application/pdf'): ?>
            <?= Html::a('Lihat', ['lihat', 'id' => $record->id], ['target' => '_blank', 'rel' => 'noopener', 'class' => 'btn btn-outline-primary']) ?>
        <?php endif; ?>
        <?= Html::a('Unduh', ['unduh', 'id' => $record->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Edit', ['update', 'id' => $record->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Kembali', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>
</section>
