<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\widgets\DatePicker;
use newerton\fancybox3\FancyBox;


/* @var $this yii\web\View */
/* @var $model app\modules\businessEntity\models\ChangeDeed */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="change-deed-form">
    <?php
    echo FancyBox::widget([
        'target' => '[img_gallery]',
    ]);
    ?>
    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model_change_deed, 'deed_number')->textInput() ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model_change_deed, 'date_of_deed')->widget(DatePicker::classname(), [
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'start_date'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'todayHighlight' => true,
                    'format' => 'dd-mm-yyyy',
                    //'maxViewMode' => 'month',
                    //'minViewMode' => 'days',
                ]
            ]); ?>
        </div>
    </div>
    <!-- <div class='row'>
        <div class='col-md-6'>
            <php
            if (!empty($model_change_deed->file_name)) {
                $ext = pathinfo($model_change_deed->file_name, PATHINFO_EXTENSION);
                $b = '';
                if ($ext == 'pdf') {
                    $b = Html::a(
                        '<i class="bx bxs-file-pdf"></i> Preview PDF',
                        '/business-entity/change-deed/fetch-file?id=' . Yii::$app->hasher->encode($model_change_deed->id),
                        [
                            'target' => '_blank',
                            'class' => 'badge badge-warning',
                        ]
                    );
                } else {
                    $b = Html::encode($model_change_deed->file_name);
                }
                echo 'File Sebelumnya: ';
                echo '<p>' . Html::a(
                    $b,
                    '/business-entity/change-deed/fetch-file?id=' . Yii::$app->hasher->encode($model_change_deed->id),
                    [
                        'target' => $ext == 'pdf' ? '_blank' : null
                    ]
                );
            }
            ?>
        </div>
    </div> -->
    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model_change_deed, 'deed_file')->widget(FileInput::class, [
                'language' => 'id',
                'options' => [
                    'id' => 'halo',
                    'multiple' => false,
                    'accept' => 'application/pdf'
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
                    'allowedFileExtensions' => ['pdf'],
                    'allowedPreviewExtensions' => ['pdf'],
                    'initialPreview' => $model_change_deed->id && $model_change_deed->file_path ? [
                        Html::a(
                            '<i class="bx bxs-file-pdf"></i> Preview PDF',
                            '/business-entity/change-deed/fetch-file?id=' . Yii::$app->hasher->encode($model_change_deed->id),
                            [
                                'target' => '_blank',
                                'class' => 'badge badge-warning',
                            ]
                        ),
                    ] : [],
                ]
            ])->hint('Ekstensi file pdf. Ukuran maksimal file 150Kb');
            ?> </div>
        <div class='col-md-6'>
            <?= $form->field($model_change_deed, 'notary_public')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'),  ['business-entity/view', 'id' => Yii::$app->hasher->encode($model_change_deed->id_business_entity)], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton($model_change_deed->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var helpBlocks = document.querySelectorAll('.help-block');

        helpBlocks.forEach(function(helpBlock) {
            var formControl = helpBlock.previousElementSibling;

            if (formControl && formControl.classList.contains('form-control')) {
                formControl.classList.add('error');
            }
        });
    });
</script>