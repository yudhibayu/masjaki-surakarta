<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\models\MonitoringWeather */

$this->title = Yii::t('monitoring', 'Create Weather') . ' : ' . $model->monitoringProgress->name;
$this->params['breadcrumbs'][] = ['label' => $model->monitoringTask->name ?? '', 'url' => ['default/view', 'id' => Yii::$app->hasher->encode($model->monitoringTask->id)]];
$this->params['breadcrumbs'][] = ['label' => $model->monitoringProgress->name ?? '', 'url' => ['progress/view', 'id' => Yii::$app->hasher->encode($model->monitoringProgress->id)]];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="monitoring-weather-create">

            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>