<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\widgets\Select2;
use kartik\widgets\FileInput;


/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\modules\pictures\models\MonitoringTaskPictures */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="monitoring-task-pictures-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
    ]); ?>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'name')->textInput() ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'monitoring_task_locations_id')->widget(Select2::classname(), [
                'data' => $dropdown_data['task_locations'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'monitoring_task_locations_id_select'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'uploadimage')->widget(FileInput::classname(), [
                'options' => [
                    'accept' => implode(',', [
                        'image/*',
                        'video/mp4'
                    ]),
                ],
                'pluginOptions' => [
                    // 'previewFileType' => 'image',
                    'showUpload' => false,
                    // 'resizePreference' => 'height',
                    // 'resizeImage' => true,
                    // 'resizeIfSizeMoreThan' => 1000,
                ]
            ])->label('Unggah File');
            ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), ['/monitoring/progress/view', 'id' => Yii::$app->hasher->encode($model->monitoring_progress_id)], ['class' => 'btn btn-warning']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>