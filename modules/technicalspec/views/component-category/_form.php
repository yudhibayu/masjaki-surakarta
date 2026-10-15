<?php

use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\technicalspec\models\RefComponentCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ref-component-category-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-12'>
            <?=
            $form->field($model, 'ref_objecttype_id')->widget(Select2::class, [
                'data' => $dropdown_data['ref_objecttype_id'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'ref_objecttype_id_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-12'>
            <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-warning']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>