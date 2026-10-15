<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\construction\models\ConstructionVacanciesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="construction-vacancies-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'construction_work') ?>

    <?= $form->field($model, 'skills') ?>

    <?= $form->field($model, 'education') ?>

    <?php // echo $form->field($model, 'age_range') ?>

    <?php // echo $form->field($model, 'work_experience') ?>

    <?php // echo $form->field($model, 'quota') ?>

    <?php // echo $form->field($model, 'salary_range') ?>

    <?php // echo $form->field($model, 'start_date') ?>

    <?php // echo $form->field($model, 'date_of_completion') ?>

    <?php // echo $form->field($model, 'type_of_work') ?>

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