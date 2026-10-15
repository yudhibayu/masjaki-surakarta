<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\construction\models\LaborSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="labor-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'nik_passport_number') ?>

    <?= $form->field($model, 'npwp') ?>

    <?= $form->field($model, 'phone_number') ?>

    <?= $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'skilled_power_type') ?>

    <?php // echo $form->field($model, 'citizenship') ?>

    <?php // echo $form->field($model, 'bpjs_health_number') ?>

    <?php // echo $form->field($model, 'employment_bpjs_number') ?>

    <?php // echo $form->field($model, 'last_education') ?>

    <?php // echo $form->field($model, 'long_work_experience') ?>

    <?php // echo $form->field($model, 'employment_status') ?>

    <?php // echo $form->field($model, 'professional_expertise') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'subdistrict') ?>

    <?php // echo $form->field($model, 'ward') ?>

    <?php // echo $form->field($model, 'created_at') ?>

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