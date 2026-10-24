<?php

use app\modules\monitoring\models\TertibChecklist;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

$this->title = $model->isNewRecord ? 'Tambah Checklist' : 'Ubah Checklist';
$this->params['breadcrumbs'][] = ['label' => 'Rekap Pengawasan Tertib', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Detail Pengawasan', 'url' => ['view', 'id' => $parent->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tertib-checklist-form">
    <h1 class="h3 mb-3"><?= Html::encode($this->title) ?></h1>
    <div class="card"><div class="card-body">
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'nama_persyaratan')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'status_pemeriksaan')->dropDownList(TertibChecklist::statusOptions(), ['prompt' => 'Pilih hasil']) ?>
        <?= $form->field($model, 'catatan')->textarea(['rows' => 4]) ?>
        <?= Html::submitButton('Simpan', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Batal', ['view', 'id' => $parent->id], ['class' => 'btn btn-outline-secondary']) ?>
        <?php ActiveForm::end(); ?>
    </div></div>
</div>
