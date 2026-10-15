<?php

use kartik\widgets\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Satker */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="satker-assign-form">

    <?php $form = ActiveForm::begin(); ?>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'satker_id')->widget(Select2::classname(), [
                'data' => $dropdown_data['satker'],
                'language' => 'id',
                'options' => ['placeholder' =>Yii::t('app', 'Choose Data...'), 'id'=>'satker_id_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);?>
        </div>
    </div>
        
    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-warning']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
