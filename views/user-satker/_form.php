<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;


/* @var $this yii\web\View */
/* @var $model app\models\UserSatker */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-satker-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-3'>
            <?=
            $form->field($model, 'user_id')->widget(Select2::classname(), [
                'data' => $dropdown_data['user_id'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'user_id_select'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>

        <div class='col-md-3'>
            <?=
            $form->field($model, 'fiscal_year')->widget(Select2::classname(), [
                'data' => $dropdown_data['fiscal_year'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'fiscal_year_select'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
    </div>

    <div class="row">
        <div class='col-md-6'>
            <?=
            $form->field($model, 'satker_id')->widget(DepDrop::classname(), [
                'options' => [
                    'placeholder' => Yii::t('app', 'Choose'),
                    'id' => 'satker_id_select'
                ],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                'pluginOptions' => [
                    'depends' => ['fiscal_year_select'],
                    'initialize' => true,
                    'url' => Url::to(['/user-satker/unit']),
                    'loadingText' => Yii::t('app', 'Loading...'),
                ]
            ]);
            ?>

        </div>
    </div>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-warning']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>