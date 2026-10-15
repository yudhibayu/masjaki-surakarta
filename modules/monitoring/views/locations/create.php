<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\modules\locations\models\MonitoringTaskLocations */

$this->title = Yii::t('monitoring', 'Monitoring Task Location');
$this->params['breadcrumbs'][] = ['label' => $model->monitoring_task_name, 'url' => ['default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="monitoring-task-locations-create">

            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>
