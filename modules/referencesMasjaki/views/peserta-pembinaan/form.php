<?php

use app\modules\referencesMasjaki\models\PesertaPembinaan;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

$this->title = $record->isNewRecord ? 'Tambah Peserta Pembinaan' : 'Ubah Peserta Pembinaan';
$this->params['breadcrumbs'][] = ['label' => 'Peserta Pembinaan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card"><div class="card-header"><h3><?= Html::encode($this->title) ?></h3></div><div class="card-body">
    <p>Peserta dipilih dari master yang tersedia. Satu baris hanya merujuk pada satu BUJK, satu masyarakat, atau satu kelompok.</p>
    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($record, 'kegiatan_id')->dropDownList($activities, ['prompt' => 'Pilih kegiatan']) ?>
        <?= $form->field($record, 'jenis_entitas')->radioList([
            PesertaPembinaan::BUJK => 'BUJK',
            PesertaPembinaan::MASYARAKAT => 'Masyarakat',
            PesertaPembinaan::KELOMPOK => 'Kelompok',
        ]) ?>
        <?php if ($availableBujkCount === 0): ?>
            <div id="pembinaan-bujk-empty" class="alert alert-info py-2">Belum ada BUJK yang aktif dan terverifikasi. Daftarkan atau verifikasi BUJK terlebih dahulu agar dapat dipilih.</div>
        <?php endif; ?>
        <?= $form->field($record, 'id_business_entity')->label('BUJK')->widget(Select2::class, [
            'bsVersion' => '5.x', 'data' => $selectedBujk,
            'options' => ['placeholder' => 'Cari nama BUJK (minimal 2 karakter)'],
            'pluginOptions' => [
                'allowClear' => true, 'minimumInputLength' => 2,
                'ajax' => ['url' => Url::to(['cari-bujk']), 'dataType' => 'json', 'delay' => 250,
                    'data' => new JsExpression('function(params) { return {q: params.term}; }')],
            ],
        ]) ?>
        <?= $form->field($record, 'masyarakat_jasa_konstruksi_id')->label('Masyarakat / Kelompok')->widget(Select2::class, [
            'bsVersion' => '5.x', 'data' => $selectedMasyarakat,
            'options' => ['placeholder' => 'Cari nama masyarakat atau kelompok (minimal 2 karakter)'],
            'pluginOptions' => [
                'allowClear' => true, 'minimumInputLength' => 2,
                'ajax' => ['url' => Url::to(['cari-masyarakat']), 'dataType' => 'json', 'delay' => 250,
                    'data' => new JsExpression('function(params) { return {q: params.term, jenis_subjek: $(\'input[name="PesertaPembinaan[jenis_entitas]"]:checked\').val()}; }')],
            ],
        ]) ?>
        <?= Html::submitButton('Simpan', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Batal', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
    <?php ActiveForm::end(); ?>
</div></section>
<?php
$this->registerJs(<<<'JS'
function togglePembinaanParticipantFields() {
    var type = $('input[name="PesertaPembinaan[jenis_entitas]"]:checked').val();
    $('.field-pesertapembinaan-id_business_entity').toggle(type === 'BUJK');
    $('.field-pesertapembinaan-masyarakat_jasa_konstruksi_id').toggle(type !== 'BUJK');
    $('#pembinaan-bujk-empty').toggle(type === 'BUJK');
    if (window.pembinaanPreviousParticipantType && window.pembinaanPreviousParticipantType !== type) {
        $('#pesertapembinaan-id_business_entity').val(null).trigger('change');
        $('#pesertapembinaan-masyarakat_jasa_konstruksi_id').val(null).trigger('change');
    }
    window.pembinaanPreviousParticipantType = type;
}
$(document).on('change', 'input[name="PesertaPembinaan[jenis_entitas]"]', togglePembinaanParticipantFields);
togglePembinaanParticipantFields();
JS);
?>
