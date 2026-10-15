<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\modules\businessEntity\models\Owner */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="owner-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model_owner, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model_owner, 'nik_passport_number')->textInput() ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model_owner, 'npwp')->textInput([
                'id' => 'npwp-input',
                'onkeypress' => "myFunction()",
                'placeholder' => '09.254.294.3-414.xxx',
                'maxlength' => '20', // Panjang maksimum sesuai format
                'class' => 'form-control' // Tambahkan class jika diperlukan
            ]); ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model_owner, 'stock')->textInput() ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model_owner, 'citizenship')->widget(Select2::class, [
                'data' => [
                    1 => 'WNI',
                    0 => 'WNA'
                ],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Pilih Negara'), 'id' => 'citizenship_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model_owner, 'ownership_type')->widget(Select2::class, [
                'data' => $dropdown_data['ownership_type'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Pilih Tipe Kepemilikan'), 'id' => 'ownership_type_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model_owner, 'address')->textarea() ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model_owner, 'id_country')->widget(Select2::class, [
                'data' => $dropdown_data['ref_negara'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Pilih Negara'), 'id' => 'country_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?>
        </div>
        <div class='col-md-6'>
            <?=
            $form->field($model_owner, 'id_province')->widget(DepDrop::class, [
                'options' => [
                    'placeholder' => Yii::t('app', 'Choose'),
                    'id' => 'id_province_select',
                ],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => false]],
                'pluginOptions' => [
                    'depends' => ['country_select'],
                    'initialize' => true,
                    'url' => Url::to(['/references-masjaki/ref-provinsi/depdrop-by-negara']),
                    'loadingText' => Yii::t('app', 'Loading...'),
                ]
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class='col-md-6'>
            <?=
            $form->field($model_owner, 'id_regency')->widget(DepDrop::class, [
                'options' => [
                    'placeholder' => Yii::t('app', 'Choose'),
                    'id' => 'id_regency_select',
                ],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => false]],
                'pluginOptions' => [
                    'depends' => ['country_select', 'id_province_select'],
                    'initialize' => true,
                    'url' => Url::to(['/references-masjaki/ref-kabkota/depdrop-by-provinsi']),
                    'loadingText' => Yii::t('app', 'Loading...'),
                ]
            ]);
            ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model_owner, 'id_subdistrict')->widget(DepDrop::class, [
                'options' => [
                    'placeholder' => Yii::t('app', 'Choose'),
                    'id' => 'id_subdistrict_select',
                ],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => false]],
                'pluginOptions' => [
                    'depends' => ['country_select', 'id_province_select', 'id_regency_select'],
                    'initialize' => true,
                    'url' => Url::to(['/references-masjaki/ref-kecamatan/depdrop-by-kabkota']),
                    'loadingText' => Yii::t('app', 'Loading...'),
                ]
            ]);
            ?>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-6'>
            <?=
            $form->field($model_owner, 'id_ward')->widget(DepDrop::class, [
                'options' => [
                    'placeholder' => Yii::t('app', 'Choose'),
                    'id' => 'id_ward_select',
                ],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => false]],
                'pluginOptions' => [
                    'depends' => ['country_select', 'id_province_select', 'id_regency_select', 'id_subdistrict_select'],
                    'initialize' => true,
                    'url' => Url::to(['/references-masjaki/ref-kelurahan/depdrop-by-kecamatan']),
                    'loadingText' => Yii::t('app', 'Loading...'),
                ]
            ]);
            ?>
        </div>
    </div>
    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), ['business-entity/view', 'id' => Yii::$app->hasher->encode($model_owner->id_business_entity)], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton($model_owner->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    function myFunction() {
        var cleave = new Cleave('#npwp-input', {
            delimiter: '.',
            blocks: [2, 3, 3, 1, 3, 3],
            numericOnly: true,
            delimiters: ['.', '.', '.', '-', '.'],
            prefix: '',
        });
    };
</script>