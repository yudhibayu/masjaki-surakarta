<?php

use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Teamlist */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="teamlist-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-6'>
            <?=
                $form->field($model, 'team_id')->widget(Select2::classname(), [
                    'data' => $teams,
                    'language' => 'id',
                    'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'team_id_select'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]);
            ?>
        </div>
        <div class='col-md-6'>
            <?=
                $form->field($model, 'user_id')->widget(Select2::classname(), [
                    'data' => $users,
                    'language' => 'id',
                    'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'user_id_select'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]);
            ?>
        </div>
    </div>
        
    <div class="text-right">
        <?php if(isset($model->team_id)) : ?>
            <?= Html::a(Yii::t('app', 'Cancel'), ['view','id'=>Yii::$app->hasher->encode($model->team_id)], ['class' => 'btn btn-warning']) ?>
        <?php else : ?>
            <?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-warning']) ?>
        <?php endif; ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
