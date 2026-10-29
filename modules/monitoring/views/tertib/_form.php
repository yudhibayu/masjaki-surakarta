<?php

use app\modules\monitoring\models\TertibPengawasan;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/* @var $model app\modules\monitoring\models\TertibPengawasan */
/* @var $taskOptions array */

$form = ActiveForm::begin();
?>
<?= $form->field($model, 'monitoring_task_id')->dropDownList($taskOptions, ['prompt' => 'Pilih paket pekerjaan']) ?>
<?= $form->field($model, 'tanggal_pengawasan')->input('date') ?>
<?= $form->field($model, 'metode_pengawasan')->dropDownList(TertibPengawasan::metodeOptions(), ['prompt' => 'Pilih metode']) ?>
<?= $form->field($model, 'uraian')->textarea(['rows' => 5]) ?>
<?= $form->field($model, 'status_pelaksanaan')->dropDownList(TertibPengawasan::statusOptions()) ?>
<?= $form->field($model, 'is_public')->checkbox(['label' => 'Tampilkan ringkasan kegiatan ini di web umum']) ?>
<p class="small text-muted">Publikasi hanya menampilkan rekap jumlah. Uraian internal dan dokumen tidak ditampilkan kepada masyarakat.</p>
<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? 'Simpan Pengawasan' : 'Simpan Perubahan', ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Batal', $model->isNewRecord ? ['index'] : ['view', 'id' => $model->id], ['class' => 'btn btn-outline-secondary']) ?>
</div>
<?php ActiveForm::end(); ?>
