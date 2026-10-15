<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\models\MonitoringTask */

$this->title = Yii::t('app', 'Update File: {name}', [
    'name' => $model->file_name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Upload File'), 'url' => ['/monitoring/detail/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]];
$this->params['breadcrumbs'][] = ['label' => $model->file_name, 'url' => ['/monitoring/detail/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="monitoring-task-update">
            <br>
            
            <?= $this->render('_form_file', [
                'model'         => $model,
                'id_monitoring_task' =>  $id_monitoring_task
            ]) ?>

        </div>
    </div>
</section>
