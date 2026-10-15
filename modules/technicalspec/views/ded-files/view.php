<?php

use newerton\fancybox3\FancyBox;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\technicalspec\models\DedFiles */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => $model->dedData->monitoringTask->name, 'url' => ['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($model->dedData->monitoringTask->id)]];
$this->params['breadcrumbs'][] = ['label' => $model->dedData->name, 'url' => ['default/view', 'id' => Yii::$app->hasher->encode($model->dedData->id)]];
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
        <div class="ded-files-view">

            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), ['default/view', 'id' => Yii::$app->hasher->encode($model->ded_data_id)], ['class' => 'btn btn-warning']) ?>
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
                        'name',
                        // 'ded_data_id',
                        'ded_data_name',
                        'description:ntext',
                        // 'file_name',
                        [
                            'attribute' => 'file_upload',
                            'format' => 'raw',
                            'value' => function ($model) {
                                if (
                                    (strtolower($model->file_extension) == 'jpg') ||
                                    (strtolower($model->file_extension) == 'jpeg') ||
                                    (strtolower($model->file_extension) == 'png') ||
                                    (strtolower($model->file_extension) == 'gif') ||
                                    (strtolower($model->file_extension) == 'webp')
                                ) {
                                    // return Html::img(Url::to(['fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]), ['width' => '200px', 'alt' => $model->name]);
                                    return Html::a(
                                        Html::img(Url::to(['fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]), ['width' => '200px', 'alt' => $model->name]),
                                        Url::to(['fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]),
                                        [
                                            'data' => [
                                                'pjax' => 0,
                                                'fancybox' => "monitoring",
                                            ]
                                        ]
                                    );
                                } else if (
                                    (strtolower($model->file_extension) == 'mp4') ||
                                    (strtolower($model->file_extension) == 'mkv')
                                ) {
                                    return '<video width="200px" controls>
                                    <source src="' . Url::to(['fetch-file', 'id' => Yii::$app->hasher->encode($model->id)], true) . '" type="video/mp4">
                                    Your browser does not support the video tag.
                                    ' . Html::a(
                                        'Download',
                                        Url::to(['fetch-file', 'id' => Yii::$app->hasher->encode($model->id)], true),
                                        [
                                            'data' => [
                                                'pjax' => 0,
                                                'fancybox' => "monitoring",
                                            ]
                                        ]
                                    ) . '
                                    </video>';
                                } else {
                                    return Html::a(Yii::t('app', 'Download') . ' <span class="fas fa-download"></span>', Url::to(['fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]), ['class' => 'btn btn-primary']);
                                }
                            },
                        ],
                        // 'file_path:ntext',
                        // 'file_extension',
                        // 'file_size',
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
        </div>
    </div>
</section>