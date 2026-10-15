<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use newerton\fancybox3\FancyBox;
/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\models\MonitoringProgressPictures */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => $model->monitoringTask->name, 'url' => ['default/view', 'id' => Yii::$app->hasher->encode($model->monitoringTask->id)]];
$this->params['breadcrumbs'][] = ['label' => $model->monitoringProgress->name, 'url' => ['progress/view', 'id' => Yii::$app->hasher->encode($model->monitoringProgress->id)]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<?php
echo FancyBox::widget([
    'target' => '[data-fancybox="monitoring"]',
]);
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="monitoring-progress-pictures-view">

            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), ['/monitoring/progress/view', 'id' => Yii::$app->hasher->encode($model->monitoring_progress_id)], ['class' => 'btn btn-warning']) ?>
                <?php // echo Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-primary']) 
                ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => Yii::$app->hasher->encode($model->id)], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
            <br>
            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                    'attributes' => [
                        'name:ntext',
                        // 'monitoring_task_id',
                        [
                            'attribute' => 'monitoring_task_id',
                            'format' => 'html',
                            'value' => Html::a($model->monitoring_task_name, ['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]),
                        ],
                        [
                            'attribute' => 'monitoring_progress_id',
                            'format' => 'html',
                            'value' => Html::a($model->monitoring_progress_name, ['/monitoring/progress/view', 'id' => Yii::$app->hasher->encode($model->monitoring_progress_id)]),
                        ],
                        [
                            'attribute' => 'monitoring_task_locations_id',
                            'format' => 'html',
                            'value' => Html::a($model->monitoring_task_locations_name, ['/monitoring/locations/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_locations_id)]),
                        ],
                        // 'file_name',
                        // 'file_url',
                        [
                            'attribute' => 'file_url',
                            'format' => 'html',
                            'value' => function ($model) {
                                return Html::a(Yii::t('app', 'View'), ['/' . $model->file_url],);
                            },
                        ],
                        // 'file_thumbnail:ntext',
                        'description:ntext',
                        [
                            'attribute' => 'uploadimage',
                            'format' => 'raw',
                            'value' => function ($model) {
                                if (pathinfo($model->file_name, PATHINFO_EXTENSION) == 'mp4') {
                                    return '<video width="320" height="240" controls>
                                    <source src="' . $model->picture_url . '" type="video/mp4">
                                    Your browser does not support the video tag.
                                    ' . Html::a(
                                        'Download',
                                        $model->picture_url,
                                        [
                                            'data' => [
                                                'pjax' => 0,
                                                'fancybox' => "monitoring",
                                            ]
                                        ]
                                    ) . '
                                    </video>';
                                }
                                return Html::a(
                                    Html::img(($model->picture_url), ['width' => '200px']),
                                    $model->picture_url,
                                    [
                                        // 'target' => '_blank',
                                        'data' => [
                                            'fancybox' => "monitoring",
                                        ],
                                    ]
                                );
                            }
                        ],
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
            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), ['/monitoring/progress/view', 'id' => Yii::$app->hasher->encode($model->monitoring_progress_id)], ['class' => 'btn btn-warning']) ?>
                <?php // echo Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-primary']) 
                ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => Yii::$app->hasher->encode($model->id)], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</section>