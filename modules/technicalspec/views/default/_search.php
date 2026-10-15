<?php

use kartik\depdrop\DepDrop;
use kartik\widgets\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\technicalspec\models\DedDataSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ded-data-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>


    <div class="row">
        <div class='col-md-6'>
            <?=
            $form->field($model, 'ref_component_category_id')->widget(Select2::classname(), [
                'data' => $dropdown_data['ref_component_category_id'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'ref_component_category_id_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'ref_component_id')->widget(DepDrop::classname(), [
                'options' => [
                    'placeholder' => Yii::t('app', 'Choose'),
                    'id' => 'ref_component_id_select',
                ],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => false]],
                'pluginOptions' => [
                    'depends' => ['ref_component_category_id_select'],
                    'initialize' => true,
                    'url' => Url::to(['component/depdrop-by-category']),
                    'loadingText' => Yii::t('app', 'Loading...'),
                ]
            ]);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'monitoring_task_name') ?>
        </div>
    </div>

    <?php // echo $form->field($model, 'monitoring_task_id') 
    ?>

    <?php // echo $form->field($model, 'monitoring_task_name') 
    ?>

    <?php // echo $form->field($model, 'ref_component_category_id') 
    ?>

    <?php // echo $form->field($model, 'ref_component_category_name') 
    ?>

    <?php // echo $form->field($model, 'ref_component_id') 
    ?>

    <?php // echo $form->field($model, 'ref_component_name') 
    ?>

    <?php // echo $form->field($model, 'cco') 
    ?>

    <?php // echo $form->field($model, 'specification') 
    ?>

    <?php // echo $form->field($model, 'quantity') 
    ?>

    <?php // echo $form->field($model, 'notes') 
    ?>

    <?php // echo $form->field($model, 'created_at') 
    ?>

    <?php // echo $form->field($model, 'created_by') 
    ?>

    <?php // echo $form->field($model, 'updated_at') 
    ?>

    <?php // echo $form->field($model, 'updated_by') 
    ?>

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