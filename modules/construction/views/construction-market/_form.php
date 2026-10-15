<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\construction\models\ConstructionPlan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="construction-plan-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'rup_code')->textInput()?>
        </div>
        <div class='col-md-6'>
            <?=$form->field($model, 'ceiling_value')->textInput(['maxlength' => true])?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'work_unit')->textInput(['maxlength' => true])?>
        </div>
        <div class='col-md-6'>
            <?=$form->field($model, 'type_of_procurement')->textInput(['maxlength' => true])?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'procurement_method')->textInput(['maxlength' => true])?>
        </div>
        <div class='col-md-6'>
            <?=$form->field($model, 'election')->textInput()?>
        </div>
    </div>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>