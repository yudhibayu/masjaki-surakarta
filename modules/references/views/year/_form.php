<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\Button;
use yii\bootstrap5\ActiveForm;
use app\modules\references\References;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\references\Reftahun */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reftahun-form">

    <?php $form = ActiveForm::begin(
        [
            'fieldConfig' => [
                'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                'horizontalCssClasses' => [
                    'label' => 'col-sm-4',
                    'offset' => 'offset-sm-4',
                    'wrapper' => 'col-sm-8',
                    'error' => '',
                    'hint' => '',
                ],
            ],
        ]
    ); ?>
    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($model, 'name')->textInput() ?>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($model, 'active')->dropDownList(['F' => References::t('year_lang', 'No'), 'T' => References::t('year_lang', 'Yes'),], ['prompt' => '']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($model, 'active_login')->dropDownList(['F' => References::t('year_lang', 'No'), 'T' => References::t('year_lang', 'Yes'),], ['prompt' => '']) ?>
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

        <div class="row">
            <div class="col-sm-12">
                <?= Html::submitButton(References::t('year_lang', 'Save'), ['class' => 'btn btn-success float-end']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>

    </div>