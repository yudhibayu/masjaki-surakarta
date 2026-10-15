<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\captcha\Captcha;
use kartik\select2\Select2;
use kartik\widgets\DepDrop;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use kartik\widgets\FileInput;
use kartik\password\PasswordInput;


/* @var $this yii\web\View */
/* @var $model app\modules\frontMap\models\TblAccountRequest */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-account-request-form">


    <?php $form = ActiveForm::begin(); ?>

    <div class='row mb-2'>
        <div class='col-md-12'>
            <?= $form->field($model, 'company_name')->textInput(['placeholder' => 'PT.Konstruksi Indonesia Jaya']) ?>
        </div>
    </div>
    <div class='row mb-2'>
        <div class='col-md-6'>
            <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'placeholder' => 'konstruksiindonesiajaya']) ?>
        </div>
        <div class='col-md-6'>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'password')->widget(PasswordInput::class)  ?>

                    <!-- <div class="form-group">
                    <= Html::label('Password', 'password-input', ['class' => 'form-label']) ?>
                    <div class="input-group">
                        <= $form->field($model, 'password')->passwordInput([
                            'id' => 'password-input',
                            'aria-describedby' => 'password',
                        ])->label(false) ?>

                        <span class="input-group-text cursor-pointer" id="togglePassword"
                            style="border-radius: 0.5rem; border-top-left-radius: 0; border-bottom-left-radius: 0;">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>
                </div> -->
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'confirm_password')->widget(PasswordInput::class)  ?>

                    <!-- <div class="form-group">
                    <= Html::label('Konfirmasi Password', 'password-input', ['class' => 'form-label']) ?>
                    <div class="input-group">
                        <= $form->field($model, 'confirm_password')->passwordInput([
                            'id' => 'password-input-2',
                            'aria-describedby' => 'password',
                        ])->label(false) ?>

                        <span class="input-group-text cursor-pointer" id="togglePassword2"
                            style="border-radius: 0.5rem; border-top-left-radius: 0; border-bottom-left-radius: 0;">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>
                </div> -->
                    <!-- <= $form->field($model, 'confirm_password')->passwordInput(['maxlength' => true]) ?> -->
                </div>
            </div>
        </div>
    </div>

    <div class='row mb-2'>
        <div class='col-md-6'>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'konstruksiindonesiajaya@gmail.com']) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'npwp')->textInput([
                'id' => 'npwp-input',
                'onkeypress' => "myFunction()",
                'placeholder' => '09.254.294.3-414.xxx',
                'maxlength' => '20', // Panjang maksimum sesuai format
                'class' => 'form-control' // Tambahkan class jika diperlukan
            ]); ?>
        </div>
    </div>

    <div class='row mb-2'>
        <div class='col-md-12'>
            <?= $form->field($model, 'company_address')->textarea(['maxlength' => true, 'placeholder' => 'Jl.Kebangkitan Nasional No.xx']) ?>


        </div>
    </div>

    <div class='row mb-2'>
        <div class='col-md-6'>
            <?= $form->field($model, 'phone_number')->widget(MaskedInput::className(), [
                'mask' => '+62 999 9999 9999[9]',
                'clientOptions' => [
                    'clearIncomplete' => true,
                    'removeMaskOnSubmit' => true,
                ],
                'options' => [
                    'placeholder' => '+62 811 234 5xxx',
                ]
            ]); ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'pkp_confirmation_number')->textInput(['placeholder' => '2273807172024xxx']) ?>
        </div>
    </div>

    <div class='row mb-2'>
        <div class='col-md-6'>
            <?= $form->field($model, 'website')->textInput(['maxlength' => true, 'placeholder' => 'konstruksiindonesiajaya.com']) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'fax')->textInput(['maxlength' => true, 'placeholder' => '0212190xxx']) ?>
        </div>
    </div>
    <div class='row mb-2'>
        <div class='col-md-6'>
            <?= $form->field($model, 'postal_code')->textInput(['placeholder' => '471xx']) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'form_of_business')->widget(Select2::class, [
                'data' => $dropdown_data['form_of_business'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Pilih Bentuk Usaha'), 'id' => 'form_of_business_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?>
        </div>
    </div>

    <div class='row mb-2'>
        <div class='col-md-6'>
            <?= $form->field($model, 'id_country')->widget(Select2::class, [
                'data' => $dropdown_data['ref_negara'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Pilih Negara'), 'id' => 'country_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'id_province')->widget(DepDrop::class, [
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
            ]); ?>
        </div>
    </div>
    <div class="row mb-2">
        <div class='col-md-6'>
            <?=
            $form->field($model, 'id_regency')->widget(DepDrop::class, [
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
            $form->field($model, 'id_subdistrict')->widget(DepDrop::class, [
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
    <div class='row mb-2'>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'id_ward')->widget(DepDrop::class, [
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
        <div class="col-md-6">
            <?= $form->field($model, 'photo')->widget(FileInput::class, [
                'language' => 'id',
                'options' => [
                    'id' => 'halo',
                    'multiple' => false,
                    'accept' => 'image/jpeg, image/jpg, image/png',
                ],
                'pluginOptions' => [
                    'resizeImages' => true,
                    'previewFileType' => 'image',
                    'showPreview' => true,
                    'showCaption' => true,
                    'showRemove' => true,
                    'showUpload' => false,
                    'resizePreference' => 'height',
                    'resizeImage' => true,
                    'resizeIfSizeMoreThan' => 250,
                    'maxFileSize' => 150,
                    'maxFileCount' => 1,
                    'allowedFileExtensions' => ['jpg', 'jpeg', 'png'],
                    'allowedPreviewExtensions' => ['jpg', 'jpeg', 'png'],
                ]
            ])->hint('Ekstensi file yang diperbolehkan (jpg, jpeg, png). Ukuran maksimal file 150Kb');
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                'captchaAction' => '/user/security/captcha',
                'template' => '<div class="row"><div class="col-xs-12 col-12 text-muted text-sm-right font-weight-light font-italic"><small><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Klik gambar untuk ganti captcha</small></div><div class="col-lg-4">{image}</div><div class="col-lg-8">{input}</div></div>',
            ]) ?>
        </div>
    </div>
    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-danger']) ?>
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