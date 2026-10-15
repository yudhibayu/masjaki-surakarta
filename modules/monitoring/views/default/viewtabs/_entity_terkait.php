<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="row">
    <div class="col-md-12 table-responsive">
        <h4><?= Yii::t('app', 'Planners') ?></h4>
        <?=
        GridView::widget([
            'dataProvider' => $plannerDataProvider,
            // 'tableOptions' => ['class' => 'table table-striped table-bordered', 'style'=>'font-size:11px'],
            'filterModel' => $plannerModel,
            'showFooter' => true,
            'columns' => [
                'planner_name',
                [
                    'attribute' => 'is_active',
                    'value' => function ($model) {
                        $val = [
                            0 => Yii::t('app', 'No'),
                            1 => Yii::t('app', 'Yes')
                        ];
                        return $val[$model->is_active];
                    }
                ],
                'description:ntext',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => Yii::t('app', 'Action'),
                    'headerOptions' => ['width' => '80'],
                    'template' => '{edit} {delete}',
                    'buttons' => [
                        'edit' => function ($url, $model, $key) {
                            return Html::a(
                                '<span class="fas fa-edit"></span>',
                                [
                                    Url::toRoute(['default/edit-planner', 'id' => Yii::$app->hasher->encode($model->id)])
                                ],
                                [
                                    'class' => 'btn btn-xs btn-warning',
                                    'style' => 'margin: 2px;width: 35px;',
                                ]
                            );
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a(
                                '<span class="fas fa-trash"></span>',
                                [
                                    Url::toRoute(['default/delete-planner', 'id' => Yii::$app->hasher->encode($model->id)])
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
                    'footer' => Html::a(Yii::t('app', 'Add'), Url::to(['default/add-planner', 'id' => Yii::$app->hasher->encode($model->id)]), ['class' => 'btn btn-primary',]),
                ],
            ],
        ]);
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12 table-responsive">
        <h4><?= Yii::t('app', 'Supervisors') ?></h4>
        <?=
        GridView::widget([
            'dataProvider' => $supervisorDataProvider,
            // 'tableOptions' => ['class' => 'table table-striped table-bordered', 'style'=>'font-size:11px'],
            'filterModel' => $supervisorModel,
            'showFooter' => true,
            'columns' => [
                'supervisor_name',
                [
                    'attribute' => 'is_active',
                    'value' => function ($model) {
                        $val = [
                            0 => Yii::t('app', 'No'),
                            1 => Yii::t('app', 'Yes')
                        ];
                        return $val[$model->is_active];
                    }
                ],
                'description:ntext',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => Yii::t('app', 'Action'),
                    'headerOptions' => ['width' => '80'],
                    'template' => '{edit} {delete}',
                    'buttons' => [
                        'edit' => function ($url, $model, $key) {
                            return Html::a(
                                '<span class="fas fa-edit"></span>',
                                [
                                    Url::toRoute(['default/edit-supervisor', 'id' => Yii::$app->hasher->encode($model->id)])
                                ],
                                [
                                    'class' => 'btn btn-xs btn-warning',
                                    'style' => 'margin: 2px;width: 35px;',
                                ]
                            );
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a(
                                '<span class="fas fa-trash"></span>',
                                [
                                    Url::toRoute(['default/delete-supervisor', 'id' => Yii::$app->hasher->encode($model->id)])
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
                    'footer' => Html::a(Yii::t('app', 'Add'), Url::to(['default/add-supervisor', 'id' => Yii::$app->hasher->encode($model->id)]), ['class' => 'btn btn-primary',]),
                ],
            ],
        ]);
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12 table-responsive">
        <h4><?= Yii::t('app', 'Eksekutors') ?></h4>
        <?=
        GridView::widget([
            'dataProvider' => $eksekutorDataProvider,
            // 'tableOptions' => ['class' => 'table table-striped table-bordered', 'style'=>'font-size:11px'],
            'filterModel' => $eksekutorModel,
            'showFooter' => true,
            'columns' => [
                'eksekutor_name',
                [
                    'attribute' => 'is_active',
                    'value' => function ($model) {
                        $val = [
                            0 => Yii::t('app', 'No'),
                            1 => Yii::t('app', 'Yes')
                        ];
                        return $val[$model->is_active];
                    }
                ],
                'description:ntext',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => Yii::t('app', 'Action'),
                    'headerOptions' => ['width' => '80'],
                    'template' => '{edit} {delete}',
                    'buttons' => [
                        'edit' => function ($url, $model, $key) {
                            return Html::a(
                                '<span class="fas fa-edit"></span>',
                                [
                                    Url::toRoute(['default/edit-eksekutor', 'id' => Yii::$app->hasher->encode($model->id)])
                                ],
                                [
                                    'class' => 'btn btn-xs btn-warning',
                                    'style' => 'margin: 2px;width: 35px;',
                                ]
                            );
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a(
                                '<span class="fas fa-trash"></span>',
                                [
                                    Url::toRoute(['default/delete-eksekutor', 'id' => Yii::$app->hasher->encode($model->id)])
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
                    'footer' => Html::a(Yii::t('app', 'Add'), Url::to(['default/add-eksekutor', 'id' => Yii::$app->hasher->encode($model->id)]), ['class' => 'btn btn-primary',]),
                ],
            ],
        ]);
        ?>
    </div>
</div>