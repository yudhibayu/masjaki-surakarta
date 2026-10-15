<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use app\widgets\Alert;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use kartik\password\PasswordInput;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\SettingsForm $model
 */

$this->title = Yii::t('user', 'Account settings');
//$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('@web/js/localisation/strength-meter-id.js', [
    'depends' => [
        \kartik\password\PasswordInputAsset::class,
    ]
]);
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

    .heis {
        margin: 0px 1px 0px 1px;
    }
</style>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>
<?= \yii\bootstrap5\Alert::widget([
    'body' => 'Pastikan untuk mengganti kata sandi minimal <b>1 tahun sekali</b> dan <b> berbeda dari kata sandi lama.<b>',
    'closeButton' => false,
    'options' => [
        'class' => "alert-warning",
    ],
]); ?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('_menu', ['suffix' => $suffix]) ?>
    </div>
    <div class="col-md-9">
        <section class="card">
            <header class="card-header">
                <div class="card-actions">
                    <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                </div>
                <h4 class="panel-title"><?= Html::encode($this->title) ?></h4>
            </header>
            <div class="card-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'account-form',
                    'options' => ['class' => 'form-horizontal'],
                    // 'fieldConfig' => [
                    //     'template' => "{label}\n<div class=\"col-lg-9 form-line\">{input}</div>\n<div class=\"col-sm-offset-3 col-lg-9\">{error}\n{hint}</div>",
                    //     'labelOptions' => ['class' => 'col-lg-3 control-label'],
                    // ],
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                ]); ?>
                <?= $form->field($model, 'email') ?>
                <?php if (Yii::$app->user->can('Administrator')) : ?>
                    <?= $form->field($model, 'username') ?>
                <?php endif; ?>

                <?= $form->field($model, 'new_password')->widget(PasswordInput::classname()) ?>
                <hr />

                <?= $form->field($model, 'current_password', [
                    'template' => '<b>{label}</b><div class="col-lg-9 form-line input-group">{input}
            <span style="padding: 6px; background-color: #ebebeb; border-top-right-radius: 3px; border-bottom-right-radius: 3px; border-top: 1px solid #ced4da; border-right: 1px solid #ced4da; border-bottom: 1px solid #ced4da" class="input-group-addon"><input type="checkbox" id="reveal-passwords" name="reveal-passwords" value="false"><label class="heis" for="reveal-passwords"><i id="icons" class="fa fa-eye"></i></label></span></div><div class="col-sm-offset-3 col-lg-9">{error}{hint}</div>'
                ])->passwordInput() ?>
                <?php
                $this->registerJs("jQuery('#reveal-passwords').change(function(){jQuery('#settings-form-current_password').attr('type',this.checked?'text':'password');
    jQuery('#icons').attr('class',this.checked?'fa fa-eye-slash':'fa fa-eye');})");
                ?>
                <div class="form-group text-right">
                    <!-- <div class="col-lg-offset-3 col-lg-9"> -->
                    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-block btn-success']) ?><br>
                    <!-- </div> -->
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </section>
        <!-- <div class="panel panel-default">
            <div class="panel-heading">

            </div>
            <div class="panel-body">

            </div>
        </div> -->

        <?php if ($model->module->enableAccountDelete) : ?>
            <section class="card">
                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                    </div>
                    <h4 class="panel-title"><?= Yii::t('user', 'Delete account') ?></h4>
                </header>
                <div class="card-body">
                    <p>
                        <?= Yii::t('user', 'Once you delete your account, there is no going back') ?>.
                        <?= Yii::t('user', 'It will be deleted forever') ?>.
                        <?= Yii::t('user', 'Please be certain') ?>.
                    </p>
                    <?= Html::a(Yii::t('user', 'Delete account'), ['delete'], [
                        'class' => 'btn btn-danger',
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('user', 'Are you sure? There is no going back'),
                    ]) ?>
                </div>
            </section>
            <!-- <div class="panel panel-danger">
                <div class="panel-heading">

                </div>
                <div class="panel-body">

                </div>
            </div> -->
        <?php endif ?>
    </div>
</div>