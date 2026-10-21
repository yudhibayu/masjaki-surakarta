<?php

use app\components\TkkDateForm;
use kartik\widgets\DatePicker;
use app\modules\referencesMasjaki\models\PesertaFasilitasiSertifikasi;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

$this->title = $record->isNewRecord ? 'Tambah Keikutsertaan' : 'Edit Keikutsertaan';
$this->params['breadcrumbs'][] = ['label' => 'Keikutsertaan Sertifikasi TKK', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$selected = $selectedPerson ? [$selectedPerson->id => $selectedPerson->nama_lengkap . ' — ' . $selectedPerson->nikTersamar] : [];
?>
<section class="card"><div class="card-header"><h3><?= Html::encode($this->title) ?></h3></div><div class="card-body">
    <p><?= Html::a('Tenaga kerja belum ada? Tambah identitas', ['/references-masjaki/tkk-tenaga-kerja/create'], ['class' => 'btn btn-outline-primary']) ?></p>
    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($record, 'tenaga_kerja_id')->widget(Select2::class, [
            'bsVersion' => '5.x',
            'data' => $selected,
            'options' => ['placeholder' => 'Cari nama atau NIK tenaga kerja'],
            'pluginOptions' => [
                'allowClear' => true, 'minimumInputLength' => 2,
                'ajax' => [
                    'url' => Url::to(['cari-tenaga-kerja']), 'dataType' => 'json', 'delay' => 250,
                    'data' => new JsExpression('function(params) { return {q: params.term}; }'),
                ],
            ],
        ]) ?>
        <?= $form->field($record, 'kegiatan_id')->dropDownList($activities, ['prompt' => 'Pilih kegiatan']) ?>
        <?= $form->field($record, 'jabatan_kerja')->textInput(['maxlength' => true]) ?>
        <?= $form->field($record, 'jenjang_kualifikasi')->textInput(['maxlength' => true]) ?>
        <?= $form->field($record, 'skema_sertifikasi')->textInput(['maxlength' => true]) ?>
        <?= $form->field($record, 'jenis_fasilitasi')->dropDownList(PesertaFasilitasiSertifikasi::JENIS, ['prompt' => 'Pilih jenis']) ?>
        <?= $form->field($record, 'status_sertifikasi')->dropDownList(PesertaFasilitasiSertifikasi::STATUS, ['prompt' => 'Pilih status']) ?>
        <p>Informasi SKK diisi setelah penerbitannya terkonfirmasi. Status kompeten tidak otomatis menerbitkan SKK.</p>
        <?= $form->field($record, 'nomor_skk')->textInput(['maxlength' => true]) ?>
        <?= $form->field($record, 'tanggal_terbit_skk', ['enableClientValidation' => false])->widget(DatePicker::class, [
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'language' => 'id',
            'options' => ['value' => TkkDateForm::display($record->tanggal_terbit_skk), 'placeholder' => 'dd-mm-yyyy', 'maxlength' => 10, 'autocomplete' => 'off'],
            'pluginOptions' => ['format' => 'dd-mm-yyyy', 'autoclose' => true, 'todayHighlight' => true],
        ]) ?>
        <?= $form->field($record, 'masa_berlaku_skk_hingga', ['enableClientValidation' => false])->widget(DatePicker::class, [
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'language' => 'id',
            'options' => ['value' => TkkDateForm::display($record->masa_berlaku_skk_hingga), 'placeholder' => 'dd-mm-yyyy', 'maxlength' => 10, 'autocomplete' => 'off'],
            'pluginOptions' => ['format' => 'dd-mm-yyyy', 'autoclose' => true, 'todayHighlight' => true],
        ]) ?>
        <?= $form->field($record, 'keterangan')->textarea(['rows' => 3]) ?>
        <?= Html::submitButton('Simpan', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Batal', ['index'], ['class' => 'btn btn-secondary']) ?>
    <?php ActiveForm::end(); ?>
</div></section>
