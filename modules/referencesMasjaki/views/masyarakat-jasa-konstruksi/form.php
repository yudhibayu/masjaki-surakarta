<?php

use app\modules\referencesMasjaki\models\MasyarakatJasaKonstruksi;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $record->isNewRecord ? 'Tambah Masyarakat Jasa Konstruksi' : 'Ubah Masyarakat Jasa Konstruksi';
$this->params['breadcrumbs'][] = ['label' => 'Data Masyarakat Jasa Konstruksi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card"><div class="card-header"><h3><?= Html::encode($this->title) ?></h3></div><div class="card-body">
    <p>Untuk jenis kelompok, NIK tidak disimpan. Data ini hanya terlihat oleh pengguna back office berwenang.</p>
    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($record, 'jenis_subjek')->dropDownList([
            MasyarakatJasaKonstruksi::PERSEORANGAN => 'Perseorangan', MasyarakatJasaKonstruksi::KELOMPOK => 'Kelompok',
        ], ['prompt' => 'Pilih jenis']) ?>
        <?= $form->field($record, 'nama')->textInput(['maxlength' => true]) ?>
        <?= $form->field($record, 'nik')->textInput(['maxlength' => 16, 'inputmode' => 'numeric', 'autocomplete' => 'off']) ?>
        <?= $form->field($record, 'instansi')->textInput(['maxlength' => true]) ?>
        <?= $form->field($record, 'jabatan')->textInput(['maxlength' => true]) ?>
        <?= $form->field($record, 'alamat')->textarea(['rows' => 3]) ?>
        <?= $form->field($record, 'nomor_telepon')->textInput(['maxlength' => true]) ?>
        <?= $form->field($record, 'is_active')->checkbox() ?>
        <?= Html::submitButton('Simpan', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Batal', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
    <?php ActiveForm::end(); ?>
</div></section>
