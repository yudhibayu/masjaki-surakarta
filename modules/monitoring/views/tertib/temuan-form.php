<?php

use app\modules\monitoring\models\TertibTemuan;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

$this->title = $model->isNewRecord ? 'Tambah Temuan' : 'Ubah Temuan';
$this->params['breadcrumbs'][] = ['label' => 'Rekap Pengawasan Tertib', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Detail Pengawasan', 'url' => ['view', 'id' => $parent->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tertib-temuan-form">
    <h1 class="h3 mb-3"><?= Html::encode($this->title) ?></h1>
    <div class="card"><div class="card-body">
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'kategori')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'uraian')->textarea(['rows' => 5]) ?>
        <?= $form->field($model, 'rekomendasi')->textarea(['rows' => 4]) ?>
        <?= $form->field($model, 'prioritas')->dropDownList(TertibTemuan::prioritasOptions()) ?>
        <?= $form->field($model, 'catatan_internal')->textarea(['rows' => 3]) ?>
        <?= $form->field($model, 'is_public')->checkbox(['label' => 'Izinkan jumlah temuan ini masuk rekap publik']) ?>
        <p class="small text-muted">Uraian temuan, rekomendasi, dan catatan internal tetap tersembunyi di web umum.</p>
        <?= Html::submitButton('Simpan Temuan', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Batal', ['view', 'id' => $parent->id], ['class' => 'btn btn-outline-secondary']) ?>
        <?php ActiveForm::end(); ?>
    </div></div>
</div>
