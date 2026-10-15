<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\technicalspec\models\DedData */

$this->title = Yii::t('technicalspec', 'Update DED: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => $model->monitoringTask->name, 'url' => ['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => Yii::$app->hasher->encode($model->id)]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="ded-data-update">
            <br>

            <?= $this->render('_form', [
                'is_create'     => false,
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>