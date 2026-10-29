<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

$this->title = 'Unggah Dokumen Pengawasan';
$this->params['breadcrumbs'][] = ['label' => 'Rekap Pengawasan Tertib', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Detail Pengawasan', 'url' => ['view', 'id' => $parent->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tertib-document-upload">
    <h1 class="h3 mb-3"><?= Html::encode($this->title) ?></h1>
    <div class="card"><div class="card-body">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <?= $form->field($model, 'jenis_dokumen')->textInput(['maxlength' => true, 'placeholder' => 'Contoh: Berita acara pengawasan']) ?>
        <?= $form->field($model, 'upload')->fileInput(['accept' => '.pdf,.jpg,.jpeg,.png']) ?>
        <p class="small text-muted">PDF atau gambar JPG/PNG, maksimal 10 MB. Berkas disimpan privat dan tidak ditampilkan di web umum.</p>
        <?= Html::submitButton('Unggah', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Batal', ['view', 'id' => $parent->id], ['class' => 'btn btn-outline-secondary']) ?>
        <?php ActiveForm::end(); ?>
    </div></div>
</div>
