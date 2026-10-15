<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\businessEntity\models\ChangeDeedSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="change-deed-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'deed_number') ?>

    <?= $form->field($model, 'date_of_deed') ?>

    <?= $form->field($model, 'notary_public') ?>

    <?= $form->field($model, 'deed_file') ?>

    <?= $form->field($model, 'craeted_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary form-reset']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php 
$this->registerJs( 
<<< EOT_JS_CODE

$(document).ready(function(){
    $('.form-reset').on('click', function () {
        $.ajax({
            url:"reset_session",
            type: "POST",
            //data:"",
            success:function(data){
            }
        });
    });
});

EOT_JS_CODE

);
?>