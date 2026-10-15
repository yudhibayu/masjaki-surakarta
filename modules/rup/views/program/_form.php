<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\rup\models\RefProgram */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ref-program-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'id')->textInput() ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'name')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'kd_program')->textInput() ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'kd_program_str')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'kd_satker')->textInput() ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'pagu')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'fiscal_year')->textInput() ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'kd_program_lokal')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-warning']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>