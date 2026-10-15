<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\widgets\Select2;


/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\models\SharedMonitoringReport */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shared-monitoring-report-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'task_id')->dropDownList($dropdown_data['task_id'], ['prompt' => Yii::t('app', 'Choose')])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'type')->textInput(['maxlength' => true])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'month')->textInput(['maxlength' => true])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'week')->textInput(['maxlength' => true])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'day')->textInput(['maxlength' => true])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'is_spec')->textInput()?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'expired_at')->textInput()?>
        </div>
    </div>
        
    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-warning']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
