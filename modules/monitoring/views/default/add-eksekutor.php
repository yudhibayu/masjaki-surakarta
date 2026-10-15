<?php

use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\models\MonitoringTask */

$this->title = Yii::t('app', 'Add Eksekutor');
$this->params['breadcrumbs'][] = ['label' => Yii::t('monitoring', 'Monitorings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
if(!isset($is_edit)){
    $is_edit = false;
}
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="add-eksekutor-create">
            <?php $form = ActiveForm::begin([
                'id' => 'form-data-add-eksekutor',
            ]); ?>
            <div class="row">
                <div class="col-md-6">
                    <?=
                    $form->field($model_eksekutor, 'eksekutor_id')->widget(Select2::class, [
                        'data' => $dropdown_data['eksekutor_id'],
                        'language' => 'id',
                        'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'eksekutor_id_select'],
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                        'disabled' => $is_edit,
                    ]);
                    ?>
                </div>
                <div class="col-md-6">
                    <?=
                    $form->field($model_eksekutor, 'is_active')->widget(Select2::class, [
                        'data' => [
                            0 => Yii::t('app', 'No'),
                            1 => Yii::t('app', 'Yes'),
                        ],
                        'language' => 'id',
                        'options' => ['id' => 'is_active_select'],
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-md-6">
                    <?=
                    $form->field($model_eksekutor, 'description')->textarea() ?>
                </div>
            </div>
            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Cancel'), ['view', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?>
                <?= Html::submitButton(Yii::t('app', 'Save'), ['id' => 'submitForm', 'class' => 'btn btn-success']) ?>
            </div>
            <?php
            // echo $this->render('_addform_eksekutor', [
            //     'model'         => $model,
            //     'model_eksekutor' => $model_eksekutor,
            //     'dropdown_data' => $dropdown_data,
            //     'url_prev'      => $url_prev,
            // ])
            ?>
            <?php $form = ActiveForm::end(); ?>
        </div>
    </div>
</section>