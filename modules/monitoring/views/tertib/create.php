<?php

use yii\helpers\Html;

$this->title = 'Tambah Kegiatan Pengawasan';
$this->params['breadcrumbs'][] = ['label' => 'Rekap Pengawasan Tertib', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tertib-create">
    <h1 class="h3 mb-3"><?= Html::encode($this->title) ?></h1>
    <div class="card"><div class="card-body">
        <?= $this->render('_form', ['model' => $model, 'taskOptions' => $taskOptions]) ?>
    </div></div>
</div>
