<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\widgets\DatePicker;


/* @var $this yii\web\View */
/* @var $model app\modules\references\models\Year_model */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('app', 'Year');
?>

<section class="card">
    <div class="card-body">
        <div class="manage-year-form">

            <?php $form = ActiveForm::begin(); ?>
            <br>
            <div class='row'>
                <div class='col-md-4 col-md-offset-4 text-center' style="margin: auto">
                    <?= $form->field($model, 'year')->dropDownList($dropdown_data, ['prompt' => Yii::t('app', 'Choose')]) ?>
                </div>
            </div>

            <div class="text-center">
                <?= Html::submitButton(Yii::t('app', 'Set'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</section>