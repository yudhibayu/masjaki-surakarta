<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\widgets\Select2;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\modules\businessEntity\models\BusinessClassification */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="business-classification-form">

    <?php $form = ActiveForm::begin(); ?>


    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'id_kbli')->widget(Select2::class, [
                'data' => $dropdown_data['id_kbli'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'kbli_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?>
        </div>
    </div>
    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), ['business-permit/view', 'id' => Yii::$app->hasher->encode($model->id_business_permit)], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>