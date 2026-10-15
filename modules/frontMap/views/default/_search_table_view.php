<?php

use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\models\MonitoringTaskSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="monitoring-task-search">

    <?php $form = ActiveForm::begin([
        'action' => ['table-index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="row">
        <div class="col-md-6">
            <?=
            $form->field($model, 'fiscal_year')->widget(Select2::class, [
                'data' => $dropdown_data['fiscal_year'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'fiscal_year_select'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
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
            ]);
            ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'typeobject_id')->widget(Select2::class, [
                'data' => $dropdown_data['object_type'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'typeobject_id_select'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Tipe Objek');
            ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'kecamatan_id')->widget(Select2::class, [
                'data' => $dropdown_data['kecamatan_id'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'kecamatan_id_select'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Kecamatan');
            ?>
        </div>
    </div>

    <?php // echo $form->field($model, 'unit_name') 
    ?>

    <?php // echo $form->field($model, 'name') 
    ?>

    <?php // echo $form->field($model, 'latest_monitored_physical_percent') 
    ?>

    <?php // echo $form->field($model, 'end_date') 
    ?>

    <?php // echo $form->field($model, 'fiscal_year') 
    ?>

    <?php // echo $form->field($model, 'latest_plan_financial_budget') 
    ?>

    <?php // echo $form->field($model, 'latest_plan_physical_percent') 
    ?>

    <?php // echo $form->field($model, 'latest_monitored_financial_budget') 
    ?>

    <?php // echo $form->field($model, 'latest_monitored_physical_percent') 
    ?>

    <?php // echo $form->field($model, 'created_at') 
    ?>

    <?php // echo $form->field($model, 'updated_at') 
    ?>

    <?php // echo $form->field($model, 'created_by') 
    ?>

    <?php // echo $form->field($model, 'updated_by') 
    ?>

    <br>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Reset'), ['table-index'], ['class' => 'btn btn-outline-secondary form-reset']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <br>

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