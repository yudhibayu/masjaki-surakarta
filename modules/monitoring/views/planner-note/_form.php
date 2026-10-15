<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\DatePicker;
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\models\MonitoringPlannerNote */
/* @var $form yii\widgets\ActiveForm */

if (!isset($is_create)) {
    $is_create = false;
}
?>

<div class="monitoring-planner-note-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-12'>
            <?= $form->field($model, 'name')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'order_no')->textInput() ?>
        </div>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'note_date')->widget(DatePicker::classname(), [
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'note_date'],
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

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'problem')->textarea(['rows' => 4]) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'solution')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-12'>
            <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <?php if ($is_create) : ?>
        <div class='row'>
            <div class='col-md-12'>
                <?=
                $form->field($model, 'file_uploads[]')->widget(FileInput::classname(), [
                    'options' => [
                        'accept' => implode(',', [
                            'image/webp',
                            'image/png',
                            'image/jpeg',
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-powerpoint',
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                        ]),
                        'multiple' => true,
                    ],
                    'pluginOptions' => [
                        'previewFileType' => 'image',
                        'showUpload' => false,
                        'resizePreference' => 'height',
                        'resizeImage' => true,
                        'resizeIfSizeMoreThan' => 1000,
                    ]
                ]);
                ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), ['default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)], ['class' => 'btn btn-warning']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>