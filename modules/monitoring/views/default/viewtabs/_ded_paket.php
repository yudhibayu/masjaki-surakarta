<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="row">
    <div class="col-md-12 shadow-sm overflow-hidden">
        <h4>Data DED</h4>
        <?= GridView::widget(
            [
                'dataProvider' => $dedDataProvider,
                'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                'filterModel' => $dedModel,
                'showFooter' => true,
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
                    [
                        'attribute' => 'quantity',
                        'value' => 'kuantitasSatuan',
                    ],
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
                        'detailUrl' => Url::to(['/technicalspec/ded-files/index-view']),
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
                        'headerOptions' => ['width' => '140'],
                        'template' => '{view} {update} {delete}',
                        'buttons' => [
                            'view' => function ($url, $model, $key) {

                                return Html::a(
                                    '<span class="fas fa-eye"></span>',
                                    [
                                        Url::toRoute(['/technicalspec/default/view', 'id' => Yii::$app->hasher->encode($model->id)])
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
                                        Url::toRoute(['/technicalspec/default/update', 'id' => Yii::$app->hasher->encode($model->id)])
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
                                        Url::toRoute(['/technicalspec/default/delete', 'id' => Yii::$app->hasher->encode($model->id)])
                                    ],
                                    [
                                        'class' => 'btn btn-xs btn-danger',
                                        'style' => 'margin: 2px;width: 35px;',
                                        'data' => [
                                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                            'method' => 'post',
                                            'params' => [], // <- extra level
                                        ],
                                    ]
                                );
                            },
                        ],
                        'footer' => Html::a(Yii::t('technicalspec', 'Add Ded'), ['/technicalspec/default/create', 'taskid' => Yii::$app->hasher->encode($model->id)], [
                            'class' => 'btn btn-primary',
                        ]),
                    ],
                ],
            ],
        ); ?>
    </div>
</div>