<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Sign Up';
?>

<style>
    .site-signup {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .card {
        width: 400px;
        padding: 20px;
        border-radius: 10px;
    }
</style>

<div class="site-signup" style="margin-top: -100px; padding: 20px;">
    <div class="card">
        <h1><?= Html::encode($this->title) ?></h1>
        <p>Please fill out the following fields to sign up:</p>
        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'class' => 'form-control']) ?>

        <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control']) ?>

        <?= $form->field($model, 'role')->dropDownList([
            'admin' => 'Admin',
            'kasir' => 'Kasir',
            'viewer' => 'Viewer',
        ], ['prompt' => 'Pilih Role', 'class' => 'form-control']) ?>

        <div class="form-group">
            <?= Html::submitButton('Sign up', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
