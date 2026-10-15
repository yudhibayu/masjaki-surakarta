<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Button;


/**
 * @var yii\web\View $this
 * @var yii\bootstrap5\ActiveForm $form
 * @var infinindotech\user\models\RecoveryForm $model
 */

$this->title = Yii::t('user', 'Reset your password');
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    @import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css");

    .input-group-addon>input[type="checkbox"] {
        display: none;
        visibility: hidden;
        opacity: 0;
        position: absolute;
    }

    .hei {
        margin: 0px 1px 0px 1px;
    }
</style>
<div class="site-login">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="card shadow-lg border-0 user-log">
                <div class="d-flex justify-content-center">
                    <div class="brand_logo_container">
                        <a href="/auth/login"><img src="/images/logo-ska-circle.png" width="120px" class="brand_logo" style="border: 1px solid grey;border-radius:50%;" alt="Logo"></a>
                    </div>
                </div>
                <div class="card-body mt-5">
                    <div class="row text-center">
                        <!-- <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3"> -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
                            </div>
                            <div class="panel-body">
                                <?php $form = ActiveForm::begin([
                                    'layout' => 'floating',
                                    'id' => 'password-recovery-form',
                                    'enableAjaxValidation' => true,
                                    'enableClientValidation' => false,
                                ]); ?>

                                <?= $form->field($model, 'password', [
                                    'template' => '<b>{label}</b><div class="input-group">{input}
            <span style="padding: 6px; background-color: #ebebeb; border-top-right-radius: 3px; border-bottom-right-radius: 3px; border-top: 1px solid #ced4da; border-right: 1px solid #ced4da; border-bottom: 1px solid #ced4da" class="input-group-addon"><input type="checkbox" id="reveal-password" name="reveal-password" value="false"><label class="hei" for="reveal-password"><i id="icon" class="fa fa-eye"></i></label></span></div>{error}{hint}',
                                    'inputOptions' => ['class' => 'form-control', 'tabindex' => '2']
                                ])->passwordInput()->label(false) ?>

                                <?php
                                $this->registerJs("jQuery('#reveal-password').change(function(){jQuery('#recovery-form-password').attr('type',this.checked?'text':'password');
    jQuery('#icon').attr('class',this.checked?'fa fa-eye-slash':'fa fa-eye');})");
                                ?>

                                <?= Html::submitButton(Yii::t('user', 'Finish'), ['class' => 'btn btn-success btn-block mt-3']) ?><br>


                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                        <!-- </div> -->
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    body {
        background-color: #EDEDED;
        font-family: "Poppins", Arial, sans-serif;
    }

    .brand_logo_container {
        position: absolute;
        top: -75px;
        border-radius: 50%;
        background: #EDEDED;
        padding: 12px;
        text-align: center;
    }

    .form-group+.form-group {
        border-top: 0px;
        padding-top: 0px;
    }

    .form-control-sm {
        padding: 0.5rem;
        font-size: 0.875rem;
        border-radius: 0.5rem;
    }

    .form-check-input {
        width: 1rem;
        height: 1rem;
        margin-top: 0.3rem;
    }

    .error-message {
        margin-top: 5px;
        font-size: 12px;
        color: red;
    }
</style>