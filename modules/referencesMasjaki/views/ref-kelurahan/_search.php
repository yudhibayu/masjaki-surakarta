<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\referencesMasjaki\models\RefKelurahanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ref-kelurahan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'ref_kecamatan_id') ?>

    <?= $form->field($model, 'ref_kecamatan_name') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'id_jenis') ?>

    <?= $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

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