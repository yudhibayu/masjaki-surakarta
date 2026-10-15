<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\technicalspec\models\DedFiles */

$this->title = Yii::t('technicalspec', 'Update DED Files: {name}', [
    'name' => $model->name,
]);

$this->params['breadcrumbs'][] = ['label' => $model->dedData->monitoringTask->name, 'url' => ['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($model->dedData->monitoringTask->id)]];
$this->params['breadcrumbs'][] = ['label' => $model->dedData->name, 'url' => ['default/view', 'id' => Yii::$app->hasher->encode($model->dedData->id)]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => Yii::$app->hasher->encode($model->id)]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="ded-files-update">
            <br>

            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>