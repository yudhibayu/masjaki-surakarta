<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sync_rup_penyedia\models\SyncLog */

$this->title = Yii::t('app', 'Create Sync Log');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sync Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sync-log-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model'         => $model,
        'dropdown_data' => $dropdown_data,
        'url_prev'      => $url_prev,
    ]) ?>

</div>
