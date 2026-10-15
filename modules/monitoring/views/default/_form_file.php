<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use kartik\widgets\FileInput;
use newerton\fancybox3\FancyBox;

/* @var $this yii\web\View */
/* @var $model app\modules\rtp_ab1\models\RtpAb1Realization */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rtp-ab1-realization-form">
    <?php
    echo FancyBox::widget([
        'target' => '[img_gallery]',
    ]);
    ?>
    <?php $form = ActiveForm::begin(['options' => [
        'enctype' => 'multipart/form-data',

    ], 'fieldConfig' => [
        'template' => '{label}{hint}{input}{error}',
    ]]); ?>

    <div class='row'>
        <div class='col-md-6'>
            <?php
            if (!empty($model->file_name)) {
                $ext = pathinfo($model->file_name, PATHINFO_EXTENSION);

                if (isset($ext)) {
                    if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif' || $ext == 'webp') {
                        $b = Html::img(
                            "/monitoring/default/file-item?id=" . $model->file_name,
                            $options = [
                                'style' => ['width' => '200px'],
                                'class' => 'img_gallery img_style'
                            ]
                        );
                    } else {
                        $b = $model->file_name;
                    }
                }

                echo 'File Sebelumnya : ';
                echo '<a href="/monitoring/default/file-item?id=' . $model->file_name . '" data-lightbox="' . $model->file_name . '" data-fancybox="true" data-title="' . $model->file_name . '" data-alt="' . $model->file_name . '">
                        <p>' . $b .
                    '</a>';
            }
            ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-12'>
            <?= $form->field($model, 'file_name')->widget(FileInput::classname(), [
                'options' => [
                    'multiple' => false,
                    'accept' => 'image/jpeg, image/jpg, image/png, application/pdf, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                ],
                'pluginOptions' => [
                    'language' => 'id',
                    'resizeImages' => true,
                    'previewFileType' => 'any',
                    'showPreview' => true,
                    'showCaption' => true,
                    'showRemove' => true,
                    'showUpload' => false,
                    'resizePreference' => 'height',
                    'resizeImage' => true,
                    'resizeIfSizeMoreThan' => 250,
                    'maxFileSize' => 2600,
                    'maxFileCount' => 1,
                    'allowedFileExtensions' => ['jpg', 'jpeg', 'png', 'pdf', 'xlsx', 'docx'],
                    'allowedPreviewExtensions' => ['jpg', 'jpeg', 'png', 'pdf', 'xlsx', 'docx'],
                ]
            ])->hint('Ekstensi file (jpg, jpeg, png, pdf, xlsx, docx). Ukuran maksimal file 2.54MB');
            ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-12'>
            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
        </div>
    </div>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), ['view','id' => Yii::$app->hasher->encode($model->monitoring_task_id)], ['class' => 'btn btn-warning']) ?>
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>