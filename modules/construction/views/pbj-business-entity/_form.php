<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\widgets\Select2;


/* @var $this yii\web\View */
/* @var $model app\modules\construction\models\PbjBusinessEntity */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pbj-business-entity-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- <div class='row'>
        <div class='col-md-6'>
            <=$form->field($model, 'kode_rup')->textInput()?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <=$form->field($model, 'kode_pbj')->textInput()?>
        </div>
    </div> -->

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'id_business_entity')->widget(Select2::class, [
                'data' => $dropdown_data['id_business_entity'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Pilih Badan Usaha'), 'id' => 'id_business_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ])->label('Badan Usaha'); ?>
            <!-- <=$form->field($model, 'id_business_entity')->textInput()?> -->
        </div>
    </div>

    <!-- <div class='row'>
        <div class='col-md-6'>
            <=$form->field($model, 'name_business_entity')->textInput(['maxlength' => true])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <=$form->field($model, 'user_id')->dropDownList($dropdown_data['user_id'], ['prompt' => Yii::t('app', 'Choose')])?>
        </div>
    </div> -->

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>