<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\widgets\DatePicker;
use newerton\fancybox3\FancyBox;
use kartik\number\NumberControl;

/* @var $this yii\web\View */
/* @var $model app\modules\construction\models\ConstructionAccident */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="construction-accident-form">
    <?php
    echo FancyBox::widget([
        'target' => '[img_gallery]',
    ]);
    ?>

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'accident')->dropDownList([1 => Yii::t('app', 'Yes'), 0 => Yii::t('app', 'No')], ['prompt' => Yii::t('app', 'Choose'), 'onchange' => 'getValue(this.value)']);
            ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'date')->widget(DatePicker::classname(), [
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose')],
                'pluginOptions' => [
                    'autoclose' => true,
                    // 'todayHighlight' => true,
                    'format' => 'dd-mm-yyyy',
                    //'maxViewMode' => 'month',
                    //'minViewMode' => 'days',

                ]
            ]);
            ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'accident_category')->dropDownList(['Ringan' => Yii::t('app', 'Minor'), 'Sedang' => Yii::t('app', 'Moderate'), 'Berat' => Yii::t('app', 'Serious'), 'Meninggal Dunia' => Yii::t('app', 'Die')], ['prompt' => Yii::t('app', 'Choose')]); ?>
        </div>
        <!-- <div class='col-md-6'>
            <= $form->field($model, 'place')->textInput(['maxlength' => true, 'placeholder' => 'Garasi proyek konstruksi Pasar Jongke']) ?>
        </div> -->
    </div>
    <!-- <div class="row"> -->
    <!-- <div class="col-md-6">
            <= $form->field($model, 'job_executor')->textInput(['maxlength' => true, 'placeholder' => 'Budi Santoso']) ?>
        </div> -->

    <!-- </div> -->
    <!-- <div class='row'>
        <div class='col-md-6'>
            <php
            if (!empty($model->file_name_before)) {
                $ext = pathinfo($model->file_name_before, PATHINFO_EXTENSION);
                $b = '';
                if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                    $b = Html::img(
                        '/construction/construction-accident/fetch-file-before?id=' . Yii::$app->hasher->encode($model->id),
                        [
                            'style' => 'width:200px;',
                            'class' => 'img_gallery img_style'
                        ]
                    );
                } elseif ($ext == 'pdf') {
                    $b = Html::a(
                        '<i class="bx bxs-file-pdf"></i> Preview PDF',
                        '/construction/construction-accident/fetch-file-before?id=' . Yii::$app->hasher->encode($model->id),
                        [
                            'target' => '_blank',
                            'class' => 'badge badge-warning',
                        ]
                    );
                } else {
                    $b = Html::encode($model->file_name_before);
                }
                echo 'File Sebelumnya: ';
                echo '<p>' . Html::a(
                    $b,
                    '/construction/construction-accident/fetch-file-before?id=' . Yii::$app->hasher->encode($model->id),
                    [
                        'data-lightbox' => $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' ? $model->file_name_before : null,
                        'data-fancybox' => $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' ? 'true' : null,
                        'data-title' => $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' ? $model->file_name_before : null,
                        'data-alt' => $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' ? $model->file_name_before : null,
                        'target' => $ext == 'pdf' ? '_blank' : null
                    ]
                );
            }
            ?>
        </div>
        <div class='col-md-6'>
            <php
            if (!empty($model->file_name_after)) {
                $ext = pathinfo($model->file_name_after, PATHINFO_EXTENSION);
                $b = '';
                if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                    $b = Html::img(
                        '/construction/construction-accident/fetch-file-after?id=' . Yii::$app->hasher->encode($model->id),
                        [
                            'style' => 'width:200px;',
                            'class' => 'img_gallery img_style'
                        ]
                    );
                } elseif ($ext == 'pdf') {
                    $b = Html::a(
                        '<i class="bx bxs-file-pdf"></i> Preview PDF',
                        '/construction/construction-accident/fetch-file-after?id=' . Yii::$app->hasher->encode($model->id),
                        [
                            'target' => '_blank',
                            'class' => 'badge badge-warning',
                        ]
                    );
                } else {
                    $b = Html::encode($model->file_name_after);
                }
                echo 'File Sebelumnya: ';
                echo '<p>' . Html::a(
                    $b,
                    '/construction/construction-accident/fetch-file-after?id=' . Yii::$app->hasher->encode($model->id),
                    [
                        'data-lightbox' => $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' ? $model->file_name_after : null,
                        'data-fancybox' => $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' ? 'true' : null,
                        'data-title' => $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' ? $model->file_name_after : null,
                        'data-alt' => $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' ? $model->file_name_after : null,
                        'target' => $ext == 'pdf' ? '_blank' : null
                    ]
                );
            }
            ?>
        </div>
    </div> -->

    <!-- <div class='row'>
        <div class='col-md-6'>
            <= $form->field($model, 'photo_before_repair')->widget(FileInput::class, [
                'language' => 'id',
                'options' => [
                    'id' => 'halo',
                    'multiple' => false,
                    'accept' => 'image/jpeg, image/jpg, image/png, application/pdf',
                ],
                'pluginOptions' => [
                    'resizeImages' => true,
                    'previewFileType' => 'any',
                    'showPreview' => true,
                    'showCaption' => true,
                    'showRemove' => true,
                    'showUpload' => false,
                    'resizePreference' => 'height',
                    'resizeImage' => true,
                    'resizeIfSizeMoreThan' => 250,
                    'maxFileSize' => 2048,
                    'maxFileCount' => 1,
                    'allowedFileExtensions' => ['jpg', 'jpeg', 'png', 'pdf'],
                    'allowedPreviewExtensions' => ['jpg', 'jpeg', 'png', 'pdf'],
                    'initialPreview' =>
                    $model->id && $model->file_path_before ? [
                        in_array(pathinfo($model->file_name_before, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']) ? [
                            Html::img(
                                '/construction/construction-accident/fetch-file-before?id=' . Yii::$app->hasher->encode($model->id),
                                [
                                    'style' => 'width:100%; height:100%;object-fit: contain;',
                                    'class' => 'img_gallery img_style'
                                ]
                            )
                        ] : [
                            Html::a(
                                '<i class="bx bxs-file-pdf"></i> Preview PDF',
                                '/construction/construction-accident/fetch-file-before?id=' . Yii::$app->hasher->encode($model->id),
                                [
                                    'target' => '_blank',
                                    'class' => 'badge badge-warning',
                                ]
                            )
                        ]
                    ] : [],
                ]
            ])->hint('Ekstensi file (jpg, jpeg, png, pdf). Ukuran maksimal file 2MB');
            ?>
        </div>
        <div class='col-md-6'>
            <= $form->field($model, 'photo_after_repair')->widget(FileInput::class, [
                'language' => 'id',
                'options' => [
                    'id' => 'haloo',
                    'multiple' => false,
                    'accept' => 'image/jpeg, image/jpg, image/png, application/pdf',
                ],
                'pluginOptions' => [
                    'resizeImages' => true,
                    'previewFileType' => 'any',
                    'showPreview' => true,
                    'showCaption' => true,
                    'showRemove' => true,
                    'showUpload' => false,
                    'resizePreference' => 'height',
                    'resizeImage' => true,
                    'resizeIfSizeMoreThan' => 250,
                    'maxFileSize' => 2048,
                    'maxFileCount' => 1,
                    'allowedFileExtensions' => ['jpg', 'jpeg', 'png', 'pdf'],
                    'allowedPreviewExtensions' => ['jpg', 'jpeg', 'png', 'pdf'],
                    'initialPreview' =>
                    $model->id && $model->file_path_after ? [
                        in_array(pathinfo($model->file_name_after, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']) ? [
                            Html::img(
                                '/construction/construction-accident/fetch-file-after?id=' . Yii::$app->hasher->encode($model->id),
                                [
                                    'style' => 'width:100%; height:100%;object-fit: contain;',
                                    'class' => 'img_gallery img_style'
                                ]
                            )
                        ] : [
                            Html::a(
                                '<i class="bx bxs-file-pdf"></i> Preview PDF',
                                '/construction/construction-accident/fetch-file-after?id=' . Yii::$app->hasher->encode($model->id),
                                [
                                    'target' => '_blank',
                                    'class' => 'badge badge-warning',
                                ]
                            )
                        ]
                    ] : [],
                ]
            ])->hint('Ekstensi file (jpg, jpeg, png, pdf). Ukuran maksimal file 2MB');
            ?>
        </div>
    </div> -->


    <div class="row">
        <div class="col-md-6">
            <?= $form->field(
                $model,
                'amount_victims_minor_injuries',
                [
                    'template' => '{label}<div class="input-group">{input} <span style="padding: 6px; background-color: #ebebeb; border-top-right-radius: 3px; border-bottom-right-radius: 3px; border-top: 1px solid #ced4da; border-right: 1px solid #ced4da; border-bottom: 1px solid #ced4da" class="input-group-addon">Orang</span></div>{error}{hint}',
                    'inputOptions' => ['class' => 'form-control']
                ]
            )->textInput(['placeholder' => '5']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field(
                $model,
                'amount_victims_moderate_injuries',
                [
                    'template' => '{label}<div class="input-group">{input} <span style="padding: 6px; background-color: #ebebeb; border-top-right-radius: 3px; border-bottom-right-radius: 3px; border-top: 1px solid #ced4da; border-right: 1px solid #ced4da; border-bottom: 1px solid #ced4da" class="input-group-addon">Orang</span></div>{error}{hint}',
                    'inputOptions' => ['class' => 'form-control']
                ]
            )->textInput(['placeholder' => '5']) ?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field(
                $model,
                'amount_victims_serious_injuries',
                [
                    'template' => '{label}<div class="input-group">{input} <span style="padding: 6px; background-color: #ebebeb; border-top-right-radius: 3px; border-bottom-right-radius: 3px; border-top: 1px solid #ced4da; border-right: 1px solid #ced4da; border-bottom: 1px solid #ced4da" class="input-group-addon">Orang</span></div>{error}{hint}',
                    'inputOptions' => ['class' => 'form-control']
                ]
            )->textInput(['placeholder' => '5']) ?>

        </div>
        <div class="col-md-6">
            <?= $form->field(
                $model,
                'amount_victims_die',
                [
                    'template' => '{label}<div class="input-group">{input} <span style="padding: 6px; background-color: #ebebeb; border-top-right-radius: 3px; border-bottom-right-radius: 3px; border-top: 1px solid #ced4da; border-right: 1px solid #ced4da; border-bottom: 1px solid #ced4da" class="input-group-addon">Orang</span></div>{error}{hint}',
                    'inputOptions' => ['class' => 'form-control']
                ]
            )->textInput(['placeholder' => '5']) ?>

        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'description')->textarea(['rows' => 6, 'placeholder' => 'Korban kecelakaan sudah diatasi dan korban terselamatkan']) ?>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'bpjs')->dropDownList([1 => Yii::t('app', 'Yes'), 0 => Yii::t('app', 'No')], ['prompt' => Yii::t('app', 'Choose'), 'onchange' => 'getValueBpjs(this.value)']);
            ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'no_bpjs')->textInput(['maxlength' => true]) ?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field(
                $model,
                'total_pekerja',
                [
                    'template' => '{label}<div class="input-group">{input} <span style="padding: 6px; background-color: #ebebeb; border-top-right-radius: 3px; border-bottom-right-radius: 3px; border-top: 1px solid #ced4da; border-right: 1px solid #ced4da; border-bottom: 1px solid #ced4da" class="input-group-addon">Orang</span></div>{error}{hint}',
                    'inputOptions' => ['class' => 'form-control']
                ]
            )->textInput(['placeholder' => '5']) ?>

        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'iuran_pekerja')->widget(NumberControl::classname(), [
                'maskedInputOptions' => [
                    'removeMaskOnSubmit' => true,
                    'prefix' => 'Rp. ',
                    'digits' => 2,
                    'groupSeparator' => '.',
                    'radixPoint' => ',',
                ],
            ]); ?>
        </div>

    </div>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), ['/rup/pbj/view', 'id' => Yii::$app->hasher->encode($model->id_construction_work)], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    function getValue($value) {
        if ($value == 0) {
            document.getElementById('constructionaccident-date').value = '';
            document.getElementById('constructionaccident-accident_category').value = '';
            document.getElementById('constructionaccident-amount_victims_minor_injuries').value = '';
            document.getElementById('constructionaccident-amount_victims_moderate_injuries').value = '';
            document.getElementById('constructionaccident-amount_victims_serious_injuries').value = '';
            document.getElementById('constructionaccident-amount_victims_die').value = '';
            document.getElementById('constructionaccident-description').value = '';
        }
    }

    function getValueBpjs($value) {
        if ($value == 0) {
            document.getElementById('constructionaccident-no_bpjs').value = '';
            document.getElementById('constructionaccident-total_pekerja').value = '';
            document.getElementById('constructionaccident-iuran_pekerja-disp').value = '';
        }
    }
</script>