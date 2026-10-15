<?php

use kartik\password\PasswordInput;
use kartik\select2\Select2;
use yii\captcha\Captcha;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\frontMap\models\TblAccountRequest */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-account-request-form">

    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
    ]); ?>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'requested_account_type')->widget(Select2::classname(), [
                'data' => $model->requestAccountLabels(),
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'requested_account_type_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'password')->widget(PasswordInput::class)  ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'password_confirm')->widget(PasswordInput::class) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-12'>
            <?= $form->field($model, 'note')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                'captchaAction' => '/user/security/captcha',
                'template' => '<div class="row"><div class="col-xs-12 col-12 text-muted text-sm-right font-weight-light font-italic"><small><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Klik gambar untuk ganti captcha</small></div><div class="col-lg-4">{image}</div><div class="col-lg-8">{input}</div></div>',
            ]) ?>
        </div>
    </div>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-warning']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>