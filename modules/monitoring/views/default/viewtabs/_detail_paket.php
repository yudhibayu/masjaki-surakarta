<?php

use app\modules\monitoring\models\MonitoringTaskFile;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\ListView;

?>
<div class="row">
    <div class="col-md-7">
        <h4>Detail Paket</h4>
        <?= DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
            'attributes' => [
                'name:ntext',
                [
                    'attribute' => 'idrup',
                    'value' => function ($model) {
                        $rupSource = $model->rupSource;
                        return $rupSource ? $rupSource->namecode : $model->idrup;
                    },
                ],
                'pbj.namecode',
                // 'unit_id',
                'unit_name',
                'team_name',
                'location_description:ntext',
                'kecamatan.name',
                'kelurahan.name',
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
                // 'criticalCondition.name',
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
    <div class="col-md-5">
        <div class="row">
            <div class="col-md-12">
                <h4>Progress Pekerjaan</h4>
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                    'attributes' => [
                        [
                            'attribute' => 'is_active',
                            'value' => function ($model) {
                                return $model->taskActiveStatusName()[$model->is_active];
                            }
                        ],
                        [
                            'attribute' => 'implementation_status',
                            'value' => function ($model) {
                                return $model->implementationStatusNames()[$model->implementation_status];
                            }
                        ],
                        [
                            'attribute' => 'is_bast_done',
                            'value' => function ($model) {
                                return $model->bastDoneStatusName()[$model->is_bast_done];
                            }
                        ],
                        [
                            'attribute' => 'bast_no',
                        ],
                        [
                            'attribute' => 'bast_date',
                            'format' => 'date',
                        ],
                        'start_date:date',
                        // 'estimated_week_finished',
                        'work_days',
                        'end_date:date',
                        'work_volume',
                        'work_units_of',
                        'work_finished_number',
                        'finally_finished_date:date',
                        [
                            'attribute' => 'latest_plan_financial_budget',
                            'format' => ['decimal', 2],
                        ],
                        [
                            'attribute' => 'latest_plan_financial_budget_percent',
                            'format' => ['decimal', 2],
                        ],
                        [
                            'attribute' => 'latest_plan_physical_percent',
                            'format' => ['decimal', 2],
                        ],
                        [
                            'attribute' => 'latest_monitored_financial_budget',
                            'format' => ['decimal', 2],
                        ],
                        [
                            'attribute' => 'latest_monitored_financial_budget_percent',
                            'format' => ['decimal', 2],
                        ],
                        [
                            'attribute' => 'latest_monitored_physical_percent',
                            'format' => ['decimal', 2],
                        ],
                    ],
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h4>Detail Kontrak</h4>
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                    'attributes' => [
                        'contractor_name',
                        'contract_number',
                        'contract_date:date',
                        'spmk_number',
                        'spmk_date:date',
                        [
                            'attribute' => 'final_price',
                            'format' => ['decimal', 2],
                        ],
                    ],
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h4>K3</h4>
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                    'attributes' => [
                        [
                            'attribute' => 'is_bpjstk',
                            'value' => function ($model) {
                                return $model->bpjstkStatusName()[$model->is_bpjstk];
                            }
                        ],
                        'bpjstk_number',
                        'bpjstk_contrib_amount:currency',
                        'bpjstk_number_workers_protected',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
<?php if (Yii::$app->user->can('Administrator') || Yii::$app->user->can('OPD')) : ?>
    <div class="row">
        <div class="col-md-12 table-responsive">
            <h4><?= Yii::t('app', 'Taggings') ?></h4>
            <?=
            GridView::widget([
                'dataProvider' => $taggingDataProvider,
                // 'tableOptions' => ['class' => 'table table-striped table-bordered', 'style'=>'font-size:11px'],
                'filterModel' => $taggingModel,
                'showFooter' => true,
                'columns' => [
                    'name',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => Yii::t('app', 'Action'),
                        'headerOptions' => ['width' => '80'],
                        'template' => '{delete}',
                        'buttons' => [
                            'delete' => function ($url, $model, $key) {
                                return Html::a(
                                    '<span class="fas fa-trash"></span>',
                                    [
                                        Url::toRoute(['default/delete-tagging', 'id' => Yii::$app->hasher->encode($model->id)])
                                    ],
                                    [
                                        'class' => 'btn btn-xs btn-danger',
                                        'style' => 'margin: 2px;width: 35px;',
                                        'data' => [
                                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                            'method' => 'post',
                                        ],
                                    ]
                                );
                            },
                        ],
                        'footer' => Html::a(Yii::t('app', 'Add'), Url::to(['default/add-tagging', 'id' => Yii::$app->hasher->encode($model->id)]), ['class' => 'btn btn-primary',]),
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h4 style="display: inline-block"><?= Yii::t('app', 'File Document') ?></h4>
            <!-- Button trigger modal -->
            <button type="button" style="float:right" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="bx bx-plus"></i> <?= Yii::t('app', 'Add File') ?>
            </button>
            <br>
            <i style="color: red;"><span class="fas fa-exclamation-triangle "></span> Dokumen yang harus diisi minimal data kontrak, spmk, AMDAL/ANDALALIN, dan RMPK </i>
            <br>

            <?php
            $dataProviderfile = new ActiveDataProvider([
                'query' => MonitoringTaskFile::find()->where(['IN', 'monitoring_task_id', $model->id])->orderBy('id ASC'),
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);
            ?>

            <div class="table-responsive">
                <table class=" table table-bordered detail-view" width="100%" height="100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="35%">
                                <?= Yii::t('app', 'Photo/File') ?>
                            </th>
                            <th class="text-center" width="55%">
                                <?= Yii::t('app', 'Description') ?>
                            </th>
                            <th class="text-center" width="10%">
                                <?= Yii::t('app', 'Action') ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?= ListView::widget([
                            'layout' => '{items}',
                            'dataProvider' => $dataProviderfile,
                            'itemView' => '_list_file',
                        ]);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 shadow-sm overflow-hidden" style="border-radius: 12px;">
            <h4><?= Yii::t('technicalspec', 'McoCco') ?></h4>
            <?= GridView::widget(
                [
                    'dataProvider' => $ccoDataProvider,
                    'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                    'filterModel' => $ccoModel,
                    'showFooter' => true,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'name',
                        // 'monitoring_task_id',
                        // 'monitoring_task_name:ntext',
                        // 'cco',
                        // [
                        //     'attribute' => 'file_name',
                        //     'format' => 'html',
                        //     'value' => function ($model) {
                        //         if (($model->file_name)) {
                        //             return Html::a('Download', $model->file_name, ['class' => 'btn btn-primary']);
                        //         }
                        //         return '';
                        //     }
                        // ],
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
                            'class' => 'kartik\grid\ExpandRowColumn',
                            'width' => '50px',
                            'value' => function ($model, $key, $index, $column) {
                                return GridView::ROW_COLLAPSED;
                            },
                            'detailUrl' => Url::to(['/technicalspec/cco/index-view']),
                            // 'headerOptions' => ['class' => 'kartik-sheet-style'],
                            'expandOneOnly' => true,
                            'allowBatchToggle' => false,
                            'expandIcon' => '<span class="fas fa-caret-square-down"></span>',
                            'collapseIcon' => '<span class="fas fa-caret-square-up"></span>',
                            'header' => 'Data File',
                            'mergeHeader' => true
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => Yii::t('app', 'Action'),
                            'headerOptions' => ['width' => '220'],
                            'template' => '{view} {add-file} {delete}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {

                                    return Html::a(
                                        '<span class="fas fa-eye"></span>',
                                        [
                                            Url::toRoute(['/technicalspec/cco/view', 'id' => Yii::$app->hasher->encode($model->id)])
                                        ],
                                        [
                                            'class' => 'btn btn-xs btn-primary',
                                            'style' => 'margin: 2px;width: 35px;',
                                        ]
                                    );
                                },
                                'add-file' => function ($url, $model, $key) {

                                    return Html::a(
                                        '<span class="fas fa-plus-circle"></span>',
                                        [
                                            Url::toRoute(['/technicalspec/cco/add-file', 'id' => Yii::$app->hasher->encode($model->id)])
                                        ],
                                        [
                                            'class' => 'btn btn-xs btn-success',
                                            'style' => 'margin: 2px;width: 35px;',
                                        ]
                                    );
                                },
                                'update' => function ($url, $model, $key) {

                                    return Html::a(
                                        '<span class="fas fa-edit"></span>',
                                        [
                                            Url::toRoute(['/technicalspec/cco/update', 'id' => Yii::$app->hasher->encode($model->id)])
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
                                            Url::toRoute(['/technicalspec/cco/delete', 'id' => Yii::$app->hasher->encode($model->id)])
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
                            'footer' => Html::a(Yii::t('technicalspec', 'Add Cco'), ['/technicalspec/cco/create-cco', 'taskid' => Yii::$app->hasher->encode($model->id)], [
                                'class' => 'btn btn-warning',
                                'data' => [
                                    'confirm' => Yii::t('app', 'Are you sure you want to add new cco?'),
                                    'method' => 'post',
                                ],
                            ]),
                        ],
                    ],
                ],
            ); ?>
        </div>
    </div>
<?php endif; ?>
