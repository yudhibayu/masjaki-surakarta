<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\widgets\DatePicker;
use newerton\fancybox3\FancyBox;


/* @var $this yii\web\View */
/* @var $model app\modules\construction\models\ConstructionSafety */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="construction-safety-form">
    <?php
    echo FancyBox::widget([
        'target' => '[img_gallery]',
    ]);
    ?>

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'date')->widget(DatePicker::classname(), [
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'start_date'],
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
    <!-- <div class='row'>
        <div class='col-md-6'>
            <php
            if (!empty($model->file_name)) {
                $ext = pathinfo($model->file_name, PATHINFO_EXTENSION);
                $b = '';
                if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                    $b = Html::img(
                        '/construction/construction-safety/fetch-file?id=' . Yii::$app->hasher->encode($model->id),
                        [
                            'style' => 'width:200px;',
                            'class' => 'img_gallery img_style'
                        ]
                    );
                } elseif ($ext == 'pdf') {
                    $b = Html::a(
                        '<i class="bx bxs-file-pdf"></i> Preview PDF',
                        '/construction/construction-safety/fetch-file?id=' . Yii::$app->hasher->encode($model->id),
                        [
                            'target' => '_blank',
                            'class' => 'badge badge-warning',
                        ]
                    );
                } else {
                    $b = '';
                }
                echo 'File Sebelumnya: ';
                echo '<p>' . Html::a(
                    $b,
                    '/construction/construction-safety/fetch-file?id=' . Yii::$app->hasher->encode($model->id),
                    [
                        'data-lightbox' => $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' ? $model->file_name : null,
                        'data-fancybox' => $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' ? 'true' : null,
                        'data-title' => $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' ? $model->file_name : null,
                        'data-alt' => $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' ? $model->file_name : null,
                        'target' => $ext == 'pdf' ? '_blank' : null
                    ]
                );
            }
            ?>
        </div>
    </div> -->
    <div class='row'>
        <div class='col-md-12'>
            <?= $form->field($model, 'report_file')->widget(FileInput::class, [
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
                    'maxFileSize' => 150,
                    'maxFileCount' => 1,
                    'allowedFileExtensions' => ['jpg', 'jpeg', 'png', 'pdf'],
                    'allowedPreviewExtensions' => ['jpg', 'jpeg', 'png', 'pdf'],
                    'initialPreview' =>
                    $model->id && $model->file_path ? [
                        in_array(pathinfo($model->file_name, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']) ? [
                            Html::img(
                                '/construction/construction-safety/fetch-file?id=' . Yii::$app->hasher->encode($model->id),
                                [
                                    'style' => 'width:100%; height:100%;object-fit: contain;',
                                    'class' => 'img_gallery img_style'
                                ]
                            )
                        ] : [
                            Html::a(
                                '<i class="bx bxs-file-pdf"></i> Preview PDF',
                                '/construction/construction-safety/fetch-file?id=' . Yii::$app->hasher->encode($model->id),
                                [
                                    'target' => '_blank',
                                    'class' => 'badge badge-warning',
                                ]
                            )
                        ]
                    ] : [],
                ]
            ])->hint('Ekstensi file (jpg, jpeg, png, pdf). Ukuran maksimal file 150Kb');
            ?> </div>
    </div>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), ['/rup/pbj/view', 'id' => Yii::$app->hasher->encode($model->id_construction_work)], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>