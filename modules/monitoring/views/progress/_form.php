<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\DatePicker;
use kartik\number\NumberControl;
use kartik\widgets\DateTimePicker;
use kartik\widgets\RangeInput;

/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\models\MonitoringProgress */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="monitoring-progress-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
    ]); ?>

    <div class='row'>
        <div class='col-md-12'>
            <?= $form->field($model, 'name')->textInput() ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'order_no')->textInput() ?>
        </div>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'progress_date')->widget(DatePicker::classname(), [
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'progress_date'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'todayHighlight' => true,
                    'format' => 'yyyy-mm-dd',
                    //'maxViewMode' => 'month',
                    //'minViewMode' => 'days',

                ]
            ]);
            ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'planned_financial_budget')->widget(NumberControl::classname(), [
                'maskedInputOptions' => [
                    'removeMaskOnSubmit' => true,
                    //'prefix' => 'Rp. ',
                    'digits' => 4,
                    'groupSeparator' => '.',
                    'radixPoint' => ','
                ],
                'options' => [
                    'class' => 'planned_financial_budget',
                ],
            ]) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'planned_physical_percent')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'financial_budget')->widget(NumberControl::classname(), [
                'maskedInputOptions' => [
                    'removeMaskOnSubmit' => true,
                    //'prefix' => 'Rp. ',
                    'digits' => 4,
                    'groupSeparator' => '.',
                    'radixPoint' => ','
                ],
                'options' => [
                    'class' => 'financial_budget',
                ],
            ]) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'physical_percent')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-3'>
            <?= $form->field($model, 'working_hours_start')->widget(DateTimePicker::class, []) ?>
        </div>
        <div class='col-md-3'>
            <?= $form->field($model, 'working_hours_end')->widget(DateTimePicker::class, []) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'number_of_workers')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-12'>
            <?= $form->field($model, 'work_being_done')->textarea(['rows' => 4]) ?>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'work_items_of_potential_accident')->textarea(['rows' => 4]) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'work_items_of_occured_accident')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-4'>
            <?= $form->field($model, 'num_minor_accident')->textInput() ?>
        </div>
        <div class='col-md-4'>
            <?= $form->field($model, 'num_moderate_accident')->textInput() ?>
        </div>
        <div class='col-md-4'>
            <?= $form->field($model, 'num_serious_accident')->textInput() ?>
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
            <?= $form->field($model, 'notes')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-12'>
            <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), ['default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)], ['class' => 'btn btn-warning']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>