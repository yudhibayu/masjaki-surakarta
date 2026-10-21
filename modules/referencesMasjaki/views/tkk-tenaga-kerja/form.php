<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $record->isNewRecord ? 'Tambah Tenaga Kerja' : 'Edit Tenaga Kerja';
$this->params['breadcrumbs'][] = ['label' => 'Tenaga Kerja Konstruksi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card"><div class="card-header"><h3><?= Html::encode($this->title) ?></h3></div><div class="card-body">
    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($record, 'nik')->textInput(['maxlength' => 16, 'inputmode' => 'numeric', 'autocomplete' => 'off']) ?>
        <?= $form->field($record, 'nama_lengkap')->textInput(['maxlength' => true]) ?>
        <?= $form->field($record, 'jenis_kelamin')->dropDownList(['L' => 'Laki-laki', 'P' => 'Perempuan'], ['prompt' => 'Pilih']) ?>
        <?= $form->field($record, 'tanggal_lahir')->input('date') ?>
        <?= $form->field($record, 'alamat')->textarea(['rows' => 3]) ?>
        <?= $form->field($record, 'nomor_telepon')->textInput(['maxlength' => true]) ?>
        <?= $form->field($record, 'pendidikan_terakhir')->textInput(['maxlength' => true]) ?>
        <?= $form->field($record, 'asal_instansi')->textInput(['maxlength' => true]) ?>
        <?= Html::submitButton('Simpan', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Batal', ['index'], ['class' => 'btn btn-secondary']) ?>
    <?php ActiveForm::end(); ?>
</div></section>
