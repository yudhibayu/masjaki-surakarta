<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\captcha\Captcha;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use kartik\widgets\FileInput;
use newerton\fancybox3\FancyBox;
use kartik\password\PasswordInput;






/* @var $this yii\web\View */
/* @var $model app\modules\businessEntity\models\BusinessEntity */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
.field-password-input {
    width: 73%;
}

.field-password-input-2 {
    width: 73%;
}
</style>
<div class="business-entity-form">
    <?php
    echo FancyBox::widget([
        'target' => '[img_gallery]',
    ]);
    ?>

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-12'>
            <?= $form->field($model, 'company_name')->textInput(['placeholder' => 'PT.Konstruksi Indonesia Jaya']) ?>
        </div>
    </div>
    <?php if ($model->isNewRecord || $model->account_verification == 0): ?>
    <div class='row'>
        <div class='col-md-4'>
            <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'placeholder' => 'konstruksiindonesiajaya']) ?>
        </div>
        <div class='col-md-8'>
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
    <?php endif; ?>
    <div class='row'>
        <?php if ($model->isNewRecord || $model->account_verification == 0): ?>
        <div class='col-md-6'>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'konstruksiindonesiajaya@gmail.com']) ?>
        </div>
        <?php endif; ?>
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

    <div class='row'>
        <div class='col-md-12'>
            <?= $form->field($model, 'company_address')->textarea(['maxlength' => true, 'placeholder' => 'Jl.Kebangkitan Nasional No.xx']) ?>


        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'phone_number')->widget(MaskedInput::className(), [
                'mask' => '+62 999 9999 9999[9]',
                'clientOptions' => [
                    'clearIncomplete' => true,
                    'removeMaskOnSubmit' => true,
                ],
                'options' => [
                    'placeholder' => '+62 811 234 5678',
                ]
            ]); ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'pkp_confirmation_number')->textInput(['placeholder' => '2273807172024xxx']) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'website')->textInput(['maxlength' => true, 'placeholder' => 'konstruksiindonesiajaya.com']) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'fax')->textInput(['maxlength' => true, 'placeholder' => '0212190xxx', 'type' => 'number']) ?>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'postal_code')->textInput(['placeholder' => '471xx', 'type' => 'number']) ?>
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

    <div class='row'>
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
    <div class="row">
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
    <div class='row'>
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
    </div>

    <!-- <div class='row'>
        <div class='col-md-6'>
            <php
            if (!empty($model->file_name)) {
                $ext = pathinfo($model->file_name, PATHINFO_EXTENSION);
                $b = '';
                if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                    $b = Html::img(
                        '/business-entity/business-entity/fetch-file?id=' . Yii::$app->hasher->encode($model->id),
                        [
                            'style' => 'width:200px;',
                            'class' => 'img_gallery img_style'
                        ]
                    );
                } else {
                    $b = Html::encode($model->file_name);
                }
                echo 'File Sebelumnya: ';
                echo '<p>' . Html::a(
                    $b,
                    '/business-entity/business-entity/fetch-file?id=' . Yii::$app->hasher->encode($model->id),
                    [
                        'data-lightbox' => $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' ? $model->file_name : null,
                        'data-fancybox' => $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' ? 'true' : null,
                        'data-title' => $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' ? $model->file_name : null,
                        'data-alt' => $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' ? $model->file_name : null
                    ]
                );
            }
            ?>
        </div>
    </div> -->
    <div class='row'>
        <div class='col-md-12'>
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
                    'initialPreview' => $model->id && $model->file_path ? [
                        Html::img(
                            '/business-entity/business-entity/fetch-file?id=' . Yii::$app->hasher->encode($model->id),
                            [
                                'style' => 'width:100%; height:100%;object-fit: contain;',
                                'class' => 'img_gallery img_style'
                            ]
                        ),
                    ] : [],
                ]
            ])->hint('Ekstensi file yang diperbolehkan (jpg, jpeg, png). Ukuran maksimal file 150Kb');
            ?>
        </div>
    </div>
    <!-- <div class="row">
        <div class="col-md-6">
            <= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                'captchaAction' => '/user/security/captcha',
                'template' => '<div class="row"><div class="col-xs-12 col-12 text-muted text-sm-right font-weight-light font-italic"><small><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Klik gambar untuk ganti captcha</small></div><div class="col-lg-4">{image}</div><div class="col-lg-8">{input}</div></div>',
            ]) ?>
        </div>
    </div> -->

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$script = <<<JS
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('password-input');
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    const toggleIcon = document.querySelector("#togglePassword i");
    toggleIcon.classList.toggle('fa-eye-slash');
    toggleIcon.classList.toggle('fa-eye');
});
document.getElementById('togglePassword2').addEventListener('click', function() {
    const passwordInput = document.getElementById('password-input-2');
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    const toggleIcon = document.querySelector("#togglePassword2 i");
    toggleIcon.classList.toggle('fa-eye-slash');
    toggleIcon.classList.toggle('fa-eye');
});
JS;
$this->registerJs($script);
?>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script> -->

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