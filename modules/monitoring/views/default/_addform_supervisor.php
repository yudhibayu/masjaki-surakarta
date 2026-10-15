<?php

use dosamigos\leaflet\LeafLetAsset;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use kartik\number\NumberControl;
use kartik\widgets\DepDrop;
use yii\widgets\Pjax;
use yii\web\View;
use bajhoe\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\models\MonitoringTask */
/* @var $form yii\widgets\ActiveForm */

$js1 = "
var unit = '" . Yii::t('app', 'Number') . "',
del = '" . Yii::t('app', 'Are you sure you want to delete this item?') . "',
lim = '" . Yii::t('app', 'Limit is reached') . "';
";

$js2 = '

$(".df_wrapper_unit .panel-title-unit").each(function(index) {
    $(this).html(unit + " - " + (index + 1))
});

$(".df_wrapper_unit").on("afterInsert", function(e, item) {
    $(".df_wrapper_unit .panel-title-unit").each(function(index) {
        $(this).html(unit + " - " + (index + 1))
    });
});

$(".df_wrapper_unit").on("afterDelete", function(e) {
    $(".df_wrapper_unit .panel-title-unit").each(function(index) {
        $(this).html(unit + " - " + (index + 1))
    });
    $("#submitForm").removeAttr("disabled");
    $("#add-item-child").removeAttr("disabled");
});
$(".df_wrapper_unit").on("beforeDelete", function(e, item) {
    if (! confirm(del)) {
        return false;
    }
    return true;
});
$(".df_wrapper_unit").on("limitReached", function(e, item) {
    alert(lim);
});

';

$this->registerJs($js1, View::POS_READY);
$this->registerJs($js2, View::POS_READY);
?>

<div class="monitoring-task-form">

    <?php $form = ActiveForm::begin([
        'id' => 'dyn-acc-form-1',
    ]); ?>

    <div class='row'>
        <div class='col-md-12'>
            <?php // echo $form->field($model, 'unit_id')->dropDownList($dropdown_data['unit_id'], ['prompt' => Yii::t('app', 'Choose')])
            ?>
            <?=
            $form->field($model, 'id')->hiddenInput()->label(false);
            ?>
        </div>
    </div>

    <div class="row">
        <br>
        <?php
        DynamicFormWidget::begin([
            'widgetContainer' => 'df_wrapper_unit', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
            'widgetBody' => '.form-container-unit', // required: css class selector
            'widgetItem' => '.isi-unit', // required: css class
            'limit' => 5, // the maximum times, an element can be cloned (default 999)
            'min' => 1, // 0 or 1 (default 1)
            'insertButton' => '.add-item-unit', // css class
            'deleteButton' => '.remove-item-unit', // css class
            'model' => $model_supervisor[0],
            'formId' => 'dyn-acc-form-1',
            'formFields' => [
                'id',
                'grade',
                'criteria',
                'description',
            ],
        ]);
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="bx bx-edit"></i> <b><?= Yii::t('app', 'Supervisor Table') ?></b>
                <button type="button" id="add-item-child" class="pull-right add-item-unit btn btn-success"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Add') ?></button>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <!-- widgetContainer -->
                <div class="form-container-unit" id="product-container">
                    <?php foreach ($model_supervisor as $index_unit => $model_child_data_unit) : ?>
                        <div class="isi-unit" style="background-color: white; margin-bottom: 10px; padding: 10px; border: 1px solid #ebebeb; border-radius: 5px;">
                            <div class="row">
                                <!-- <div class="col-md-1"> -->
                                <!-- <span class="panel-title-unit"></span> -->
                                <?php
                                if (!$model_child_data_unit->isNewRecord) {
                                    echo $form->field($model_child_data_unit, "[{$index_unit}]id")->hiddenInput()->label(false);
                                }
                                ?>
                                <!-- </div> -->
                                <div class="col-md-10">
                                    <?= $form->field($model_child_data_unit, "[{$index_unit}]supervisor_id")->widget(Select2::classname(), [
                                        'data' => $dropdown_data['supervisor_id'],
                                        'language' => 'id',
                                        'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'supervisor_id_select'],
                                        'pluginOptions' => [
                                            'allowClear' => false,
                                            
                                        ],
                                    ]);
                                    ?>
                                    <div class="help-block"><small id="textsame" name="textsame"></small></div>
                                </div>
                                <div class="col-md-2">
                                    <label><?= Yii::t('app', 'Action') ?></label>
                                    <div>
                                        <button type="button" class="remove-item-unit btn btn-danger"><i class="fa fa-minus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>

            </div>
        </div>
        <?php DynamicFormWidget::end(); ?>
    </div>



    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-warning']) ?>
        <?= Html::submitButton(Yii::t('app', 'Save'), ['id' => 'submitForm', 'class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>