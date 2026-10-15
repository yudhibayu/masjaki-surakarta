<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\number\NumberControl;


/* @var $this yii\web\View */
/* @var $model app\modules\businessEntity\models\ExperienceBusinessEntity */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="experience-business-entity-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'work_package_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'field_work')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'assignor_name_work')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'assignor_address_work')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'contract_number')->textInput() ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'contract_value')->widget(NumberControl::classname(), [
                'maskedInputOptions' => [
                    'removeMaskOnSubmit' => true,
                    'prefix' => 'Rp. ',
                    'digits' => 2,
                    'groupSeparator' => '.',
                    'radixPoint' => ','
                ],
                'options' => [
                    'id' => 'start',
                ],
            ])->label('Nilai Kontrak'); ?>
        </div>
    </div>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), ['business-entity/view', 'id' => Yii::$app->hasher->encode($model->id_business_entity)], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>