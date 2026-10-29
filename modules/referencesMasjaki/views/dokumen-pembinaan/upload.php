<?php

use app\modules\referencesMasjaki\models\DokumenKegiatanPembinaan;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Unggah Dokumen Kegiatan';
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Pembinaan', 'url' => ['/references-masjaki/kegiatan-pembinaan']];
$this->params['breadcrumbs'][] = ['label' => $activity->nama_kegiatan, 'url' => ['/references-masjaki/kegiatan-pembinaan/view', 'id' => $activity->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card"><div class="card-header"><h3><?= Html::encode($this->title) ?></h3></div><div class="card-body">
    <p><strong>Kegiatan:</strong> <?= Html::encode($activity->nama_kegiatan) ?></p>
    <p>Format PDF, JPG, PNG, XLS, XLSX. Maksimal 10 MB per file. Dokumen hanya dapat diunduh oleh pengguna back office berwenang.</p>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <?= $form->field($record, 'jenis_dokumen')->dropDownList(DokumenKegiatanPembinaan::JENIS, ['prompt' => 'Pilih jenis dokumen']) ?>
        <?= $form->field($record, 'upload')->fileInput(['accept' => '.pdf,.jpg,.jpeg,.png,.xls,.xlsx']) ?>
        <?= Html::submitButton('Unggah', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Batal', ['/references-masjaki/kegiatan-pembinaan/view', 'id' => $activity->id], ['class' => 'btn btn-outline-secondary']) ?>
    <?php ActiveForm::end(); ?>
</div></section>
