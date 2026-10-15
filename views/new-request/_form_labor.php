<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\captcha\Captcha;
use kartik\select2\Select2;
use kartik\widgets\DepDrop;
use yii\widgets\ActiveForm;
use kartik\password\PasswordInput;
use yii\widgets\MaskedInput;


/* @var $this yii\web\View */
/* @var $model app\modules\frontMap\models\TblAccountRequest */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-account-request-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row mb-2'>
        <div class='col-md-6'>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Budi Santoso']) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'nik_passport_number')->textInput(['placeholder' => '3372014403996xxx']) ?>
        </div>
    </div>

    <div class='row mb-2'>
        <div class='col-md-6'>
            <?= $form->field($model, 'username')->textInput(['placeholder' => 'budisantoso']) ?>
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
            <?= $form->field($model, 'npwp')->textInput([
                'id' => 'npwp-input',
                'onkeypress' => "myFunction()",
                'placeholder' => '09.254.294.3-414.xxx',
                'maxlength' => '20', // Panjang maksimum sesuai format
                'class' => 'form-control' // Tambahkan class jika diperlukan
            ]);  ?>
        </div>
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
            ]);  ?>
        </div>
    </div>

    <div class='row mb-2'>
        <div class='col-md-6'>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'budisantoso@gmail.com']) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'skilled_power_type')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class='row mb-2'>
        <div class='col-md-6'>
            <?= $form->field($model, 'citizenship')->widget(Select2::class, [
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
            <?= $form->field($model, 'bpjs_health_number')->textInput(['placeholder' => '33778654xxx']) ?>
        </div>
    </div>

    <div class='row mb-2'>
        <div class='col-md-6'>
            <?= $form->field($model, 'employment_bpjs_number')->textInput(['placeholder' => '99986756xxx']) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'last_education')->widget(Select2::class, [
                'data' => $dropdown_data['education'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose Education'), 'id' => 'education_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?>
        </div>
    </div>

    <div class='row mb-2'>

        <style>
            .form-control.separator {
                background-color: #fff;
                text-align: center;
                width: fit-content;
            }

            .field-labor-long_work_experience {
                width: 75%;
            }
        </style>
        <div class="col-md-6">
            <div class="form-group">
                <label for="age_range">Pengalaman Kerja</label>
                <div class="input-group">
                    <?= $form->field($model, 'long_work_experience')->textInput(['placeholder' => '5'])->label(false) ?>
                    <span class="form-control separator">Tahun</span>
                </div>
            </div>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'employment_status')->widget(Select2::class, [
                'data' => ['1' => 'Tetap', '0' => 'Tidak Tetap'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Pilih Status Pekerjaan'), 'id' => 'employment_status_select'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>

        </div>
    </div>

    <div class='row mb-2'>
        <div class='col-md-6'>
            <?= $form->field($model, 'professional_expertise')->textInput(['maxlength' => true, 'placeholder' => 'Mandor Konstruksi']) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'address')->textarea(['maxlength' => true, 'placeholder' => 'Jl.Kebangkitan Nasional No.xx']) ?>
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
            <?=
            $form->field($model, 'id_province')->widget(DepDrop::class, [
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
        <div class='col-md-6'>
            <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>
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