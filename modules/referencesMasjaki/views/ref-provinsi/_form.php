<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\widgets\Select2;


/* @var $this yii\web\View */
/* @var $model app\modules\referencesMasjaki\models\RefProvinsi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ref-provinsi-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-8'>
            <?= $form->field($model, 'ref_negara_id')->widget(Select2::class, [
                'data' => $dropdown_data['ref_negara_id'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Pilih Negara '), 'id' => 'country_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-8'>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>