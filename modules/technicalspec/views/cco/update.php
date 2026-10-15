<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\technicalspec\models\McoCco */

$this->title = Yii::t('technicalspec', 'Update MCO CCO: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => $model->monitoringTask->name, 'url' => ['default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => Yii::$app->hasher->encode($model->id)]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="mco-cco-update">
            <br>

            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>