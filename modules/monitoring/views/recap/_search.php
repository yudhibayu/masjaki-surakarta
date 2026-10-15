<?php

use kartik\widgets\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\models\MonitoringTaskSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="monitoring-task-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="row">
        <div class="col-md-6">
            <?=
            $form->field($model, 'team_id')->widget(Select2::class, [
                'data' => $dropdown_data['team_id'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'team_id_select'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Kelompok');
            ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'unit_id')->widget(Select2::class, [
                'data' => $dropdown_data['unit_id'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'unit_id_select'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('OPD');
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?=
            $form->field($model, 'object_type_id')->widget(Select2::class, [
                'data' => $dropdown_data['object_type_id'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'object_type_id_select'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Jenis Objek');
            ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'sd_bulan')->widget(Select2::class, [
                'data' => $dropdown_data['sd_bulan'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'sd_bulan_select'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('S/D Bulan');
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?=
            $form->field($model, 'implementation_status')->widget(Select2::class, [
                'data' => $dropdown_data['implementation_status'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'implementation_status_select'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(Yii::t('app', 'Physical Progress Condition'));
            ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary form-reset']) ?>
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