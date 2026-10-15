<?php

use kartik\file\FileInput;
use kartik\widgets\ColorInput;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\references\models\ObjectType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="object-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'map_color')->widget(ColorInput::class, [
                'options' => ['placeholder' => 'Pilih Warna ...'],
            ])
            ?>
        </div>
        <div class='col-md-12'>
            <?=
            $form->field($model, 'uploadimage')->widget(FileInput::classname(), [
                'options' => [
                    'accept' => 'image/*',
                ],
                'pluginOptions' => [
                    'initialPreview' => [
                        Url::to($model->folderurl,true).'/'.$model->icon,
                    ],
                    // 'overwriteInitial' => true,
                    'initialPreviewAsData'=>true,
                    'previewFileType' => 'image',
                    'showUpload' => false,
                    'resizePreference' => 'height',
                    'resizeImage' => true,
                    'resizeIfSizeMoreThan' => 1000,
                    'maxFileSize' => 1000,
                ]
            ]);
            ?>
        </div>
    </div>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-warning']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>