<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\widgets\Select2;


/* @var $this yii\web\View */
/* @var $model app\models\Teamlist */
/* @var $form yii\widgets\ActiveForm */
?>

<section class="card">
    <div class="card-body">
        <div class="manage-team-form">

            <?php $form = ActiveForm::begin(); ?>

            <div class='row'>
                <div class='col-md-8 col-md-offset-2 text-center' style="margin: auto">
                    <?=
                    $form->field($model, 'satker_id')->widget(Select2::classname(), [
                        'data' => $dropdown_data,
                        'language' => 'id',
                        'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'satker_id_select'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>
            </div>

            <div class=" text-center">
                    <?= Html::submitButton(Yii::t('app', 'Set'), ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
</section>