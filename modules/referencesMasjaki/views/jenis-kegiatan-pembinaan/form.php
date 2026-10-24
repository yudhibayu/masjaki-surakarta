<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $record->isNewRecord ? 'Tambah Jenis Kegiatan' : 'Ubah Jenis Kegiatan';
$this->params['breadcrumbs'][] = ['label' => 'Master Jenis Kegiatan Pembinaan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card"><div class="card-header"><h3><?= Html::encode($this->title) ?></h3></div><div class="card-body">
    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($record, 'nama')->textInput(['maxlength' => true]) ?>
        <?= $form->field($record, 'is_active')->checkbox() ?>
        <?= Html::submitButton('Simpan', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Batal', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
    <?php ActiveForm::end(); ?>
</div></section>
