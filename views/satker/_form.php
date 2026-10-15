<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Satker */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="satker-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
                <?=
                $form->field($model, 'ref_sot_id')->widget(Select2::classname(), [
                    'data' => $dropdown_data['ref_sot_id'],
                    'language' => 'id',
                    'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'ref_sot_id_select'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            <?php // echo $form->field($model, 'fiscal_year')->textInput()
            ?>
        </div>
    </div>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-warning']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>