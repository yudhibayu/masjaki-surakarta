<?php

use app\modules\referencesMasjaki\models\DokumenPesertaSertifikasi;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Unggah Dokumen Peserta';
$this->params['breadcrumbs'][] = ['label' => 'Keikutsertaan', 'url' => ['/references-masjaki/tkk-peserta/view', 'id' => $peserta->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card"><div class="card-header"><h3><?= Html::encode($this->title) ?></h3></div><div class="card-body">
    <p>Beberapa file dapat dipilih sekaligus. Format PDF, JPG, atau PNG; maksimum 5 MB per file.</p>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <?= $form->field($formModel, 'jenis_dokumen')->dropDownList(DokumenPesertaSertifikasi::JENIS, ['prompt' => 'Pilih jenis dokumen']) ?>
        <?= $form->field($formModel, 'files[]')->fileInput(['multiple' => true, 'accept' => '.pdf,.jpg,.jpeg,.png']) ?>
        <?= Html::submitButton('Unggah', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Batal', ['/references-masjaki/tkk-peserta/view', 'id' => $peserta->id], ['class' => 'btn btn-secondary']) ?>
    <?php ActiveForm::end(); ?>
</div></section>
