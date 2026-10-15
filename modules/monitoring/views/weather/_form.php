<?php

use kartik\widgets\DateTimePicker;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\Select2;


/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\models\MonitoringWeather */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="monitoring-weather-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Judul') ?>
        </div>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'ref_cuaca_id')->widget(Select2::class, [
                'data' => $dropdown_data['ref_cuaca_id'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'ref_cuaca_id_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'time_from')->widget(DateTimePicker::class, [
                'options' => ['placeholder' => Yii::t('app', 'Choose')],
            ]) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'time_to')->widget(DateTimePicker::class, [
                'options' => ['placeholder' => Yii::t('app', 'Choose')],
            ]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-12'>
            <?= $form->field($model, 'note')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), ['progress/view', 'id' => Yii::$app->hasher->encode($model->monitoringProgress->id)], ['class' => 'btn btn-warning']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>