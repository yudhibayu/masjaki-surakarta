<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use infinindotech\user\helpers\Timezone;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\bootstrap5\ActiveForm;
use yii\widgets\Breadcrumbs;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\Profile $model
 */

$this->title = Yii::t('user', 'Profile settings');
// $this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['']];

?>
<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

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
                <h4><?= Html::encode($this->title) ?></h4>
            </header>
            <div class="card-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'profile-form',
                    'options' => ['class' => 'form-horizontal'],
                    // 'fieldConfig' => [
                    //     'template' => "{label}\n<div class=\"col-lg-9 form-line\">{input}</div>\n<div class=\"col-sm-offset-3 col-lg-9\">{error}\n{hint}</div>",
                    //     'labelOptions' => ['class' => 'col-lg-3 control-label'],
                    // ],
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                    'validateOnBlur' => false,
                ]); ?>

                <?= $form->field($model, 'name') ?>
                <?=
                $form->field($model, 'timezone')->widget(Select2::class, [
                    'data' => ArrayHelper::map(Timezone::getAll(), 'timezone', 'name'),
                    'language' => 'id',
                    'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'timezone_select'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
                <?= $form
                    ->field($model, 'gravatar_email')
                    ->hint(Html::a(Yii::t('user', 'Change your avatar at Gravatar.com'), 'http://gravatar.com')) ?>

                <?= $form->field($model, 'bio')->textarea() ?>

                <div class="form-group">
                    <div class="col-lg-offset-3 col-lg-9">
                        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-block btn-success', 'style' => 'float: right']) ?>
                        <br>
                    </div>
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
    </div>
</div>