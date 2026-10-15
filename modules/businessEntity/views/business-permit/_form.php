<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\widgets\Select2;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;


/* @var $this yii\web\View */
/* @var $model app\modules\businessEntity\models\BusinessPermit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="business-permit-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model_business_permit, 'business_permit_type')->widget(Select2::class, [
                'data' => $dropdown_data['business_permit_type'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose Business Permit Type'), 'id' => 'education_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model_business_permit, 'reference_number')->textInput() ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model_business_permit, 'valid_until')->widget(DatePicker::classname(), [
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'start_date'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'todayHighlight' => true,
                    'format' => 'dd-mm-yyyy',
                    //'maxViewMode' => 'month',
                    //'minViewMode' => 'days',

                ],
            ]);
            ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model_business_permit, 'id_business_qualification')->widget(Select2::class, [
                'data' => $dropdown_data['construction_sbu'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose Business Qualification'), 'id' => 'id_business_qualification_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model_business_permit, 'grantor_institution')->textInput(['maxlength' => true]) ?>
        </div>
        <!-- <div class='col-md-6'>
            <= $form->field($model_business_permit, 'business_permit_file')->textInput(['maxlength' => true]) ?>
        </div> -->
    </div>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), ['business-entity/view', 'id' => Yii::$app->hasher->encode($model_business_permit->id_business_entity)], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton($model_business_permit->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>