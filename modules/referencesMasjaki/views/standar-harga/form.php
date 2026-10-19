<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $record->isNewRecord ? 'Tambah Dokumen Produk Hukum' : 'Edit Dokumen ' . $record->master->nama . ' ' . $record->tahun;
$this->params['breadcrumbs'][] = ['label' => 'Peraturan Jasa Konstruksi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header"><h3><?= Html::encode($this->title) ?></h3></div>
    <div class="card-body">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
            <?= $form->field($formModel, 'master_produk_hukum_id')->dropDownList($masters, ['prompt' => 'Pilih produk hukum']) ?>
            <?= $form->field($formModel, 'tahun')->input('number', ['min' => 2000, 'max' => 2100]) ?>
            <p>Format: PDF, XLS, atau XLSX. <strong>Maksimum 2 MB per file.</strong>
                Unggah ulang kombinasi yang sama untuk mengganti file tanpa mengubah status publikasi.</p>
            <?= $form->field($formModel, 'file')->fileInput(['accept' => '.pdf,.xls,.xlsx']) ?>
            <?php if (!$record->isNewRecord): ?>
                <p>File saat ini: <?= Html::a(Html::encode($record->nama_file), ['unduh', 'id' => $record->id]) ?>. Kosongkan unggahan untuk mempertahankannya.</p>
            <?php endif; ?>
            <?= $form->field($formModel, 'is_public')->checkbox(['label' => 'Publikasikan di web umum']) ?>
            <div class="mt-3">
                <?= Html::submitButton('Simpan', ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Batal', ['index'], ['class' => 'btn btn-secondary']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</section>
