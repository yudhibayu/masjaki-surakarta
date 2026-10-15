<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hitcounter\models\HitCounterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hit-counter-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'counter_id') ?>

    <?= $form->field($model, 'cookie_mark') ?>

    <?= $form->field($model, 'js_cookei_enabled') ?>

    <?= $form->field($model, 'js_java_enabled') ?>

    <?= $form->field($model, 'js_timezone_offset') ?>

    <?php // echo $form->field($model, 'js_timezone') ?>

    <?php // echo $form->field($model, 'js_connection') ?>

    <?php // echo $form->field($model, 'js_current_url') ?>

    <?php // echo $form->field($model, 'js_referer_url') ?>

    <?php // echo $form->field($model, 'js_screen_width') ?>

    <?php // echo $form->field($model, 'js_screen_height') ?>

    <?php // echo $form->field($model, 'js_color_depth') ?>

    <?php // echo $form->field($model, 'js_browser_language') ?>

    <?php // echo $form->field($model, 'js_history_length') ?>

    <?php // echo $form->field($model, 'js_is_toutch_device') ?>

    <?php // echo $form->field($model, 'js_processor_ram') ?>

    <?php // echo $form->field($model, 'serv_ip') ?>

    <?php // echo $form->field($model, 'serv_user_agent') ?>

    <?php // echo $form->field($model, 'serv_referer_url') ?>

    <?php // echo $form->field($model, 'serv_server_name') ?>

    <?php // echo $form->field($model, 'serv_auth_user_id') ?>

    <?php // echo $form->field($model, 'serv_port') ?>

    <?php // echo $form->field($model, 'serv_cookies') ?>

    <?php // echo $form->field($model, 'serv_os') ?>

    <?php // echo $form->field($model, 'serv_client') ?>

    <?php // echo $form->field($model, 'serv_device') ?>

    <?php // echo $form->field($model, 'serv_brand') ?>

    <?php // echo $form->field($model, 'serv_model') ?>

    <?php // echo $form->field($model, 'serv_bot') ?>

    <?php // echo $form->field($model, 'serv_host_by_ip') ?>

    <?php // echo $form->field($model, 'serv_is_proxy_or_vpn') ?>

    <?php // echo $form->field($model, 'created_at') ?>

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