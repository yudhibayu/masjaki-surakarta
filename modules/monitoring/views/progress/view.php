<?php

use kartik\editable\Editable;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use newerton\fancybox3\FancyBox;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\models\MonitoringProgress */

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
        <h4><?= $model->verified ? Yii::t('app', 'Verified') : Yii::t('app', 'Not Verified'); ?></h4>
    </div>
    <div class="card-body">
        <div class="monitoring-progress-view">

            <div class="text-right">
                <?php if (Yii::$app->user->can('Administrator') || Yii::$app->user->can('OPD')) : ?>
                    <?= Html::a(
                        $model->verified ?
                            Yii::t('monitoring', 'Unverify') :
                            Yii::t('monitoring', 'Verify'),
                        [
                            Url::toRoute(['progress/verify-progress', 'progressid' => Yii::$app->hasher->encode($model->id)])
                        ],
                        [
                            'class' => $model->verified ? 'btn btn-danger' : 'btn btn-primary',
                            'data' => [
                                'confirm' => $model->verified ? Yii::t('app', 'Are you sure you want to unverify this item?') : Yii::t('app', 'Are you sure you want to verify this item?'),
                                'method' => 'post',
                                'pjax' => '0',
                                'bs-toggle' => "tooltip",
                            ],
                            'title' => Yii::t('app', 'PPK to verify this monitoring progress'),
                        ]
                    ) ?>
                <?php endif; ?>
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
                        [
                            'attribute' => 'verified',
                            'value' => function ($model) {
                                if ($model->verified) {
                                    return Yii::t('app', 'Verified');
                                }
                                return Yii::t('app', 'Not Verified');
                            },
                        ],
                        'order_no',
                        // 'monitoring_task_id',
                        'monitoring_task_name:ntext',
                        'progress_date:date',
                        'working_hours_start:datetime',
                        'working_hours_end:datetime',
                        'number_of_workers',
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
                        'work_being_done:ntext',
                        'work_items_of_potential_accident:ntext',
                        'work_items_of_occured_accident:ntext',
                        'num_minor_accident',
                        'num_moderate_accident',
                        'num_serious_accident',
                        'problem:ntext',
                        'solution:ntext',
                        'notes:ntext',
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
            <div class="row">
                <div class="col-md-12">
                    <h4><?= Yii::t('monitoring', 'Weathers') ?></h4>
                    <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                        <?= GridView::widget(
                            [
                                'dataProvider' => $weatherDataProvider,
                                'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                                'filterModel' => $weatherModel,
                                'showFooter' => true,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],

                                    'name',
                                    'refCuaca.name',
                                    // 'monitoringProgress.name',
                                    'time_from:datetime',
                                    'time_to:datetime',
                                    'note:ntext',
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
                                        'template' => '{view} {update} {delete}',
                                        'buttons' => [
                                            'view' => function ($url, $model, $key) {

                                                return Html::a(
                                                    '<span class="fas fa-eye"></span>',
                                                    [
                                                        Url::toRoute(['weather/view', 'id' => Yii::$app->hasher->encode($model->id)])
                                                    ],
                                                    [
                                                        'class' => 'btn btn-xs btn-primary',
                                                        'style' => 'margin: 2px;width: 35px;',
                                                    ]
                                                );
                                            },
                                            'update' => function ($url, $model, $key) {

                                                return Html::a(
                                                    '<span class="fas fa-edit"></span>',
                                                    [
                                                        Url::toRoute(['weather/update', 'id' => Yii::$app->hasher->encode($model->id)])
                                                    ],
                                                    [
                                                        'class' => 'btn btn-xs btn-warning',
                                                        'style' => 'margin: 2px;width: 35px;',
                                                    ]
                                                );
                                            },
                                            'delete' => function ($action, $model, $key) {

                                                return Html::a(
                                                    '<span class="fas fa-trash"></span>',
                                                    [
                                                        Url::toRoute(['weather/delete', 'id' => Yii::$app->hasher->encode($model->id)])
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
                                        ], 'footer' => Html::a(Yii::t('app', 'Add'), Url::to(['weather/create', 'id' => Yii::$app->hasher->encode($model->id)]), ['class' => 'btn btn-primary', 'data' => ['pjax' => 0],]),
                                    ],
                                ],
                            ],
                        ); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h4><?= Yii::t('monitoring', 'Monitoring Progress Pictures') ?></h4>
                    <?=
                    GridView::widget([
                        'dataProvider' => $picturesDataProvider,
                        // 'tableOptions' => ['class' => 'table table-striped table-bordered', 'style'=>'font-size:11px'],
                        'filterModel' => $picturesModel,
                        'showFooter' => true,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'name:ntext',
                            // 'monitoring_task_id',
                            // 'monitoring_task_name:ntext',
                            // 'file_name',
                            // 'file_url',
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
                                'template' => '{view}',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {
                                        return Html::a(
                                            '<span class="fas fa-eye"></span>',
                                            [
                                                Url::to(['progress-file/view', 'id' => Yii::$app->hasher->encode($model->id)])
                                            ],
                                            [
                                                'class' => 'btn btn-xs btn-primary',
                                                'style' => 'margin: 2px;width: 35px;',
                                            ]
                                        );
                                    },
                                ],
                                'footer' => Html::a(Yii::t('app', 'Add'), Url::to(['progress-file/create', 'id' => Yii::$app->hasher->encode($model->id)]), ['class' => 'btn btn-primary', 'data' => ['pjax' => 0], 'target' => '_blank']),
                            ],
                        ],
                    ]);
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h4><?= Yii::t('monitoring', 'Monitoring Specification') ?></h4>
                    <?= Html::a(Yii::t('app', 'Add'), ['/technicalspec/monitoring-spec/create', 'progressid' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-primary']) ?>
                    <?= ListView::widget(
                        [
                            'dataProvider' => $specProvider,
                            'itemView' => '_view_monitoring_spec',
                        ],
                    ); ?>
                </div>
            </div>
            <!-- <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), ['default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)], ['class' => 'btn btn-warning']) ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => Yii::$app->hasher->encode($model->id)], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </div> -->
        </div>
    </div>
</section>