<?php

use app\modules\monitoring\models\TertibTindakLanjut;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

$this->title = 'Catat Riwayat Tindak Lanjut';
$this->params['breadcrumbs'][] = ['label' => 'Rekap Pengawasan Tertib', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Detail Pengawasan', 'url' => ['view', 'id' => $parent->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tertib-tindak-lanjut-form">
    <h1 class="h3 mb-3"><?= Html::encode($this->title) ?></h1>
    <div class="alert alert-light border">
        <strong><?= Html::encode($finding->kategori) ?></strong>
        <div><?= nl2br(Html::encode($finding->uraian)) ?></div>
    </div>
    <div class="card"><div class="card-body">
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'tanggal_tindak_lanjut')->input('date') ?>
        <?= $form->field($model, 'uraian_tindakan')->textarea(['rows' => 5]) ?>
        <?= $form->field($model, 'hasil_verifikasi')->textarea(['rows' => 4]) ?>
        <?= $form->field($model, 'status')->dropDownList(TertibTindakLanjut::statusOptions()) ?>
        <?= $form->field($model, 'catatan_internal')->textarea(['rows' => 3]) ?>
        <p class="small text-muted">Setiap penyimpanan menambahkan riwayat baru dan memperbarui status terkini temuan.</p>
        <?= Html::submitButton('Simpan Riwayat', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Batal', ['view', 'id' => $parent->id], ['class' => 'btn btn-outline-secondary']) ?>
        <?php ActiveForm::end(); ?>
    </div></div>
</div>
