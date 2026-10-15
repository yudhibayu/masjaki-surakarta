<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\models\MonitoringProgressSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="monitoring-progress-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'order_no') ?>

    <?= $form->field($model, 'monitoring_task_id') ?>

    <?= $form->field($model, 'monitoring_task_name') ?>

    <?= $form->field($model, 'progress_date') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'planned_financial_budget') ?>

    <?php // echo $form->field($model, 'planned_physical_percent') ?>

    <?php // echo $form->field($model, 'financial_budget') ?>

    <?php // echo $form->field($model, 'physical_percent') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary form-reset']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php 
$this->registerJs( 
<<< EOT_JS_CODE

$(document).ready(function(){
    $('.form-reset').on('click', function () {
        $.ajax({
            url:"reset_session",
            type: "POST",
            //data:"",
            success:function(data){
            }
        });
    });
});

EOT_JS_CODE

);
?>