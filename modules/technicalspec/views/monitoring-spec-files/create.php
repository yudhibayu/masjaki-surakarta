<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\technicalspec\models\MonitoringSpecFiles */

$this->title = Yii::t('technicalspec', 'Monitoring Specification Files');
$this->params['breadcrumbs'][] = ['label' => Yii::t('technicalspec', 'Monitoring Specification Files'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="monitoring-spec-files-create">
            <div class="row">
                <div class="col-md-4">
                    <?= DetailView::widget([
                        'model' => $spec,
                        'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                        'attributes' => [
                            'name',
                            // 'ded_data_id',
                            'ded_data_name',
                            // 'monitoring_progress_id',
                            'monitoring_progress_name',
                            // 'monitoring_task_id',
                            'monitoring_task_name',
                            'description:ntext',
                            'created_at:datetime',
                            'updated_at:datetime',
                            [
                                'attribute' => 'created_by',
                                'value' => $model->maker
                            ],
                            [
                                'attribute' => 'updated_by',
                                'value' => $model->updater
                            ],
                        ],
                    ]) ?>
                </div>
                <div class="col-md-8">
                    <?= $this->render('_form', [
                        'model'         => $model,
                        'dropdown_data' => $dropdown_data,
                        'url_prev'      => $url_prev,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>