<?php

use kartik\editable\Editable;
use kartik\grid\GridView;
use newerton\fancybox3\FancyBox;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\models\MonitoringPlannerNote */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => $model->monitoring_task_name, 'url' => ['default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]];
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
        <div class="monitoring-planner-note-view">
            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), ['default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)], ['class' => 'btn btn-warning']) ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-primary']) ?>
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
                        'order_no',
                        // 'monitoring_task_id',
                        'monitoring_task_name:ntext',
                        'note_date:date',
                        // 'date_day',
                        // 'date_month',
                        // 'date_year',
                        'description:ntext',
                        // 'planned_financial_budget',
                        // 'planned_physical_percent',
                        // 'financial_budget',
                        // 'physical_percent',
                        'problem:ntext',
                        'solution:ntext',
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
            <br>
            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Add File'), ['planner-note-file/create', 'pnid' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-primary']) ?>
            </div>
            <br>
            <?php Pjax::begin(['id' => 'monitoring-planner-note-file-yea-pjax']); ?>
            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <?= GridView::widget(
                    [
                        'dataProvider' => $plannerNoteFileDataProvider,
                        'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                        'filterModel' => $plannerNoteFileModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            'name',
                            [
                                'class' => 'kartik\grid\EditableColumn',
                                'attribute' => 'description',
                                'format' => 'ntext',
                                'refreshGrid' => false,
                                'editableOptions' => function ($model, $key, $index) {
                                    return [
                                        'header' => Yii::t('app', 'Description'),
                                        // 'size' => 'md',
                                        'inputType' => Editable::INPUT_TEXTAREA,
                                        'asPopover' => false,
                                    ];
                                }
                            ],
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
                                        // return Html::img(Url::to(['ded-files/fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]), ['width' => '200px', 'alt' => $model->name]);
                                        return Html::a(
                                            Html::img(Url::to(['planner-note-file/fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]), ['width' => '200px', 'alt' => $model->name]),
                                            Url::to(['planner-note-file/fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]),
                                            [
                                                'data' => [
                                                    'pjax' => 0,
                                                    'fancybox' => "monitoring",
                                                ]
                                            ]
                                        );
                                    } else {
                                        return Html::a(
                                            '<span class="fas fa-download"></span>',
                                            Url::to(['planner-note-file/fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]),
                                            [
                                                'class' => 'btn btn-xs btn-primary',
                                                'style' => 'margin: 2px;width: 35px;',
                                            ]
                                        );
                                    }
                                },
                            ],
                            //'file_path:ntext',
                            //'file_extension',
                            //'file_size',
                            //'created_at:datetime',
                            //'updated_at:datetime',
                            /*[
                                'attribute' => 'created_by', 
                                'value' => 'maker',
                                'filter' => false
                            ],*/
                            /*[
                                'attribute' => 'updated_by', 
                                'value' => 'updater',
                                'filter' => false
                            ],*/

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => Yii::t('app', 'Action'),
                                'headerOptions' => ['width' => '100'],
                                'template' => '{view} {delete}',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {

                                        return Html::a(
                                            '<span class="fas fa-eye"></span>',
                                            [
                                                Url::toRoute(['view', 'id' => Yii::$app->hasher->encode($model->id)])
                                            ],
                                            [
                                                'class' => 'btn btn-xs btn-primary',
                                                'style' => 'margin: 2px;width: 35px;',
                                            ]
                                        );
                                    },
                                    'delete' => function ($action, $model, $key) {

                                        return Html::a(
                                            '<span class="fas fa-trash"></span>',
                                            [
                                                Url::toRoute(['delete', 'id' => Yii::$app->hasher->encode($model->id)])
                                            ],
                                            [
                                                //'class' => 'btn btn-danger',
                                                'data' => [
                                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                    'method' => 'post',
                                                    'params' => [], // <- extra level
                                                ],
                                                'class' => 'btn btn-xs btn-danger',
                                                'style' => 'margin: 2px;width: 35px;',
                                            ]
                                        );
                                    },
                                ],
                            ],
                        ],
                    ],
                ); ?>
            </div>
            <?php Pjax::end(); ?>
            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), ['default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)], ['class' => 'btn btn-warning']) ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-primary']) ?>
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