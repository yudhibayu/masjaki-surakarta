<?php

use newerton\fancybox3\FancyBox;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\models\MonitoringPlannerNoteFile */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => $model->constructionAccident->id, 'url' => ['construction-accident/view', 'id' => Yii::$app->hasher->encode($model->constructionAccident->id)]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<?php
echo FancyBox::widget([
    'target' => '[data-fancybox="accident"]',
]);
?>
<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="construction-accident-file-view">

            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), ['construction-accident/view', 'id' => Yii::$app->hasher->encode($model->construction_accident_id)], ['class' => 'btn btn-warning']) ?>
                <!-- <= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-primary']) ?> -->
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
                        // 'monitoring_planner_note_id',
                        // 'monitoring_planner_note_name',
                        'description:ntext',
                        [
                            'attribute' => 'file_name',
                            'format' => 'raw',
                            'value' => function ($model) {
                                if (
                                    // (strtolower($model->file_extension) == 'jpg') ||
                                    // (strtolower($model->file_extension) == 'jpeg') ||
                                    // (strtolower($model->file_extension) == 'png') ||
                                    // (strtolower($model->file_extension) == 'gif') ||
                                    // (strtolower($model->file_extension) == 'webp') ||
                                    (strtolower($model->file_extension) == 'pdf')
                                ) {
                                    // return Html::img(Url::to(['fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]), ['width' => '200px', 'alt' => $model->name]);
                                    return $model->file_name. '&nbsp&nbsp&nbsp&nbsp&nbsp' . Html::a(
                                        Yii::t('app', 'Download') . ' <span class="fas fa-download"></span>',
                                        Url::to(['fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]),
                                        [
                                            'class' => 'btn btn-xs btn-success',
                                            'data' => [
                                                'pjax' => 0,
                                                // 'fancybox' => "accident",
                                            ],
                                            'target' => '_blank'
                                        ]
                                    );
                                 
                                }  
                                // else {
                                //     return Html::a(Yii::t('app', 'Download') . ' <span class="fas fa-download"></span>', Url::to(['fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]), ['class' => 'btn btn-primary', 'target' => '_blank']);
                                // }
                            },
                        ],
                        // 'file_name',
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
            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), ['construction-accident/view', 'id' => Yii::$app->hasher->encode($model->construction_accident_id)], ['class' => 'btn btn-warning']) ?>
                <!-- <= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-primary']) ?> -->
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