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
 * @var infinindotech\user\models\User $model
 * @var infinindotech\user\Module $module
 */

$this->title = Yii::t('user', 'Sign up');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <!-- <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3"> -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><center><?= Html::encode($this->title) ?></center></h3>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'layout' => 'floating',
                    'id' => 'registration-form',
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                ]); ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'username') ?>

                <?php if ($module->enableGeneratingPassword == false): ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                <?php endif ?>

                <?= Html::submitButton(Yii::t('user', 'Sign up'), ['class' => 'btn btn-success btn-block mt-3 mb-3', 'style' => 'background: #1b7351; border-color: #1b7351']) ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <p class="text-center">
            <?= Html::a(Yii::t('user', 'Already registered? Sign in!'), ['/user/security/login']) ?>
        </p>
    <!-- </div> -->
</div>
