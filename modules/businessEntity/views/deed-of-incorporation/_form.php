<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\widgets\DatePicker;
use newerton\fancybox3\FancyBox;


/* @var $this yii\web\View */
/* @var $model app\modules\businessEntity\models\DeedOfIncorporation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="deed-of-incorporation-form">
    <?php
    echo FancyBox::widget([
        'target' => '[img_gallery]',
    ]);
    ?>
    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model_deed_of_incorporation, 'deed_number')->textInput() ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model_deed_of_incorporation, 'date_of_deed')->widget(DatePicker::classname(), [
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
            if (!empty($model_deed_of_incorporation->file_name)) {
                $ext = pathinfo($model_deed_of_incorporation->file_name, PATHINFO_EXTENSION);
                $b = '';
                if ($ext == 'pdf') {
                    $b = Html::a(
                        '<i class="bx bxs-file-pdf"></i> Preview PDF',
                        '/business-entity/deed-of-incorporation/fetch-file?id=' . Yii::$app->hasher->encode($model_deed_of_incorporation->id),
                        [
                            'target' => '_blank',
                            'class' => 'badge badge-warning',
                        ]
                    );
                } else {
                    $b = Html::encode($model_deed_of_incorporation->file_name);
                }
                echo 'File Sebelumnya: ';
                echo '<p>' . Html::a(
                    $b,
                    '/business-entity/deed-of-incorporation/fetch-file?id=' . Yii::$app->hasher->encode($model_deed_of_incorporation->id),
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
            <?= $form->field($model_deed_of_incorporation, 'deed_file')->widget(FileInput::class, [
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
                    'initialPreview' => $model_deed_of_incorporation->id && $model_deed_of_incorporation->file_path ? [
                        Html::a(
                            '<i class="bx bxs-file-pdf"></i> Preview PDF',
                            '/business-entity/deed-of-incorporation/fetch-file?id=' . Yii::$app->hasher->encode($model_deed_of_incorporation->id),
                            [
                                'target' => '_blank',
                                'class' => 'badge badge-warning',
                            ]
                        )
                    ] : [],
                ]
            ])->hint('Ekstensi file pdf. Ukuran maksimal file 150Kb');
            ?> </div>
        <div class='col-md-6'>
            <?= $form->field($model_deed_of_incorporation, 'notary_public')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'),  ['business-entity/view', 'id' => Yii::$app->hasher->encode($model_deed_of_incorporation->id_business_entity)], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton($model_deed_of_incorporation->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>