<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;


/* @var $this yii\web\View */
/* @var $model app\modules\businessEntity\models\Administrator */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="administrator-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model_administrator, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model_administrator, 'nik_passport_number')->textInput() ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model_administrator, 'npwp')->textInput([
                'id' => 'npwp-input',
                'onkeypress' => "myFunction()",
                'placeholder' => '09.254.294.3-414.xxx',
                'maxlength' => '20', // Panjang maksimum sesuai format
                'class' => 'form-control' // Tambahkan class jika diperlukan
            ]);  ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model_administrator, 'employment_bpjs_number')->textInput() ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model_administrator, 'number_bpjs_health')->textInput() ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model_administrator, 'status')->widget(Select2::class, [
                'data' => [
                    1 => 'Aktif',
                    0 => 'Nonaktif'
                ],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Pilih Status'), 'id' => 'status_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model_administrator, 'served_since')->widget(DatePicker::classname(), [
                'language' => 'id',
                'options' => ['placeholder' => 'Choose', 'id' => 'served_since'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'todayHighlight' => true,
                    'format' => 'dd-mm-yyyy',
                    //'maxViewMode' => 'month',
                    //'minViewMode' => 'days',

                ]
            ]);
            ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model_administrator, 'served_arrived')->widget(DatePicker::classname(), [
                'language' => 'id',
                'options' => ['placeholder' => 'Choose', 'id' => 'served_arrived'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'todayHighlight' => true,
                    'format' => 'dd-mm-yyyy',
                    //'maxViewMode' => 'month',
                    //'minViewMode' => 'days',

                ]
            ]);
            ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model_administrator, 'position')->textInput(['maxlength' => true]) ?>

        </div>
        <div class='col-md-6'>
            <?= $form->field($model_administrator, 'type_of_management')->widget(Select2::class, [
                'data' => $dropdown_data['type_of_management'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Pilih Tipe Kepengurusan'), 'id' => 'type_of_management_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model_administrator, 'citizenship')->widget(Select2::class, [
                'data' => [
                    1 => 'WNI',
                    0 => 'WNA'
                ],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Pilih Negara'), 'id' => 'citizenship_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?> </div>
        <div class='col-md-6'>
            <?= $form->field($model_administrator, 'address')->textarea() ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model_administrator, 'id_country')->widget(Select2::class, [
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
            $form->field($model_administrator, 'id_province')->widget(DepDrop::class, [
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
            $form->field($model_administrator, 'id_regency')->widget(DepDrop::class, [
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
            $form->field($model_administrator, 'id_subdistrict')->widget(DepDrop::class, [
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
            $form->field($model_administrator, 'id_ward')->widget(DepDrop::class, [
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
        <?= Html::a(Yii::t('app', 'Cancel'), ['business-entity/view', 'id' => Yii::$app->hasher->encode($model_administrator->id_business_entity)], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton($model_administrator->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
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