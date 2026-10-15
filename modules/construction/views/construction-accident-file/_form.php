<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\widgets\Select2;
use kartik\widgets\FileInput;


/* @var $this yii\web\View */
/* @var $model app\modules\construction\models\ConstructionAccidentFile */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="construction-accident-file-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'description')->textarea(['rows' => 2]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-12'>
            <?=
            $form->field($model, 'file_name')->widget(FileInput::classname(), [
                'language' => 'id',
                'options' => [
                    'id' => 'file-accident',
                    'multiple' => false,
                    'accept' => 'application/pdf',
                    // 'accept' => 'image/jpeg, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                ],
                'pluginOptions' => [
                    // 'previewFileType' => 'image',
                    'showUpload' => false,
                    'resizePreference' => 'height',
                    'resizeImage' => true,
                    'resizeIfSizeMoreThan' => 1000,
                    'maxFileSize' => 150,
                    'maxFileCount' => 1,
                    'allowedFileExtensions' => ['pdf'],
                ]
            ])->label(Yii::t('app', 'File Upload'));
            ?>
        </div>
    </div>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), ['construction-accident/view', 'id' => Yii::$app->hasher->encode($model->construction_accident_id)], ['class' => 'btn btn-warning']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>