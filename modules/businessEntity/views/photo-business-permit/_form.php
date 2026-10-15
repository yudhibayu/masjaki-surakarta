<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use newerton\fancybox3\FancyBox;


/* @var $this yii\web\View */
/* @var $model app\modules\businessEntity\models\PhotoBusinessPermit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="photo-business-permit-form">
    <?php
    echo FancyBox::widget([
        'target' => '[img_gallery]',
    ]);
    ?>
    <?php $form = ActiveForm::begin(); ?>

    <!-- <div class='row'>
        <div class='col-md-6'>
            <php
            if (!empty($model->file_name)) {
                $ext = pathinfo($model->file_name, PATHINFO_EXTENSION);
                $b = '';
                if ($ext == 'pdf') {
                    $b = Html::a(
                        '<i class="bx bxs-file-pdf"></i> Preview PDF',
                        '/business-entity/photo-business-permit/fetch-file?id=' . Yii::$app->hasher->encode($model->id),
                        [
                            'target' => '_blank',
                            'class' => 'badge badge-warning',
                        ]
                    );
                } else {
                    $b = Html::encode($model->file_name);
                }
                echo 'File Sebelumnya: ';
                echo '<p>' . Html::a(
                    $b,
                    '/business-entity/photo-business-permit/fetch-file?id=' . Yii::$app->hasher->encode($model->id),
                    [
                        'target' => $ext == 'pdf' ? '_blank' : null
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
                    'accept' => 'application/pdf',
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
                    'initialPreview' => $model->id && $model->file_path ? [
                        Html::a(
                            '<i class="bx bxs-file-pdf"></i> Preview PDF',
                            '/business-entity/photo-business-permit/fetch-file?id=' . Yii::$app->hasher->encode($model->id),
                            [
                                'target' => '_blank',
                                'class' => 'badge badge-warning',
                            ]
                        )
                    ] : [],
                ]
            ])->hint('Ekstensi file pdf. Ukuran maksimal file 150Kb');
            ?>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-8'>
            <?= $form->field($model, 'description')->textarea(['row' => 4]) ?>
        </div>
    </div>
    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), ['business-permit/view', 'id' => Yii::$app->hasher->encode($model->id_business_permit)], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>