<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\rup\models\PbjSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pbj-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'kodelelang') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'budget') ?>

    <?= $form->field($model, 'hps') ?>

    <?= $form->field($model, 'vendor_name') ?>

    <?php // echo $form->field($model, 'sppbj_no') ?>

    <?php // echo $form->field($model, 'contract_no') ?>

    <?php // echo $form->field($model, 'contract_date') ?>

    <?php // echo $form->field($model, 'contract_duration') ?>

    <?php // echo $form->field($model, 'contract_price') ?>

    <?php // echo $form->field($model, 'contract_start_date') ?>

    <?php // echo $form->field($model, 'contract_end_date') ?>

    <?php // echo $form->field($model, 'work_start_date') ?>

    <?php // echo $form->field($model, 'work_finished_date') ?>

    <?php // echo $form->field($model, 'program') ?>

    <?php // echo $form->field($model, 'activity') ?>

    <?php // echo $form->field($model, 'sub_activity') ?>

    <?php // echo $form->field($model, 'namasatker') ?>

    <?php // echo $form->field($model, 'kodesatker') ?>

    <?php // echo $form->field($model, 'idmetodepengadaan') ?>

    <?php // echo $form->field($model, 'metodepengadaan') ?>

    <?php // echo $form->field($model, 'ppk_id') ?>

    <?php // echo $form->field($model, 'ppk_name') ?>

    <?php // echo $form->field($model, 'ppk_nip') ?>

    <?php // echo $form->field($model, 'fiscal_year') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary form-reset']) ?>
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