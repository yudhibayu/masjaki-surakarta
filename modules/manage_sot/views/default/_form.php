<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\manage_sot\models\RefSot */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ref-sot-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class='col-md-6'>
            <?=$form->field($model, 'name')->textInput(['maxlength' => true])?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'note')->textarea(['rows' => 6])?>
        </div>
        <div class='col-md-3'>
            <?= $form->field($model, 'active')->dropDownList([ 'F' => Yii::t('app','No'), 'T' => Yii::t('app','Yes'), ], ['prompt' => Yii::t('app','Choose')]) ?>
        </div>
    </div>
        
    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-warning']) ?>
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
