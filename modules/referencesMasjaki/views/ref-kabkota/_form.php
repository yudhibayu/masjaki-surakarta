<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\widgets\Select2;


/* @var $this yii\web\View */
/* @var $model app\modules\referencesMasjaki\models\RefKabkota */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ref-kabkota-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- <div class='row'>
        <div class='col-md-6'>
            <=$form->field($model, 'id')->textInput(['maxlength' => true])?>
        </div>
    </div> -->

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'ref_provinsi_id')->dropDownList($dropdown_data['ref_provinsi_id'], ['prompt' => Yii::t('app', 'Choose')]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'id_jenis')->dropDownList(['1' => Yii::t('app', 'Kabupaten'), '2' => Yii::t('app', 'Kota'),], ['prompt' => Yii::t('app', 'Choose')])?>
        </div>
    </div>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>