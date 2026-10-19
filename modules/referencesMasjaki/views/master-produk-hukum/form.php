<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $record->isNewRecord ? 'Tambah Master Produk Hukum' : 'Edit Master Produk Hukum';
$this->params['breadcrumbs'][] = ['label' => 'Master Produk Hukum', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header"><h3><?= Html::encode($this->title) ?></h3></div>
    <div class="card-body">
        <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($record, 'nama')->textInput(['maxlength' => true]) ?>
            <?= $form->field($record, 'is_active')->checkbox(['label' => 'Aktif']) ?>
            <?= Html::submitButton('Simpan', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Batal', ['index'], ['class' => 'btn btn-secondary']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</section>
