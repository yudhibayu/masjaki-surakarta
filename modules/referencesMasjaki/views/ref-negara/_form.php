<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\referencesMasjaki\models\RefNegara */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ref-negara-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- <div class='row'>
        <div class='col-md-6'>
            <=$form->field($model, 'id')->textInput(['maxlength' => true])?>
        </div>
    </div> -->
        
    <div class='row'>
        <div class='col-md-8'>
            <?=$form->field($model, 'name')->textInput(['maxlength' => true])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-8'>
            <?=$form->field($model, 'iso')->textInput(['maxlength' => true])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-8'>
            <?=$form->field($model, 'iso3')->textInput(['maxlength' => true])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-8'>
            <?=$form->field($model, 'numcode')->textInput()?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-8'>
            <?=$form->field($model, 'phonecode')->textInput()?>
        </div>
    </div>
        
    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
