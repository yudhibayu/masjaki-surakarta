<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use infinindotech\user\widgets\Connect;
use app\overrides\user\models\LoginForm;
use yii\captcha\Captcha;

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\LoginForm $model
 * @var dektrium\user\Module $module
 */

$this->title = Yii::t('user', 'Sign in');

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
<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

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
                        <div class="row">
                            <!-- <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3"> -->
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <?php $form = ActiveForm::begin([
                                        'id' => 'login-form',
                                        'enableAjaxValidation' => false,
                                        'enableClientValidation' => false,
                                        'validateOnBlur' => false,
                                        'validateOnType' => false,
                                        'validateOnChange' => false,
                                    ]) ?>

                                    <?php if ($module->debug) : ?>
                                        <?= $form->field($model, 'login', [
                                            'inputOptions' => [
                                                'autofocus' => 'autofocus',
                                                'class' => 'form-control',
                                                'tabindex' => '1'
                                            ]
                                        ])->dropDownList(LoginForm::loginList());
                                        ?>

                                    <?php else : ?>

                                        <?= $form->field(
                                            $model,
                                            'login',
                                            ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control', 'tabindex' => '1']]
                                        );
                                        ?>

                                    <?php endif ?>

                                    <?php if ($module->debug) : ?>
                                        <div class="alert alert-warning">
                                            <?= Yii::t('user', 'Password is not necessary because the module is in DEBUG mode.'); ?>
                                        </div>
                                    <?php else : ?>
                                        <?= $form->field(
                                            $model,
                                            'password',
                                            [
                                                'template' => '<b>{label}</b><div class="input-group">{input} <span style="padding: 6px; background-color: #ebebeb; border-top-right-radius: 3px; border-bottom-right-radius: 3px; border-top: 1px solid #ced4da; border-right: 1px solid #ced4da; border-bottom: 1px solid #ced4da" class="input-group-addon"><input type="checkbox" id="reveal-password" name="reveal-password" value="false"><label class="hei" for="reveal-password"><i id="icon" class="fa fa-eye"></i></label></span></div>{error}{hint}',
                                                'inputOptions' => ['class' => 'form-control', 'tabindex' => '2']
                                            ]
                                        )
                                            ->passwordInput()
                                            ->label(
                                                Yii::t('user', 'Password')
                                                    . ($module->enablePasswordRecovery ?
                                                        ' (' . Html::a(
                                                            Yii::t('user', 'Forgot password?'),
                                                            ['/user/recovery/request'],
                                                            ['tabindex' => '5']
                                                        )
                                                        . ')' : '')
                                            ) ?>
                                        <?php
                                        $this->registerJs("jQuery('#reveal-password').change(function(){jQuery('#login-form-password').attr('type',this.checked?'text':'password'); jQuery('#icon').attr('class',this.checked?'fa fa-eye-slash':'fa fa-eye');})");
                                        ?>
                                    <?php endif ?>

                                    <?php
                                    if (Yii::$app->params['enableYearLogin']) {
                                        echo $form->field($model, 'tahun')->dropdownList(
                                            $tahuns,
                                            ['tabindex' => '3', 'prompt' => Yii::t('app', 'Choose')]
                                        );
                                    } ?>

                                    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                                        'captchaAction' => '/user/security/captcha',
                                        'template' => '<div class="row"><div class="col-xs-12 col-12 text-muted text-sm-right font-weight-light font-italic"><small><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Klik gambar untuk ganti captcha</small></div><div class="col-lg-4">{image}</div><div class="col-lg-8">{input}</div></div>',
                                    ]) ?>

                                    <!-- <= $form->field($model, 'rememberMe')->checkbox(['tabindex' => '4']) ?> -->

                                    <?= Html::submitButton(
                                        Yii::t('user', 'Sign in'),
                                        ['class' => 'btn btn-success btn-block', 'tabindex' => '5']
                                    ) ?>

                                    <?php ActiveForm::end(); ?>
                                    <br>

                                    <?php if ($module->enableConfirmation) : ?>
                                        <p class="text-center">
                                            <?= Html::a(Yii::t('user', 'Didn\'t receive confirmation message?'), ['/user/registration/resend']) ?>
                                        </p>
                                    <?php endif ?>
                                    <?php if ($module->enableRegistration) : ?>
                                        <p class="text-center">
                                            <?= Html::a(Yii::t('user', 'Don\'t have an account? Sign up!'), ['/user/registration/register']) ?>
                                        </p>
                                    <?php endif ?>
                                    <?= Connect::widget([
                                        'baseAuthUrl' => ['/user/security/auth'],
                                    ]) ?>
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