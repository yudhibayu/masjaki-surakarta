<?php

use app\modules\construction\models\RegistrantTraining;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\construction\models\WorkTrainingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pelatihan Kerja';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->registerJs(
    <<< EOT_JS_CODE
    
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
});

       

EOT_JS_CODE

);
?>
<style>
    @media (max-width: 576px) {
        .tabs-wrapper {
            overflow-x: auto;
            overflow-y: hidden;
            white-space: nowrap;
        }

        .nav-tabs {
            display: inline-flex;
            padding: 0;
            margin: 0;
            flex-wrap: nowrap;
        }

        .nav-tabs .nav-item {
            flex: 0 0 auto;
        }

        .nav-tabs .nav-link {
            display: inline-block;
            padding: 0.5rem 1rem;
            white-space: nowrap;
        }
    }
</style>
<section class="card">
    <div class="card-header">
        <h3><i class="menu-icon tf-icons bx bx-hard-hat"></i> <?= Html::encode($this->title) ?> - List Data</h3>
        <p>Manajemen Pelatihan Kerja</p>
    </div>
    <?php if (Yii::$app->user->can('Administrator')) : ?>
        <div class="d-sm-flex align-items-center justify-content-between border-bottom p-3">
            <div class="tabs-wrapper">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab"
                            aria-controls="overview" aria-selected="true">
                            <i class='bx bx-building'></i> Semua
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#nonpub" role="tab"
                            aria-selected="false">
                            <i class='bx bx-x-circle'></i> Belum Terpublikasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#pub" role="tab"
                            aria-selected="false">
                            <i class='bx bx-check-circle'></i> Sudah Terpublikasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link border-0" id="more-tab" data-bs-toggle="tab" href="#more" role="tab"
                            aria-selected="false">
                            <i class='bx bx-block'></i> Ditutup
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tab-content tab-content-basic">

            <div class="margin-button">
                <p class="d-flex">
                    <?= Html::a('<i class="menu-icon tf-icons bx bx-plus fw-bold"></i>' . Yii::t('app', 'Create') . ' ' . Yii::t('app', 'Work Training'), ['create'], ['class' => 'btn btn-success fw-bold']) ?>
                </p>
            </div>

            <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="home-tab">
                <div class="work-training-index">
                    <?php Pjax::begin(['id' => 'work-training-yea-pjax']); ?>

                    <?php // echo $this->render('_search', ['model' => $searchModel]); 
                    ?>

                    <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                        <div class="table-responsive">
                            <?= GridView::widget(
                                [
                                    'dataProvider' => $dataProvider,
                                    'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover'],
                                    'filterModel' => $searchModel,
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],

                                        'training_name',
                                        'person_responsible_name',
                                        'kuota',
                                        [
                                            'attribute' => 'skills',
                                            'value' => function ($model) {
                                                return $model->skillsRelation ? $model->skillsRelation->name : null;
                                            },
                                        ],
                                        'implementation_date',
                                        'implementation_time',

                                        'year_name',
                                        // [
                                        //     'attribute' => 'id_year',
                                        //     'value' => function ($model) {
                                        //         return $model->refYear ? $model->refYear->name : null;
                                        //     },
                                        // ],
                                        [
                                            'attribute' => 'publication',
                                            'value' => function ($model) {
                                                return $model->statusPublication ? $model->statusPublication : null;
                                            },
                                        ],
                                        //'description',
                                        //'subdistrict',
                                        //'ward',
                                        //'created_at:datetime',
                                        //'updated_at:datetime',
                                        //'deleted_at',
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
                                        //'deleted_by',

                                        [
                                            'class' => 'yii\grid\ActionColumn',
                                            'header' => Yii::t('app', 'Action'),
                                            'headerOptions' => ['width' => '100'],
                                            'template' => '{view} {update} {delete} {registrant} {publish} {closed}',
                                            'buttons' => [
                                                'view' => function ($url, $model, $key) {

                                                    return Html::a(
                                                        '<span class="bx bx-show"></span>',
                                                        [
                                                            Url::toRoute(['view', 'id' => Yii::$app->hasher->encode($model->id)])
                                                        ],
                                                        [
                                                            'class' => 'btn btn-xs btn-primary',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                },
                                                'update' => function ($url, $model, $key) {
                                                    if ($model->publication == 0) {

                                                        return Html::a(
                                                            '<span class="bx bx-edit"></span>',
                                                            [
                                                                Url::toRoute(['update', 'id' => Yii::$app->hasher->encode($model->id)])
                                                            ],
                                                            [
                                                                'class' => 'btn btn-xs btn-warning',
                                                                'style' => 'margin: 2px;width: 35px;',
                                                            ]
                                                        );
                                                    }
                                                },
                                                'delete' => function ($action, $model, $key) {
                                                    return Html::a(
                                                        '<span class="bx bx-trash"></span>',
                                                        [
                                                            Url::toRoute(['delete', 'id' => Yii::$app->hasher->encode($model->id)])
                                                        ],
                                                        [
                                                            'data' => [
                                                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                                'method' => 'post',
                                                                'params' => [], // <- extra level,
                                                            ],
                                                            'class' => 'btn btn-xs btn-danger',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                },

                                                // 'registrant' => function ($action, $model, $key) {

                                                //     if ($model->publication !== 0) {
                                                //         return Html::a(
                                                //             '<span class="bx bx-user"></span>',
                                                //             [
                                                //                 Url::toRoute(['/construction/registrant-training', 'id' => Yii::$app->hasher->encode($model->id)])
                                                //             ],
                                                //             [
                                                //                 'class' => 'btn btn-xs btn-success',
                                                //                 'style' => 'margin: 2px;width: 35px;',
                                                //             ]
                                                //         );
                                                //     }
                                                // },
                                                'publish' => function ($url, $model, $key) {
                                                    if ($model->publication == $model::UNPUBLICATED) {
                                                        return Html::a(
                                                            '<span class="bx bx-log-in"></span>',
                                                            [
                                                                Url::toRoute(['publication', 'id' => Yii::$app->hasher->encode($model->id)])
                                                            ],
                                                            [
                                                                'data' => [
                                                                    'confirm' => Yii::t('app', 'Item ini akan dipublikasi, yakin?'),
                                                                    'method' => 'post',
                                                                ],
                                                                'data-toggle' => 'tooltip',
                                                                'title' => Yii::t('app', 'Publish'),
                                                                'class' => 'btn btn-xs btn-info',
                                                                'style' => 'margin: 2px;width: 35px;',
                                                            ]
                                                        );
                                                    } elseif ($model->publication == $model::PUBLICATED) {
                                                        return Html::a(
                                                            '<span class="bx bx-log-out"></span>',
                                                            [
                                                                Url::toRoute(['unpublication', 'id' => Yii::$app->hasher->encode($model->id)])
                                                            ],
                                                            [
                                                                'data' => [
                                                                    'confirm' => Yii::t('app', 'Item ini batal dipublikasi, yakin?'),
                                                                    'method' => 'post',
                                                                ],
                                                                'data-toggle' => 'tooltip',
                                                                'title' => Yii::t('app', 'Unpublish'),
                                                                'class' => 'btn btn-xs btn-danger',
                                                                'style' => 'margin: 2px;width: 35px;',
                                                            ]
                                                        );
                                                    }
                                                },
                                                'closed' => function ($url, $model, $key) {
                                                    if ($model->publication == $model::PUBLICATED) {
                                                        return Html::a(
                                                            '<span class="bx bx-window-close"></span>',
                                                            [
                                                                Url::toRoute(['closed', 'id' => Yii::$app->hasher->encode($model->id)])
                                                            ],
                                                            [
                                                                'data' => [
                                                                    'confirm' => Yii::t('app', 'Item ini akan ditutup, yakin?'),
                                                                    'method' => 'post',
                                                                ],
                                                                'data-toggle' => 'tooltip',
                                                                'title' => Yii::t('app', 'Closed'),
                                                                'class' => 'btn btn-xs btn-warning',
                                                                'style' => 'margin: 2px;width: 35px;',
                                                            ]
                                                        );
                                                    } elseif ($model->publication == $model::CLOSED) {
                                                        return Html::a(
                                                            '<span class="bx bx-window-open"></span>',
                                                            [
                                                                Url::toRoute(['open', 'id' => Yii::$app->hasher->encode($model->id)])
                                                            ],
                                                            [
                                                                'data' => [
                                                                    'confirm' => Yii::t('app', 'Item ini akan dibuka, yakin?'),
                                                                    'method' => 'post',
                                                                ],
                                                                'data-toggle' => 'tooltip',
                                                                'title' => Yii::t('app', 'Open'),
                                                                'class' => 'btn btn-xs btn-info',
                                                                'style' => 'margin: 2px;width: 35px;',
                                                            ]
                                                        );
                                                    }
                                                },
                                            ],
                                        ],
                                    ],
                                ]
                            ); ?>
                        </div>
                    </div>
                    <?php Pjax::end(); ?>

                </div>
            </div>
            <div class="tab-pane fade" id="nonpub" role="tabpanel" aria-labelledby="home-tab">
                <div class="work-training-index">
                    <?php Pjax::begin(['id' => 'work-training-yea-pjax-unpublication']); ?>

                    <?php // echo $this->render('_search', ['model' => $searchModel]); 
                    ?>

                    <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                        <div class="table-responsive">
                            <?= GridView::widget(
                                [
                                    'dataProvider' => $dataProviderUnpublicated,
                                    'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover'],
                                    'filterModel' => $searchModel,
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],

                                        'training_name',
                                        'person_responsible_name',
                                        'kuota',
                                        [
                                            'attribute' => 'skills',
                                            'value' => function ($model) {
                                                return $model->skillsRelation ? $model->skillsRelation->name : null;
                                            },
                                        ],
                                        'implementation_date',
                                        'implementation_time',

                                        'year_name',
                                        // [
                                        //     'attribute' => 'id_year',
                                        //     'value' => function ($model) {
                                        //         return $model->refYear ? $model->refYear->name : null;
                                        //     },
                                        // ],
                                        [
                                            'attribute' => 'publication',
                                            'value' => function ($model) {
                                                return $model->statusPublication ? $model->statusPublication : null;
                                            },
                                        ],
                                        //'description',
                                        //'subdistrict',
                                        //'ward',
                                        //'created_at:datetime',
                                        //'updated_at:datetime',
                                        //'deleted_at',
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
                                        //'deleted_by',

                                        [
                                            'class' => 'yii\grid\ActionColumn',
                                            'header' => Yii::t('app', 'Action'),
                                            'headerOptions' => ['width' => '100'],
                                            'template' => '{view} {update} {delete} {registrant} {publish} {closed}',
                                            'buttons' => [
                                                'view' => function ($url, $model, $key) {

                                                    return Html::a(
                                                        '<span class="bx bx-show"></span>',
                                                        [
                                                            Url::toRoute(['view', 'id' => Yii::$app->hasher->encode($model->id)])
                                                        ],
                                                        [
                                                            'class' => 'btn btn-xs btn-primary',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                },
                                                'update' => function ($url, $model, $key) {
                                                    if ($model->publication == $model::UNPUBLICATED) {

                                                        return Html::a(
                                                            '<span class="bx bx-edit"></span>',
                                                            [
                                                                Url::toRoute(['update', 'id' => Yii::$app->hasher->encode($model->id)])
                                                            ],
                                                            [
                                                                'class' => 'btn btn-xs btn-warning',
                                                                'style' => 'margin: 2px;width: 35px;',
                                                            ]
                                                        );
                                                    }
                                                },
                                                'delete' => function ($action, $model, $key) {

                                                    return Html::a(
                                                        '<span class="bx bx-trash"></span>',
                                                        [
                                                            Url::toRoute(['delete', 'id' => Yii::$app->hasher->encode($model->id)])
                                                        ],
                                                        [
                                                            'data' => [
                                                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                                'method' => 'post',
                                                                'params' => [], // <- extra level,
                                                            ],
                                                            'class' => 'btn btn-xs btn-danger',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                },

                                                // 'registrant' => function ($action, $model, $key) {

                                                //     if ($model->publication !== 0) {
                                                //         return Html::a(
                                                //             '<span class="bx bx-user"></span>',
                                                //             [
                                                //                 Url::toRoute(['/construction/registrant-training', 'id' => Yii::$app->hasher->encode($model->id)])
                                                //             ],
                                                //             [
                                                //                 'class' => 'btn btn-xs btn-success',
                                                //                 'style' => 'margin: 2px;width: 35px;',
                                                //             ]
                                                //         );
                                                //     }
                                                // },
                                                'publish' => function ($url, $model, $key) {
                                                    if ($model->publication == $model::UNPUBLICATED) {
                                                        return Html::a(
                                                            '<span class="bx bx-log-in"></span>',
                                                            [
                                                                Url::toRoute(['publication', 'id' => Yii::$app->hasher->encode($model->id)])
                                                            ],
                                                            [
                                                                'data' => [
                                                                    'confirm' => Yii::t('app', 'Item ini akan dipublikasi, yakin?'),
                                                                    'method' => 'post',
                                                                ],
                                                                'data-toggle' => 'tooltip',
                                                                'title' => Yii::t('app', 'Publish'),
                                                                'class' => 'btn btn-xs btn-info',
                                                                'style' => 'margin: 2px;width: 35px;',
                                                            ]
                                                        );
                                                    } elseif ($model->publication == $model::PUBLICATED) {
                                                        return Html::a(
                                                            '<span class="bx bx-log-out"></span>',
                                                            [
                                                                Url::toRoute(['unpublication', 'id' => Yii::$app->hasher->encode($model->id)])
                                                            ],
                                                            [
                                                                'data' => [
                                                                    'confirm' => Yii::t('app', 'Item ini batal dipublikasi, yakin?'),
                                                                    'method' => 'post',
                                                                ],
                                                                'data-toggle' => 'tooltip',
                                                                'title' => Yii::t('app', 'Unpublish'),
                                                                'class' => 'btn btn-xs btn-danger',
                                                                'style' => 'margin: 2px;width: 35px;',
                                                            ]
                                                        );
                                                    }
                                                },
                                                'closed' => function ($url, $model, $key) {
                                                    if (Yii::$app->user->can('Administrator')) {
                                                        if ($model->publication == $model::PUBLICATED) {
                                                            return Html::a(
                                                                '<span class="bx bx-window-close"></span>',
                                                                [
                                                                    Url::toRoute(['closed', 'id' => Yii::$app->hasher->encode($model->id)])
                                                                ],
                                                                [
                                                                    'data' => [
                                                                        'confirm' => Yii::t('app', 'Item ini akan ditutup, yakin?'),
                                                                        'method' => 'post',
                                                                    ],
                                                                    'data-toggle' => 'tooltip',
                                                                    'title' => Yii::t('app', 'Closed'),
                                                                    'class' => 'btn btn-xs btn-warning',
                                                                    'style' => 'margin: 2px;width: 35px;',
                                                                ]
                                                            );
                                                        } elseif ($model->publication == $model::CLOSED) {
                                                            return Html::a(
                                                                '<span class="bx bx-window-open"></span>',
                                                                [
                                                                    Url::toRoute(['open', 'id' => Yii::$app->hasher->encode($model->id)])
                                                                ],
                                                                [
                                                                    'data' => [
                                                                        'confirm' => Yii::t('app', 'Item ini akan dibuka, yakin?'),
                                                                        'method' => 'post',
                                                                    ],
                                                                    'data-toggle' => 'tooltip',
                                                                    'title' => Yii::t('app', 'Open'),
                                                                    'class' => 'btn btn-xs btn-info',
                                                                    'style' => 'margin: 2px;width: 35px;',
                                                                ]
                                                            );
                                                        }
                                                    }
                                                    return '';
                                                },
                                            ],
                                        ],
                                    ],
                                ]
                            ); ?>
                        </div>
                    </div>
                    <?php Pjax::end(); ?>

                </div>
            </div>
            <div class="tab-pane fade" id="pub" role="tabpanel" aria-labelledby="home-tab">
                <div class="work-training-index">
                    <?php Pjax::begin(['id' => 'work-training-yea-pjax-publication']); ?>

                    <?php // echo $this->render('_search', ['model' => $searchModel]); 
                    ?>

                    <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                        <div class="table-responsive">
                            <?= GridView::widget(
                                [
                                    'dataProvider' => $dataProviderPublicated,
                                    'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover'],
                                    'filterModel' => $searchModel,
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],

                                        'training_name',
                                        'person_responsible_name',
                                        'kuota',
                                        [
                                            'attribute' => 'skills',
                                            'value' => function ($model) {
                                                return $model->skillsRelation ? $model->skillsRelation->name : null;
                                            },
                                        ],
                                        'implementation_date',
                                        'implementation_time',

                                        'year_name',
                                        // [
                                        //     'attribute' => 'id_year',
                                        //     'value' => function ($model) {
                                        //         return $model->refYear ? $model->refYear->name : null;
                                        //     },
                                        // ],
                                        [
                                            'attribute' => 'publication',
                                            'value' => function ($model) {
                                                return $model->statusPublication ? $model->statusPublication : null;
                                            },
                                        ],
                                        //'description',
                                        //'subdistrict',
                                        //'ward',
                                        //'created_at:datetime',
                                        //'updated_at:datetime',
                                        //'deleted_at',
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
                                        //'deleted_by',

                                        [
                                            'class' => 'yii\grid\ActionColumn',
                                            'header' => Yii::t('app', 'Action'),
                                            'headerOptions' => ['width' => '100'],
                                            'template' => '{view} {update} {delete} {registrant} {publish} {closed} {register}',
                                            'buttons' => [
                                                'view' => function ($url, $model, $key) {
                                                    return
                                                        Html::a(
                                                            '<span class="bx bx-show"></span>',
                                                            [
                                                                Url::toRoute(['view', 'id' => Yii::$app->hasher->encode($model->id)])
                                                            ],
                                                            [
                                                                'class' => 'btn btn-xs btn-primary',
                                                                'style' => 'margin: 2px;width: 35px;',
                                                            ]
                                                        );
                                                },
                                                'update' => function ($url, $model, $key) {
                                                    if ($model->publication == $model::UNPUBLICATED) {
                                                        return
                                                            Html::a(
                                                                '<span class="bx bx-edit"></span>',
                                                                [
                                                                    Url::toRoute(['update', 'id' => Yii::$app->hasher->encode($model->id)])
                                                                ],
                                                                [
                                                                    'class' => 'btn btn-xs btn-warning',
                                                                    'style' => 'margin: 2px;width: 35px;',
                                                                ]
                                                            );
                                                    }
                                                },
                                                'delete' => function ($action, $model, $key) {
                                                    return
                                                        Html::a(
                                                            '<span class="bx bx-trash"></span>',
                                                            [
                                                                Url::toRoute(['delete', 'id' => Yii::$app->hasher->encode($model->id)])
                                                            ],
                                                            [
                                                                'data' => [
                                                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                                    'method' => 'post',
                                                                    'params' => [], // <- extra level,
                                                                ],
                                                                'class' => 'btn btn-xs btn-danger',
                                                                'style' => 'margin: 2px;width: 35px;',
                                                            ]
                                                        );
                                                },

                                                // 'registrant' => function ($action, $model, $key) {
                                                //     return Yii::$app->user->can('Administrator') && $model->publication !== 0 ?
                                                //         Html::a(
                                                //             '<span class="bx bx-user"></span>',
                                                //             [
                                                //                 Url::toRoute(['/construction/registrant-training', 'id' => Yii::$app->hasher->encode($model->id)])
                                                //             ],
                                                //             [
                                                //                 'class' => 'btn btn-xs btn-success',
                                                //                 'style' => 'margin: 2px;width: 35px;',
                                                //             ]
                                                //         ) : '';
                                                // },
                                                'publish' => function ($url, $model, $key) {
                                                    if ($model->publication == $model::UNPUBLICATED) {
                                                        return Html::a(
                                                            '<span class="bx bx-log-in"></span>',
                                                            [
                                                                Url::toRoute(['publication', 'id' => Yii::$app->hasher->encode($model->id)])
                                                            ],
                                                            [
                                                                'data' => [
                                                                    'confirm' => Yii::t('app', 'Item ini akan dipublikasi, yakin?'),
                                                                    'method' => 'post',
                                                                ],
                                                                'data-toggle' => 'tooltip',
                                                                'title' => Yii::t('app', 'Publish'),
                                                                'class' => 'btn btn-xs btn-info',
                                                                'style' => 'margin: 2px;width: 35px;',
                                                            ]
                                                        );
                                                    } elseif ($model->publication == $model::PUBLICATED) {
                                                        return Html::a(
                                                            '<span class="bx bx-log-out"></span>',
                                                            [
                                                                Url::toRoute(['unpublication', 'id' => Yii::$app->hasher->encode($model->id)])
                                                            ],
                                                            [
                                                                'data' => [
                                                                    'confirm' => Yii::t('app', 'Item ini batal dipublikasi, yakin?'),
                                                                    'method' => 'post',
                                                                ],
                                                                'data-toggle' => 'tooltip',
                                                                'title' => Yii::t('app', 'Unpublish'),
                                                                'class' => 'btn btn-xs btn-danger',
                                                                'style' => 'margin: 2px;width: 35px;',
                                                            ]
                                                        );
                                                    }
                                                },
                                                'closed' => function ($url, $model, $key) {
                                                    if ($model->publication == $model::PUBLICATED) {
                                                        return Html::a(
                                                            '<span class="bx bx-window-close"></span>',
                                                            [
                                                                Url::toRoute(['closed', 'id' => Yii::$app->hasher->encode($model->id)])
                                                            ],
                                                            [
                                                                'data' => [
                                                                    'confirm' => Yii::t('app', 'Item ini akan ditutup, yakin?'),
                                                                    'method' => 'post',
                                                                ],
                                                                'data-toggle' => 'tooltip',
                                                                'title' => Yii::t('app', 'Closed'),
                                                                'class' => 'btn btn-xs btn-warning',
                                                                'style' => 'margin: 2px;width: 35px;',
                                                            ]
                                                        );
                                                    } elseif ($model->publication == $model::CLOSED) {
                                                        return Html::a(
                                                            '<span class="bx bx-window-open"></span>',
                                                            [
                                                                Url::toRoute(['open', 'id' => Yii::$app->hasher->encode($model->id)])
                                                            ],
                                                            [
                                                                'data' => [
                                                                    'confirm' => Yii::t('app', 'Item ini akan dibuka, yakin?'),
                                                                    'method' => 'post',
                                                                ],
                                                                'data-toggle' => 'tooltip',
                                                                'title' => Yii::t('app', 'Open'),
                                                                'class' => 'btn btn-xs btn-info',
                                                                'style' => 'margin: 2px;width: 35px;',
                                                            ]
                                                        );
                                                    }
                                                },
                                            ],
                                        ],
                                    ],
                                ]
                            ); ?>
                        </div>
                    </div>
                    <?php Pjax::end(); ?>


                </div>
            </div>
            <div class="tab-pane fade" id="more" role="tabpanel" aria-labelledby="home-tab">
                <div class="work-training-index">
                    <?php Pjax::begin(['id' => 'work-training-yea-pjax-closed']); ?>

                    <?php // echo $this->render('_search', ['model' => $searchModel]); 
                    ?>

                    <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                        <div class="table-responsive">
                            <?= GridView::widget(
                                [
                                    'dataProvider' => $dataProviderClosed,
                                    'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover'],
                                    'filterModel' => $searchModel,
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],

                                        'training_name',
                                        'person_responsible_name',
                                        'kuota',
                                        [
                                            'attribute' => 'skills',
                                            'value' => function ($model) {
                                                return $model->skillsRelation ? $model->skillsRelation->name : null;
                                            },
                                        ],
                                        'implementation_date',
                                        'implementation_time',

                                        'year_name',
                                        // [
                                        //     'attribute' => 'id_year',
                                        //     'value' => function ($model) {
                                        //         return $model->refYear ? $model->refYear->name : null;
                                        //     },
                                        // ],
                                        [
                                            'attribute' => 'publication',
                                            'value' => function ($model) {
                                                return $model->statusPublication ? $model->statusPublication : null;
                                            },
                                        ],
                                        //'description',
                                        //'subdistrict',
                                        //'ward',
                                        //'created_at:datetime',
                                        //'updated_at:datetime',
                                        //'deleted_at',
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
                                        //'deleted_by',

                                        [
                                            'class' => 'yii\grid\ActionColumn',
                                            'header' => Yii::t('app', 'Action'),
                                            'headerOptions' => ['width' => '100'],
                                            'template' => '{view} {update} {delete} {registrant} {publish} {closed}',
                                            'buttons' => [
                                                'view' => function ($url, $model, $key) {

                                                    return Html::a(
                                                        '<span class="bx bx-show"></span>',
                                                        [
                                                            Url::toRoute(['view', 'id' => Yii::$app->hasher->encode($model->id)])
                                                        ],
                                                        [
                                                            'class' => 'btn btn-xs btn-primary',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                },
                                                'update' => function ($url, $model, $key) {
                                                    if ($model->publication == $model::UNPUBLICATED) {

                                                        return Html::a(
                                                            '<span class="bx bx-edit"></span>',
                                                            [
                                                                Url::toRoute(['update', 'id' => Yii::$app->hasher->encode($model->id)])
                                                            ],
                                                            [
                                                                'class' => 'btn btn-xs btn-warning',
                                                                'style' => 'margin: 2px;width: 35px;',
                                                            ]
                                                        );
                                                    }
                                                },
                                                'delete' => function ($action, $model, $key) {

                                                    return Html::a(
                                                        '<span class="bx bx-trash"></span>',
                                                        [
                                                            Url::toRoute(['delete', 'id' => Yii::$app->hasher->encode($model->id)])
                                                        ],
                                                        [
                                                            'data' => [
                                                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                                'method' => 'post',
                                                                'params' => [], // <- extra level,
                                                            ],
                                                            'class' => 'btn btn-xs btn-danger',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                },

                                                // 'registrant' => function ($action, $model, $key) {

                                                //     if ($model->publication !== 0) {
                                                //         return Html::a(
                                                //             '<span class="bx bx-user"></span>',
                                                //             [
                                                //                 Url::toRoute(['/construction/registrant-training', 'id' => Yii::$app->hasher->encode($model->id)])
                                                //             ],
                                                //             [
                                                //                 'class' => 'btn btn-xs btn-success',
                                                //                 'style' => 'margin: 2px;width: 35px;',
                                                //             ]
                                                //         );
                                                //     }
                                                // },
                                                'publish' => function ($url, $model, $key) {
                                                    if ($model->publication == $model::UNPUBLICATED) {
                                                        return Html::a(
                                                            '<span class="bx bx-log-in"></span>',
                                                            [
                                                                Url::toRoute(['publication', 'id' => Yii::$app->hasher->encode($model->id)])
                                                            ],
                                                            [
                                                                'data' => [
                                                                    'confirm' => Yii::t('app', 'Item ini akan dipublikasi, yakin?'),
                                                                    'method' => 'post',
                                                                ],
                                                                'data-toggle' => 'tooltip',
                                                                'title' => Yii::t('app', 'Publish'),
                                                                'class' => 'btn btn-xs btn-info',
                                                                'style' => 'margin: 2px;width: 35px;',
                                                            ]
                                                        );
                                                    } elseif ($model->publication == $model::PUBLICATED) {
                                                        return Html::a(
                                                            '<span class="bx bx-log-out"></span>',
                                                            [
                                                                Url::toRoute(['unpublication', 'id' => Yii::$app->hasher->encode($model->id)])
                                                            ],
                                                            [
                                                                'data' => [
                                                                    'confirm' => Yii::t('app', 'Item ini batal dipublikasi, yakin?'),
                                                                    'method' => 'post',
                                                                ],
                                                                'data-toggle' => 'tooltip',
                                                                'title' => Yii::t('app', 'Unpublish'),
                                                                'class' => 'btn btn-xs btn-danger',
                                                                'style' => 'margin: 2px;width: 35px;',
                                                            ]
                                                        );
                                                    }
                                                },
                                                'closed' => function ($url, $model, $key) {
                                                    if (Yii::$app->user->can('Administrator')) {
                                                        if ($model->publication == $model::PUBLICATED) {
                                                            return Html::a(
                                                                '<span class="bx bx-window-close"></span>',
                                                                [
                                                                    Url::toRoute(['closed', 'id' => Yii::$app->hasher->encode($model->id)])
                                                                ],
                                                                [
                                                                    'data' => [
                                                                        'confirm' => Yii::t('app', 'Item ini akan ditutup, yakin?'),
                                                                        'method' => 'post',
                                                                    ],
                                                                    'data-toggle' => 'tooltip',
                                                                    'title' => Yii::t('app', 'Closed'),
                                                                    'class' => 'btn btn-xs btn-warning',
                                                                    'style' => 'margin: 2px;width: 35px;',
                                                                ]
                                                            );
                                                        } elseif ($model->publication == $model::CLOSED) {
                                                            return Html::a(
                                                                '<span class="bx bx-window-open"></span>',
                                                                [
                                                                    Url::toRoute(['open', 'id' => Yii::$app->hasher->encode($model->id)])
                                                                ],
                                                                [
                                                                    'data' => [
                                                                        'confirm' => Yii::t('app', 'Item ini akan dibuka, yakin?'),
                                                                        'method' => 'post',
                                                                    ],
                                                                    'data-toggle' => 'tooltip',
                                                                    'title' => Yii::t('app', 'Open'),
                                                                    'class' => 'btn btn-xs btn-info',
                                                                    'style' => 'margin: 2px;width: 35px;',
                                                                ]
                                                            );
                                                        }
                                                    }
                                                    return '';
                                                },
                                            ],
                                        ],
                                    ],
                                ]
                            ); ?>
                        </div>
                    </div>
                    <?php Pjax::end(); ?>

                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if (!Yii::$app->user->can('Team') && Yii::$app->user->can('Tenaga Kerja')) : ?>

        <div class="work-training-index">
            <?php Pjax::begin(['id' => 'work-training-yea-pjax']); ?>

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px; padding: 20px;">
                <div class="table-responsive">
                    <?= GridView::widget(
                        [
                            'dataProvider' => $dataProviderLabor,
                            'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover'],
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                'training_name',
                                'person_responsible_name',
                                'kuota',
                                [
                                    'attribute' => 'skills',
                                    'value' => function ($model) {
                                        return $model->skillsRelation ? $model->skillsRelation->name : null;
                                    },
                                ],
                                'implementation_date',
                                'implementation_time',

                                'year_name',
                                // [
                                //     'attribute' => 'id_year',
                                //     'value' => function ($model) {
                                //         return $model->refYear ? $model->refYear->name : null;
                                //     },
                                // ],
                                [
                                    'attribute' => 'publication',
                                    'value' => function ($model) {
                                        return $model->statusPublication ? $model->statusPublication : null;
                                    },
                                ],
                                //'description',
                                //'subdistrict',
                                //'ward',
                                //'created_at:datetime',
                                //'updated_at:datetime',
                                //'deleted_at',
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
                                //'deleted_by',

                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => Yii::t('app', 'Action'),
                                    'headerOptions' => ['width' => '100'],
                                    'template' => '{view} {register}',
                                    'buttons' => [
                                        'view' => function ($url, $model, $key) {

                                            return Html::a(
                                                '<span class="bx bx-show"></span>',
                                                [
                                                    Url::toRoute(['view', 'id' => Yii::$app->hasher->encode($model->id)])
                                                ],
                                                [
                                                    'class' => 'btn btn-xs btn-primary',
                                                    'style' => 'margin: 2px;width: 35px;',
                                                ]
                                            );
                                        },
                                        'register' => function ($url, $model, $key) {
                                            if ($model->publication == $model::PUBLICATED) {
                                                $cekregister = RegistrantTraining::find()->where(['id_training' => $model->id, 'id_labor' => Yii::$app->session['tenaga_kerja']['id_labor']])->one();
                                                if (!$cekregister) {
                                                return Html::a(
                                                    '<span class="bx bx-user-plus"></span>',
                                                    [
                                                        Url::toRoute(['register', 'id' => Yii::$app->hasher->encode($model->id)])
                                                    ],
                                                    [
                                                        'data-toggle' => 'tooltip',
                                                        'title' => Yii::t('app', 'Register'),
                                                        'class' => 'btn btn-xs btn-info',
                                                        'style' => 'margin: 2px;width: 35px;',
                                                    ]
                                                );
                                            }
                                            }
                                        },
                                    ],
                                ],
                            ],
                        ]
                    ); ?>
                </div>
            </div>
            <?php Pjax::end(); ?>

        </div>
    <?php endif; ?>
</section>
<style>
    .nav-tabs .nav-link {
        border: none;
        color: #000;
        font-weight: bold;
        border-radius: 5px;
    }

    .nav-tabs .nav-link.active {
        color: #fff;
        background-color: var(--bs-primary);
        border-radius: 5px;
    }
</style>
<?= $this->registerJs(
    <<< EOT_JS_CODE

(function($){
    $(document).on('ajaxError', function(event, xhr) {
      if (xhr.status === 401 || xhr.status === 403) {
        window.location.reload();
      }
    });
})(jQuery);

EOT_JS_CODE

);
?>