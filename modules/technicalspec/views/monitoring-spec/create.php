<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\technicalspec\models\MonitoringSpec */

$this->title = Yii::t('technicalspec', 'Monitoring Specification');
$this->params['breadcrumbs'][] = ['label' => Yii::t('monitoring', 'Monitorings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $monitoringtask->name, 'url' => ['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($monitoringtask->id)]];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-xxl-4">
                <h4>Detail Paket Pekerjaan</h4>
                <?= DetailView::widget([
                    'model' => $monitoringtask,
                    'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                    'attributes' => [
                        'name:ntext',
                        'objectType.name',
                        'rup.namecode',
                        'pbj.namecode',
                        // 'unit_id',
                        'unit_name',
                        'team_name',
                        'location_description:ntext',
                        'kecamatan.name',
                        'latlng',
                        [
                            'attribute' => 'final_price',
                            'format' => ['decimal', 2],
                        ],
                        [
                            'attribute' => 'work_range_start',
                            'format' => ['decimal', 2],
                        ],
                        [
                            'attribute' => 'work_range_end',
                            'format' => ['decimal', 2],
                        ],
                    ],
                ]) ?>
                <h4>Detail Progress</h4>
                <?= DetailView::widget([
                    'model' => $progress,
                    'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                    'attributes' => [
                        'name:ntext',
                        'order_no',
                        // 'monitoring_task_id',
                        'monitoring_task_name:ntext',
                        'progress_date:date',
                        [
                            'attribute' => 'planned_financial_budget',
                            'format' => ['decimal', 2],
                        ],
                        [
                            'attribute' => 'financial_budget',
                            'format' => ['decimal', 2],
                        ],
                        [
                            'attribute' => 'planned_physical_percent',
                            'format' => ['decimal', 2],
                        ],
                        [
                            'attribute' => 'physical_percent',
                            'format' => ['decimal', 2],
                        ],
                    ],
                ]) ?>
            </div>
            <div class="col-xxl-8">
                <div class="monitoring-spec-create">
                    <h4>Data Spesifikasi</h4>
                    <?= $this->render('_form', [
                        'model'         => $model,
                        'modelded'      => $modelded,
                        'dropdown_data' => $dropdown_data,
                        'url_prev'      => $url_prev,
                        'is_create'     => true,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>