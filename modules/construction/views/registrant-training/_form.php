<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\widgets\Select2;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\modules\construction\models\RegistrantTraining */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="registrant-training-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <!-- <div class='col-md-6'>
            <= $form->field($model, 'id_training')->widget(Select2::class, [
                'data' => $dropdown_data['id_training'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Pilih Pelatihan'), 'id' => 'id_training_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?>
        </div> -->
        <!-- </div>

    <div class='row'>
        <div class='col-md-6'>
            <= $form->field($model, 'name_training')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class='row'> -->
        <div class='col-md-6'>
            <?= $form->field($model, 'id_labor')->widget(Select2::class, [
                'data' => $dropdown_data['id_labor'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Pilih Tenaga Kerja'), 'id' => 'id_labor_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?>
        </div>
    </div>

    <!-- <div class='row'>
        <div class='col-md-6'>
            <= $form->field($model, 'name_labor')->textInput(['maxlength' => true]) ?>
        </div>
    </div> -->

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>