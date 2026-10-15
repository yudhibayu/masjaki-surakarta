<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

?>
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
                ['class' => 'yii\grid\SerialColumn'],
                'progress_date:date',
                'order_no',
                // 'name:ntext',
                // 'description:ntext',
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
                    'attribute' => 'verified',
                    'value' => function ($model) {
                        if ($model->verified) {
                            return Yii::t('app', 'Yes');
                        }
                        return Yii::t('app', 'No');
                    },
                    'filter' => [
                        0 => Yii::t('app', 'No'),
                        1 => Yii::t('app', 'Yes'),
                    ],
                ],
                [
                    'class' => 'kartik\grid\ExpandRowColumn',
                    'width' => '50px',
                    'value' => function ($model, $key, $index, $column) {
                        return GridView::ROW_COLLAPSED;
                    },
                    'detailUrl' => Url::to(['weather/index-view']),
                    'headerOptions' => ['width' => '80'],
                    'expandOneOnly' => true,
                    'allowBatchToggle' => false,
                    'expandIcon' => '<span class="fas fa-caret-square-down"></span>',
                    'collapseIcon' => '<span class="fas fa-caret-square-up"></span>',
                    // 'label' => 'Data Pembuktian Spesifikasi',
                    'header' => 'Data Cuaca',
                    'collapseAllTitle' => 'Data Cuaca',
                    'expandAllTitle' => 'Data Cuaca',
                    'collapseTitle' => 'Data Cuaca',
                    'expandTitle' => 'Data Cuaca',
                    'mergeHeader' => true

                ],
                [
                    'class' => 'kartik\grid\ExpandRowColumn',
                    'width' => '50px',
                    'value' => function ($model, $key, $index, $column) {
                        return GridView::ROW_COLLAPSED;
                    },
                    'detailUrl' => Url::to(['/technicalspec/monitoring-spec/index-view']),
                    'headerOptions' => ['width' => '80'],
                    'expandOneOnly' => true,
                    'allowBatchToggle' => false,
                    'expandIcon' => '<span class="fas fa-caret-square-down"></span>',
                    'collapseIcon' => '<span class="fas fa-caret-square-up"></span>',
                    // 'label' => 'Data Pembuktian Spesifikasi',
                    'header' => 'Data Pembuktian Spesifikasi',
                    'collapseAllTitle' => 'Data Pembuktian Spesifikasi',
                    'expandAllTitle' => 'Data Pembuktian Spesifikasi',
                    'collapseTitle' => 'Data Pembuktian Spesifikasi',
                    'expandTitle' => 'Data Pembuktian Spesifikasi',
                    'mergeHeader' => true

                ],
                [
                    'class' => 'kartik\grid\ExpandRowColumn',
                    'width' => '50px',
                    'value' => function ($model, $key, $index, $column) {
                        return GridView::ROW_COLLAPSED;
                    },
                    'detailUrl' => Url::to(['progress-file/index-view']),
                    'headerOptions' => ['width' => '80'],
                    'expandOneOnly' => true,
                    'allowBatchToggle' => false,
                    'expandIcon' => '<span class="fas fa-caret-square-down"></span>',
                    'collapseIcon' => '<span class="fas fa-caret-square-up"></span>',
                    // 'label' => 'Data Pembuktian Spesifikasi',
                    'header' => 'Gambar Monitoring',
                    'collapseAllTitle' => 'Gambar Monitoring',
                    'expandAllTitle' => 'Gambar Monitoring',
                    'collapseTitle' => 'Gambar Monitoring',
                    'expandTitle' => 'Gambar Monitoring',
                    'mergeHeader' => true

                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => Yii::t('app', 'Action'),
                    'headerOptions' => ['width' => '80'],
                    'template' => '{view} {edit} {addpicture} {verifymonitoring}',
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            return Html::a(
                                '<span class="fas fa-eye"></span>',
                                [
                                    Url::toRoute(['progress/view', 'id' => Yii::$app->hasher->encode($model->id)])
                                ],
                                [
                                    'class' => 'btn btn-xs btn-primary',
                                    'style' => 'margin: 2px;width: 35px;',
                                ]
                            );
                        },
                        'edit' => function ($url, $model, $key) {
                            return Html::a(
                                '<span class="fas fa-edit"></span>',
                                [
                                    Url::toRoute(['progress/update', 'id' => Yii::$app->hasher->encode($model->id)])
                                ],
                                [
                                    'class' => 'btn btn-xs btn-warning',
                                    'style' => 'margin: 2px;width: 35px;',
                                ]
                            );
                        },
                        'addpicture' => function ($url, $model, $key) {
                            return Html::a(
                                '<span class="fas fa-image"></span>',
                                [
                                    Url::toRoute(['progress-file/create', 'id' => Yii::$app->hasher->encode($model->id)])
                                ],
                                [
                                    'class' => 'btn btn-xs btn-success',
                                    'style' => 'margin: 2px;width: 35px;',
                                ]
                            );
                        },
                        'verifymonitoring' => function ($url, $model, $key) {

                            if (Yii::$app->user->can('Administrator') || Yii::$app->user->can('OPD')) {
                                return Html::a(
                                    $model->verified ?
                                        '<span class="fas fa-check-square"></span>' :
                                        '<span class="fas fa-square"></span>',
                                    [
                                        Url::toRoute(['progress/verify-progress', 'progressid' => Yii::$app->hasher->encode($model->id)])
                                    ],
                                    [
                                        'class' => $model->verified ? 'btn btn-xs btn-danger' : 'btn btn-xs btn-primary',
                                        'style' => 'margin: 2px;width: 35px;',
                                        'data' => [
                                            'confirm' => $model->verified ? Yii::t('app', 'Are you sure you want to unverify this item?') : Yii::t('app', 'Are you sure you want to verify this item?'),
                                            'method' => 'post',
                                            'pjax' => '0',
                                            'bs-toggle' => "tooltip",
                                        ],
                                        'title' => 'PPK to verify this monitoring progress',
                                    ]
                                );
                            }
                            return '';
                        },
                    ],
                    'footer' => Html::a(Yii::t('app', 'Add'), Url::to(['progress/create', 'id' => Yii::$app->hasher->encode($model->id)]), ['class' => 'btn btn-primary',]),
                ],
            ],
        ]);
        ?>
    </div>
</div>