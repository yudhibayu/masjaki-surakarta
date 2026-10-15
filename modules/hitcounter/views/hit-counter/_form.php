<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\widgets\Select2;
use kartik\widgets\TimePicker;


/* @var $this yii\web\View */
/* @var $model app\modules\hitcounter\models\HitCounter */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hit-counter-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'counter_id')->dropDownList($dropdown_data['counter_id'], ['prompt' => Yii::t('app', 'Choose')])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'cookie_mark')->textInput(['maxlength' => true])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'js_cookei_enabled')->textInput()?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'js_java_enabled')->textInput()?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'js_timezone_offset')->widget(TimePicker::classname(), [
                'options' => ['placeholder' =>Yii::t('app', 'Choose'), 'id'=>'js_timezone_offset'],
                'pluginOptions' => [
                    'showMeridian' => false,
                    'showSeconds' => false,
                    'minuteStep' => 5,
                    'defaultTime' => 'current',
                ]
            ]);
            ?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'js_timezone')->widget(TimePicker::classname(), [
                'options' => ['placeholder' =>Yii::t('app', 'Choose'), 'id'=>'js_timezone'],
                'pluginOptions' => [
                    'showMeridian' => false,
                    'showSeconds' => false,
                    'minuteStep' => 5,
                    'defaultTime' => 'current',
                ]
            ]);
            ?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'js_connection')->textInput(['maxlength' => true])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'js_current_url')->textarea(['rows' => 4])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'js_referer_url')->textarea(['rows' => 4])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'js_screen_width')->textInput()?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'js_screen_height')->textInput()?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'js_color_depth')->textInput()?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'js_browser_language')->textInput(['maxlength' => true])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'js_history_length')->textInput()?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'js_is_toutch_device')->textInput()?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'js_processor_ram')->textInput()?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'serv_ip')->textInput(['maxlength' => true])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'serv_user_agent')->textarea(['rows' => 4])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'serv_referer_url')->textarea(['rows' => 4])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'serv_auth_user_id')->dropDownList($dropdown_data['serv_auth_user_id'], ['prompt' => Yii::t('app', 'Choose')])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'serv_port')->textInput()?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'serv_cookies')->textInput()?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'serv_os')->textInput(['maxlength' => true])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'serv_client')->textInput(['maxlength' => true])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'serv_device')->textInput(['maxlength' => true])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'serv_brand')->textInput(['maxlength' => true])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'serv_model')->textInput(['maxlength' => true])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'serv_bot')->textInput(['maxlength' => true])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'serv_host_by_ip')->textInput(['maxlength' => true])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?=$form->field($model, 'serv_is_proxy_or_vpn')->textInput()?>
        </div>
    </div>
        
    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
