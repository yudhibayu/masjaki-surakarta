<?php

use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\models\MonitoringTask */

$this->title = Yii::t('app', 'Add Tagging');
$this->params['breadcrumbs'][] = ['label' => Yii::t('monitoring', 'Monitorings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="add-planner-create">
            <?php $form = ActiveForm::begin([
                'id' => 'form-data-add-planner',
            ]); ?>
            <div class="row">
                <div class="col-md-6">
                    <?=
                    $form->field($model_tagging, 'tagging_id')->widget(Select2::classname(), [
                        'data' => $dropdown_data['tagging'],
                        'language' => 'id',
                        'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'tagging_id_select'],
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                    ]);
                    ?>
                </div>
            </div>
            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Cancel'), ['view', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?>
                <?= Html::submitButton(Yii::t('app', 'Save'), ['id' => 'submitForm', 'class' => 'btn btn-success']) ?>
            </div>
            <?php
            // echo $this->render('_addform', [
            //     'model'         => $model,
            //     'model_planner' => $model_planner,
            //     'dropdown_data' => $dropdown_data,
            //     'url_prev'      => $url_prev,
            // ])
            ?>
            <?php $form = ActiveForm::end(); ?>
        </div>
    </div>
</section>