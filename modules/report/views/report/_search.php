<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\DatePicker;
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="report-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>
    <div class="row">
        <div class='col-md-6'>
            <?= $form->field($model, 'accident')->dropDownList([1 => Yii::t('app', 'Yes'), 0 => Yii::t('app', 'No')], ['prompt' => Yii::t('app', 'Choose'), 'onchange' => 'getValue(this.value)']);
            ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'date')->widget(DatePicker::classname(), [
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'date_accident'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'todayHighlight' => true,
                    'format' => 'dd-mm-yyyy',
                    //'maxViewMode' => 'month',
                    //'minViewMode' => 'days',

                ]
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'accident_category')->dropDownList(['Ringan' => Yii::t('app', 'Minor'), 'Sedang' => Yii::t('app', 'Moderate'), 'Berat' => Yii::t('app', 'Serious'), 'Meninggal Dunia' => Yii::t('app', 'Die')], ['prompt' => Yii::t('app', 'Choose')]); ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>&nbsp;
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-warning form-reset']) ?>&nbsp;
        <?php if ($queryString) { ?>
            <?= Html::a(Yii::t('app', '<i class="fa fa-print"></i> Export Excel'), ['/report/report/export?' . Yii::$app->request->queryString], ['data-pjax' => 0, 'class' => 'btn btn-success']) ?>
        <?php } else { ?>
            <?= Html::a(Yii::t('app', '<i class="fa fa-print"></i> Export Excel'), ['/report/report/export'], ['data-pjax' => 0, 'class' => 'btn btn-success']) ?>
        <?php } ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerJs(
    <<< EOT_JS_CODE

$(document).ready(function(){
    $('.form-reset').on('click', function () {
        $.ajax({
            url:"reset_session",
            type: "POST",
            //data:"",
            success:function(data){
            }
        });
    });
});

EOT_JS_CODE

);
?>