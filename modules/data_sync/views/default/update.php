<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sync_rup_penyedia\models\SyncLog */

$this->title = Yii::t('app', 'Update Sync Log: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sync Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => Yii::$app->hasher->encode($model->id)]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="sync-log-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model'         => $model,
        'dropdown_data' => $dropdown_data,
        'url_prev'      => $url_prev,
    ]) ?>

</div>
