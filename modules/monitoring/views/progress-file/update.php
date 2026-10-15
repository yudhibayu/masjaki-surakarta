<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\modules\pictures\models\MonitoringTaskPictures */

$this->title = Yii::t('monitoring', 'Update Monitoring Progress Pictures: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => $model->monitoringTask->name, 'url' => ['default/view', 'id' => Yii::$app->hasher->encode($model->monitoringTask->id)]];
$this->params['breadcrumbs'][] = ['label' => $model->monitoringProgress->name, 'url' => ['progress/view', 'id' => Yii::$app->hasher->encode($model->monitoringProgress->id)]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => Yii::$app->hasher->encode($model->id)]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="monitoring-progress-pictures-update">

    <h3><?= Html::encode($this->title) ?></h3>
    <br>
    
    <?= $this->render('_form', [
        'model'         => $model,
        'dropdown_data' => $dropdown_data,
        'url_prev'      => $url_prev,
    ]) ?>

</div>
