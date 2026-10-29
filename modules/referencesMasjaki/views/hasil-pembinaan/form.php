<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Hasil Pembinaan Peserta';
$this->params['breadcrumbs'][] = ['label' => 'Hasil Pembinaan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$name = $participant->businessEntity ? $participant->businessEntity->company_name : ($participant->masyarakatJasaKonstruksi ? $participant->masyarakatJasaKonstruksi->nama : '—');
?>
<section class="card"><div class="card-header"><h3><?= Html::encode($this->title) ?></h3></div><div class="card-body">
    <p><strong>Kegiatan:</strong> <?= Html::encode($participant->kegiatan->nama_kegiatan) ?></p>
    <p><strong>Peserta:</strong> <?= Html::encode($name) ?></p>
    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($record, 'hasil_capaian')->textarea(['rows' => 4]) ?>
        <?= $form->field($record, 'memperoleh_sertifikat')->dropDownList(['' => 'Belum diketahui', '1' => 'Ya', '0' => 'Tidak']) ?>
        <?= $form->field($record, 'keterangan_tindak_lanjut')->textarea(['rows' => 4]) ?>
        <?= Html::submitButton('Simpan Hasil', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Batal', ['/references-masjaki/peserta-pembinaan/view', 'id' => $participant->id], ['class' => 'btn btn-outline-secondary']) ?>
    <?php ActiveForm::end(); ?>
</div></section>
