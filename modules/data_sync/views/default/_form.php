<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Inflector;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\sync_rup_penyedia\models\SyncLog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sync-log-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-3'>
            <?=$form->field($model, 'name')->textInput(['maxlength' => true])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-3'>
            <?=$form->field($model, 'record_total')->textInput()?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-3'>
            <?=$form->field($model, 'queue_total')->textInput()?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-3'>
            <?=$form->field($model, 'queue_finished')->textInput()?>
        </div>
    </div>
        
    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-warning']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
