<?php

use app\components\TkkDateForm;
use kartik\widgets\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $record->isNewRecord ? 'Tambah Kegiatan Pembinaan' : 'Ubah Kegiatan Pembinaan';
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Pembinaan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card"><div class="card-header"><h3><?= Html::encode($this->title) ?></h3></div><div class="card-body">
    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($record, 'jenis_kegiatan_id')->dropDownList($jenisOptions, ['prompt' => 'Pilih jenis kegiatan']) ?>
        <?= $form->field($record, 'tahun_anggaran')->input('number', ['min' => 2000, 'max' => 2100]) ?>
        <?= $form->field($record, 'nama_kegiatan')->textInput(['maxlength' => true]) ?>
        <?= $form->field($record, 'tanggal_mulai', ['enableClientValidation' => false])->widget(DatePicker::class, [
            'type' => DatePicker::TYPE_COMPONENT_APPEND, 'language' => 'id',
            'options' => ['value' => TkkDateForm::display($record->tanggal_mulai), 'placeholder' => 'dd-mm-yyyy', 'maxlength' => 10, 'autocomplete' => 'off'],
            'pluginOptions' => ['format' => 'dd-mm-yyyy', 'autoclose' => true, 'todayHighlight' => true],
        ]) ?>
        <?= $form->field($record, 'tanggal_selesai', ['enableClientValidation' => false])->widget(DatePicker::class, [
            'type' => DatePicker::TYPE_COMPONENT_APPEND, 'language' => 'id',
            'options' => ['value' => TkkDateForm::display($record->tanggal_selesai), 'placeholder' => 'dd-mm-yyyy', 'maxlength' => 10, 'autocomplete' => 'off'],
            'pluginOptions' => ['format' => 'dd-mm-yyyy', 'autoclose' => true, 'todayHighlight' => true],
        ]) ?>
        <?= $form->field($record, 'lokasi')->textInput(['maxlength' => true]) ?>
        <?= $form->field($record, 'penyelenggara')->textInput(['maxlength' => true]) ?>
        <?= $form->field($record, 'keterangan')->textarea(['rows' => 4]) ?>
        <?= Html::submitButton('Simpan Draft', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Batal', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
    <?php ActiveForm::end(); ?>
</div></section>
