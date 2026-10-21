<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $record->isNewRecord ? 'Tambah Kegiatan Sertifikasi' : 'Edit Kegiatan Sertifikasi';
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Fasilitasi Sertifikasi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card"><div class="card-header"><h3><?= Html::encode($this->title) ?></h3></div><div class="card-body">
    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($record, 'nama_kegiatan')->textInput(['maxlength' => true]) ?>
        <?= $form->field($record, 'tahun_anggaran')->input('number', ['min' => 2000, 'max' => 2100]) ?>
        <?= $form->field($record, 'tanggal_mulai')->input('date') ?>
        <?= $form->field($record, 'tanggal_selesai')->input('date') ?>
        <?= $form->field($record, 'lokasi_kegiatan')->textInput(['maxlength' => true]) ?>
        <?= $form->field($record, 'lsp_id')->dropDownList($lsps, ['prompt' => 'Pilih LSP']) ?>
        <?= $form->field($record, 'keterangan')->textarea(['rows' => 3]) ?>
        <?= Html::submitButton('Simpan', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Batal', ['index'], ['class' => 'btn btn-secondary']) ?>
    <?php ActiveForm::end(); ?>
</div></section>
