<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use newerton\fancybox3\FancyBox;

/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\models\MonitoringTask */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('monitoring', 'Monitorings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<?php
echo FancyBox::widget([
    'target' => '[data-fancybox="monitoring"]',
]);
?>
<?php Pjax::begin(); ?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="monitoring-task-view">
            <div class="row">
                <div class="col-md-4 text-start">
                    <?= Html::a(Yii::t('app', 'Back to List'), [$url_prev], ['class' => 'btn btn-sm btn-warning']) ?>
                </div>
                <div class="col-md-4 text-start text-md-center">
                </div>
                <div class="col-md-4 text-start text-md-end">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-8">
                    <h4>Detail Paket</h4>
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                        'attributes' => [
                            'name:ntext',
                            'rup.namecode',
                            'pbj.namecode',
                            // 'unit_id',
                            'unit_name',
                            'team_name',
                            'location_description:ntext',
                            'kecamatan.name',
                            'latlng',
                            [
                                'attribute' => 'budget',
                                'format' => ['decimal', 2],
                            ],
                            [
                                'attribute' => 'hps',
                                'format' => ['decimal', 2],
                            ],
                            [
                                'attribute' => 'bid_price',
                                'format' => ['decimal', 2],
                            ],
                            [
                                'attribute' => 'final_price',
                                'format' => ['decimal', 2],
                            ],
                            'team.name',
                            'ppkom_name',
                            'ppkom_nip',
                            'objectType.name',
                            [
                                'attribute' => 'work_range_start',
                                'format' => ['decimal', 2],
                            ],
                            [
                                'attribute' => 'work_range_end',
                                'format' => ['decimal', 2],
                            ],
                            'criticalCondition.name',
                            'critical_day_at',
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
                <div class="col-md-4">
                    <p>
                        <?= Html::a(Yii::t('technicalspec', 'Add Cco'), ['cco/create-cco', 'taskid' => Yii::$app->hasher->encode($model->id)], [
                            'class' => 'btn btn-warning',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to add new cco?'),
                                'method' => 'post',
                            ],
                        ]) ?>
                    </p>
                    <div class="row">
                        <div class="col-md-12 shadow-sm overflow-hidden" style="border-radius: 12px;">
                            <?= GridView::widget(
                                [
                                    'dataProvider' => $ccoDataProvider,
                                    'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                                    'filterModel' => $ccoModel,
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],

                                        'name',
                                        // 'monitoring_task_id',
                                        // 'monitoring_task_name:ntext',
                                        // 'cco',
                                        [
                                            'attribute' => 'file_name',
                                            'format' => 'html',
                                            'value' => function ($model) {
                                                if (($model->file_name)) {
                                                    return Html::a('Download', $model->file_name, ['class' => 'btn btn-primary']);
                                                }
                                                return '';
                                            }
                                        ],
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
                                            'headerOptions' => ['width' => '80'],
                                            'template' => '{view} {update} {delete}',
                                            'buttons' => [
                                                'view' => function ($url, $model, $key) {

                                                    return Html::a(
                                                        '<span class="fas fa-eye"></span>',
                                                        [
                                                            Url::toRoute(['cco/view', 'id' => Yii::$app->hasher->encode($model->id)])
                                                        ]
                                                    );
                                                },
                                                'update' => function ($url, $model, $key) {

                                                    return Html::a(
                                                        '<span class="fas fa-edit"></span>',
                                                        [
                                                            Url::toRoute(['cco/update', 'id' => Yii::$app->hasher->encode($model->id)])
                                                        ]
                                                    );
                                                },
                                                'delete' => function ($action, $model, $key) {

                                                    return Html::a(
                                                        '<span class="fas fa-trash"></span>',
                                                        [
                                                            Url::toRoute(['cco/delete', 'id' => Yii::$app->hasher->encode($model->id)])
                                                        ],
                                                        [
                                                            //'class' => 'btn btn-danger',
                                                            'data' => [
                                                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                                'method' => 'post',
                                                                'params' => [], // <- extra level
                                                            ],
                                                        ]
                                                    );
                                                },
                                            ],
                                        ],
                                    ],
                                ],
                            ); ?>
                        </div>
                    </div>
                </div>
            </div>
            <p>
                <?= Html::a(Yii::t('technicalspec', 'Add Ded'), ['default/create', 'taskid' => Yii::$app->hasher->encode($model->id)], [
                    'class' => 'btn btn-primary',
                ]) ?>
            </p>
            <div class="row">
                <div class="col-md-12">
                    <?= GridView::widget(
                        [
                            'panel' => [
                                'type' => GridView::TYPE_PRIMARY,
                                // 'heading' => '',
                                // 'footer' => '',
                                'before' => false,
                                // 'after' => false,
                            ],
                            'dataProvider' => $dedDataProvider,
                            'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                            'filterModel' => $dedModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                [
                                    'attribute' => 'cco',
                                    'value' => function ($model) {
                                        if ($model->cco == 0) {
                                            return 'MCO';
                                        }
                                        return 'CCO ' . $model->cco;
                                    },
                                    'filter' => $ccoDropDown,
                                ],
                                'name',
                                // 'monitoring_task_id',
                                // 'monitoring_task_name:ntext',
                                // 'ref_component_category_id',
                                'ref_component_category_name',
                                //'ref_component_id',
                                'ref_component_name',
                                'specification:ntext',
                                'quantity',
                                // 'notes:ntext',
                                //'created_at:datetime',
                                /*[
                                'attribute' => 'created_by', 
                                'value' => 'maker',
                                'filter' => false
                                ],*/
                                //'updated_at:datetime',
                                /*[
                                'attribute' => 'updated_by', 
                                'value' => 'updater',
                                'filter' => false
                                ],*/

                                [
                                    'class' => 'kartik\grid\ExpandRowColumn',
                                    'width' => '50px',
                                    'value' => function ($model, $key, $index, $column) {
                                        return GridView::ROW_COLLAPSED;
                                    },
                                    'detailUrl' => Url::to(['ded-files/index-view']),
                                    // 'headerOptions' => ['class' => 'kartik-sheet-style'],
                                    'expandOneOnly' => true,
                                    'allowBatchToggle' => false,
                                    'expandIcon' => '<h4 >Files </h4>+',
                                    'collapseIcon' => '<h4 >Files </h4>-',
                                    'header' => '',
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => Yii::t('app', 'Action'),
                                    'headerOptions' => ['width' => '80'],
                                    'template' => '{view} {update} {delete}',
                                    'buttons' => [
                                        'view' => function ($url, $model, $key) {

                                            return Html::a(
                                                '<span class="fas fa-eye"></span>',
                                                [
                                                    Url::toRoute(['default/view', 'id' => Yii::$app->hasher->encode($model->id)])
                                                ]
                                            );
                                        },
                                        'update' => function ($url, $model, $key) {

                                            return Html::a(
                                                '<span class="fas fa-edit"></span>',
                                                [
                                                    Url::toRoute(['default/update', 'id' => Yii::$app->hasher->encode($model->id)])
                                                ]
                                            );
                                        },
                                        'delete' => function ($action, $model, $key) {

                                            return Html::a(
                                                '<span class="fas fa-trash"></span>',
                                                [
                                                    Url::toRoute(['default/delete', 'id' => Yii::$app->hasher->encode($model->id)])
                                                ],
                                                [
                                                    //'class' => 'btn btn-danger',
                                                    'data' => [
                                                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                        'method' => 'post',
                                                        'params' => [], // <- extra level
                                                    ],
                                                ]
                                            );
                                        },
                                    ],
                                ],
                            ],
                        ],
                    ); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <h4><?= Yii::t('monitoring', 'Monitoring Progresses') ?></h4>
                    <?=
                    GridView::widget([
                        'dataProvider' => $progressDataProvider,
                        // 'tableOptions' => ['class' => 'table table-striped table-bordered', 'style'=>'font-size:11px'],
                        'filterModel' => $progressModel,
                        'showFooter' => true,
                        'columns' => [
                            'order_no',
                            // 'name:ntext',
                            // 'description:ntext',
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
                            [
                                'class' => 'kartik\grid\ExpandRowColumn',
                                'width' => '50px',
                                'value' => function ($model, $key, $index, $column) {
                                    return GridView::ROW_COLLAPSED;
                                },
                                'detailUrl' => Url::to(['monitoring-spec/index-view']),
                                // 'headerOptions' => ['class' => 'kartik-sheet-style'],
                                'expandOneOnly' => true,
                                'allowBatchToggle' => false,
                                'expandIcon' => '<h4 >Recorded Data </h4>+',
                                'collapseIcon' => '<h4 >Recorded Data </h4>-',
                                'header' => '',
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => Yii::t('app', 'Action'),
                                'headerOptions' => ['width' => '80'],
                                'template' => '{view} {add}',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {
                                        return Html::a(
                                            '<span class="fas fa-eye"></span>',
                                            [
                                                Url::toRoute(['/monitoring/progress/view', 'id' => Yii::$app->hasher->encode($model->id)])
                                            ]
                                        );
                                    },
                                    'add' => function ($url, $model, $key) {
                                        return Html::a(
                                            '<span class="fas fa-plus"></span>',
                                            [
                                                Url::toRoute(['monitoring-spec/create', 'progressid' => Yii::$app->hasher->encode($model->id)])
                                            ]
                                        );
                                    },
                                ],
                            ],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php Pjax::end() ?>
<?=
$this->render('_print_modal', ['model' => $model]);
?>