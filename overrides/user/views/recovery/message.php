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

/**
 * @var yii\web\View $this
 * @var yii\bootstrap5\ActiveForm $form
 * @var infinindotech\user\models\RecoveryForm $model
 */

if (isset($title)) {
    $this->title = $title;
} else {
    $this->title = Yii::t('user', 'Recover your password');
}
$this->params['breadcrumbs'][] = $this->title;
?>
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
                                <p>
                                    <?php
                                    if ($this->title == \Yii::t('user', 'Recovery message sent')) {
                                        echo "Silahkan cek dan klik link email yang telah anda dapatkan.";
                                    } else if ($this->title == \Yii::t('user', 'Password has been changed')) {
                                        echo "Silahkan login dengan password baru.";
                                    } else {
                                        echo "Silahkan login atau menuju beranda.";
                                    }
                                    ?>
                                </p>
                                <p>
                                    <?= Html::a(Yii::t('app', 'Login'), "/auth/login", ['class' => 'btn btn-success mt-3']) ?>
                                    <?= Html::a(Yii::t('app', 'Home'), "/", ['class' => 'btn btn-warning mt-3']) ?>
                                </p>
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