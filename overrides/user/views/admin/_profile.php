<?php

/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use infinindotech\user\helpers\Timezone;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\User $user
 * @var dektrium\user\models\Profile $profile
 */
?>

<?php $this->beginContent('@app/overrides/user/views/admin/update.php', ['user' => $user, 'profile' => $profile]) ?>

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'wrapper' => 'col-sm-10 form-line',
        ],
    ],
]); ?>

<?= $form->field($profile, 'name') ?>
<?php //echo $form->field($profile, 'public_email') ?>
<?php //echo $form->field($profile, 'website') ?>
<?php //echo $form->field($profile, 'location') ?>

            <?= 
                $form->field($profile, 'timezone')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Timezone::getAll(), 'timezone', 'name'),
                    'language' => 'id',
                    'options' => ['placeholder' =>Yii::t('app', 'Choose'), 'id'=>'timezone_select'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
<?= $form->field($profile, 'gravatar_email') ?>
<?= $form->field($profile, 'bio')->textarea() ?>

<br>
<div class="form-group text-right">
    <!-- <div class="col-lg-offset-3 col-lg-9"> -->
        <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-block btn-success']) ?>
    <!-- </div> -->
</div>

<?php ActiveForm::end(); ?>

<?php $this->endContent() ?>
