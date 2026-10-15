<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap5\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-login">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100" style="margin-bottom: -20px;">
            <div class="col-4 col-4 p-0" style="background-color: #fff;border-radius:20px;width:23rem;">
                <div class="card shadow-lg border-0" style="border-radius: 20px;padding: 10px 10px 0 10px;margin-bottom:0px;width:23rem;">
                    <div class="d-flex justify-content-center p-2">
                        <div class="brand_logo_container">
                            <a href="/site/login"><img src="/images/logo-ska-circle.png" width="120px" class="brand_logo" style="border: 1px solid grey;border-radius:50%;" alt="Logo"></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php $form = ActiveForm::begin([
                            'id' => 'login-form',
                            'layout' => 'horizontal',
                            'fieldConfig' => [
                                'template' => "<div class=\"\">{input}{error}</div>",
                            ],
                        ]); ?>
                        <div class="form-group">
                            <?= Html::label('Username', 'username-input', ['class' => 'form-label text-bold']) ?>
                            <?= $form->field($model, 'username')->textInput([
                                'autofocus' => true,
                                'class' => 'form-control form-control-sm',
                                'id' => 'username-input'
                            ])->label(false) ?>
                        </div>

                        <div class="form-group">
                            <?= Html::label('Password', 'password-input', ['class' => 'form-label']) ?>
                            <?= Html::a('(lupa?)', ['site/forgot-password'], ['class' => 'form-link']) ?>
                        </div>
                        <div class="input-group">
                            <?= Html::activePasswordInput($model, 'password', [
                                'class' => 'form-control form-control-sm',
                                'id' => 'password-input',
                                'aria-describedby' => 'password',
                                'onChange' => 'lapan()'
                            ]) ?>
                            <span class="input-group-text cursor-pointer" id="togglePassword" style="border-radius: 0.5rem; border-top-left-radius: 0; border-bottom-left-radius: 0;"><i class="fa fa-eye"></i></span>
                        </div>
                        <div class="form-group mt-3">
                            <div class="d-flex align-items-center">
                                <span class="fa fa-info-circle me-2"></span>
                                <p class="fw-bold" style="font-family: Courier New; margin: 0;font-size:13px;">ketuk Funtuk merefresh captcha</p>
                            </div>
                            <div class="captcha-container mb-2">
                                <div class="d-flex align-items-center">
                                    <canvas id="captcha-image" width="160" height="60" class="captcha-image me-2" onclick="refreshCaptcha()"></canvas>
                                </div>
                                <input type="text" id="captcha-input" name="captcha" class="form-control form-control-sm" placeholder="Captcha" required>
                            </div>
                            <div id="error-message" class="error-message"></div>
                        </div>
                        <button type="submit" class="btn btn-success btn-block mb-3" tabindex="5" style="width: 100%;">Masuk</button>
                        <?= Html::a('<i class="fa fa-arrow-left me-1"></i> Back to Home', ['/site/index']) ?>

                    </div>
                    <?php ActiveForm::end(); ?>
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

    .captcha-container {
        display: flex;
        align-items: center;
        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
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

<script>
    function lapan() {
        var password = $('#password-input').val();
        if (password.length < 8 || /\s/.test(password)) {
            alert('Password tidak boleh mengandung spasi dan harus memiliki minimal 8 karakter');
            $('#password-input').val('');
            return false;
        }
    }
    const canvas = document.getElementById("captcha-image");
    const inputBox = document.getElementById("captcha-input");
    let captchaText = "";

    function generateCaptcha() {
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz23456789';
        let captcha = '';
        for (let i = 0; i < 6; i++) {
            const randomIndex = Math.floor(Math.random() * characters.length);
            captcha += characters[randomIndex];
        }
        return captcha;
    }

    function drawCaptcha(text) {
        const ctx = canvas.getContext("2d");
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.font = "23px Times New Roman";
        const xInitialSpace = 20;
        const letterSpace = 130 / text.length;
        const colors = ["#333333", "#666666", "#999999", "#CCCCCC", "#050C9C"];

        for (let i = 0; i < text.length; i++) {
            const randomColorIndex = Math.floor(Math.random() * colors.length);
            ctx.fillStyle = colors[randomColorIndex];
            ctx.fillText(text[i], xInitialSpace + i * letterSpace, Math.floor(Math.random() * (30 - 15 + 1) + 15));
        }
    }

    function refreshCaptcha() {
        captchaText = generateCaptcha();
        drawCaptcha(captchaText);
        inputBox.value = '';
        document.getElementById('error-message').textContent = '';
        document.getElementById('submitButton').disabled = true;
    }

    window.onload = function() {
        refreshCaptcha();
    };

    inputBox.addEventListener('input', function() {
        const errorMessage = document.getElementById('error-message');
        if (inputBox.value === captchaText) {
            errorMessage.textContent = 'CAPTCHA is correct!';
            errorMessage.style.color = 'green';
            document.getElementById('submitButton').disabled = false;
        } else {
            errorMessage.textContent = 'CAPTCHA is incorrect. Please try again.';
            errorMessage.style.color = 'red';
            document.getElementById('submitButton').disabled = true;
        }
    });

    document.getElementById('captcha-image').addEventListener('dblclick', function(event) {
        event.preventDefault();
        return false;
    });

    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password-input');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        const toggleIcon = document.querySelector("#togglePassword i");
        toggleIcon.classList.toggle('fa-eye-slash');
        toggleIcon.classList.toggle('fa fa-eye');
    });
</script>