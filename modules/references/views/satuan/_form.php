<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\references\models\RefSatuan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ref-satuan-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'name')->textarea(['rows' => 4])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'description')->textarea(['rows' => 4])?>
        </div>
    </div>
        
    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-warning']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
