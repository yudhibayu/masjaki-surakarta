<?php

use app\modules\construction\models\RegistrantVacancies;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\construction\models\ConstructionVacanciesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Construction Vacancies');
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
        <h3><i class="menu-icon tf-icons bx bx-building-house"></i> <?= Html::encode($this->title) ?> - List Data</h3>
        <p>Manajemen Lowongan Kerja</p>
    </div>
    <?php if (Yii::$app->user->can('Badan Usaha')) : ?>
        <div class="d-sm-flex align-items-center justify-content-between border-bottom p-3">
            <div class="tabs-wrapper">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab"
                            aria-controls="overview" aria-selected="false">
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
            <!-- <php if (Yii::$app->user->can('Badan Usaha')) : ?> -->

            <div class="margin-button">
                <p class="d-flex">
                    <?= Html::a('<i class="menu-icon tf-icons bx bx-plus fw-bold"></i> ' . Yii::t('app', 'Create') . Yii::t('app', 'Construction Vacancies'), ['create'], ['class' => 'btn btn-success fw-bold']) ?>
                </p>
            </div>
            <!-- <php endif; ?> -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="home-tab">
                <div class="construction-vacancies-index">

                    <?php Pjax::begin(['id' => 'construction-vacancies-yea-pjax']); ?>

                    <?php // echo $this->render('_search', ['model' => $searchModel]); 
                    ?>

                    <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                        <div class="table-responsive">
                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover'],
                                'filterModel' => $searchModel,
                                'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '-'],
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    'name',
                                    'description',
                                    [
                                        'attribute' => 'construction_work',
                                        'value' => function ($model) {
                                            return $model->constructionWork ? $model->constructionWork->name : null;
                                        },
                                    ],
                                    [
                                        'attribute' => 'skills',
                                        'value' => function ($model) {
                                            return $model->skillsRelation ? $model->skillsRelation->name : null;
                                        },
                                    ],
                                    [
                                        'attribute' => 'education',
                                        'value' => function ($model) {
                                            return $model->educationRelation ? $model->educationRelation->name : null;
                                        },
                                    ],
                                    [
                                        'attribute' => 'type_of_work',
                                        'value' => function ($model) {
                                            return $model->workType ? $model->workType : null;
                                        },
                                    ],
                                    'year_name',
                                    'nama_business_entity',
                                    [
                                        'attribute' => 'publication',
                                        'value' => function ($model) {
                                            return $model->statusPublication ? $model->statusPublication : null;
                                        },
                                    ],

                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => Yii::t('app', 'Action'),
                                        'headerOptions' => ['width' => '150'],
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
                                                        'data-toggle' => 'tooltip',
                                                        'title' => Yii::t('app', 'View'),
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
                                                            'data-toggle' => 'tooltip',
                                                            'title' => Yii::t('app', 'Update'),
                                                        ]
                                                    );
                                                }
                                            },
                                            'delete' => function ($url, $model, $key) {

                                                return Html::a(
                                                    '<span class="bx bx-trash"></span>',
                                                    [
                                                        Url::toRoute(['delete', 'id' => Yii::$app->hasher->encode($model->id)])
                                                    ],
                                                    [
                                                        'data' => [
                                                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                            'method' => 'post',
                                                        ],
                                                        'class' => 'btn btn-xs btn-danger',
                                                        'style' => 'margin: 2px;width: 35px;',
                                                        'data-toggle' => 'tooltip',
                                                        'title' => Yii::t('app', 'Delete'),

                                                    ]
                                                );
                                            },

                                            // 'registrant' => function ($action, $model, $key) {

                                            //     if ($model->publication !== 0) {
                                            //         return Html::a(
                                            //             '<span class="bx bx-user"></span>',
                                            //             [
                                            //                 Url::toRoute(['/construction/registrant-vacancies', 'id' => Yii::$app->hasher->encode($model->id)])
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
                                                            'class' => 'btn btn-xs btn-info',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                            'data-toggle' => 'tooltip',
                                                            'title' => Yii::t('app', 'Publish'),
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
                                                            'class' => 'btn btn-xs btn-danger',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                            'data-toggle' => 'tooltip',
                                                            'title' => Yii::t('app', 'Unpublish'),
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
                                                            'class' => 'btn btn-xs btn-warning',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                            'data-toggle' => 'tooltip',
                                                            'title' => Yii::t('app', 'Closed'),
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
                                                            'class' => 'btn btn-xs btn-info',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                            'data-toggle' => 'tooltip',
                                                            'title' => Yii::t('app', 'Open'),
                                                        ]
                                                    );
                                                }
                                            },
                                        ],
                                    ],
                                ],
                            ]); ?>
                        </div>
                    </div>
                    <?php Pjax::end(); ?>

                </div>
            </div>
            <div class="tab-pane fade" id="nonpub" role="tabpanel" aria-labelledby="home-tab">
                <div class="construction-vacancies-index">

                    <?php Pjax::begin(['id' => 'construction-vacancies-yea-pjax-unpublictaed']); ?>

                    <?php // echo $this->render('_search', ['model' => $searchModel]); 
                    ?>

                    <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                        <div class="table-responsive">
                            <?= GridView::widget([
                                'dataProvider' => $dataProviderUnpublicated,
                                'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover'],
                                'filterModel' => $searchModel,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    'name',
                                    'description',
                                    [
                                        'attribute' => 'construction_work',
                                        'value' => function ($model) {
                                            return $model->constructionWork ? $model->constructionWork->name : null;
                                        },
                                    ],
                                    [
                                        'attribute' => 'skills',
                                        'value' => function ($model) {
                                            return $model->skillsRelation ? $model->skillsRelation->name : null;
                                        },
                                    ],
                                    [
                                        'attribute' => 'education',
                                        'value' => function ($model) {
                                            return $model->educationRelation ? $model->educationRelation->name : null;
                                        },
                                    ],
                                    [
                                        'attribute' => 'type_of_work',
                                        'value' => function ($model) {
                                            return $model->workType ? $model->workType : null;
                                        },
                                    ],
                                    'year_name',
                                    'nama_business_entity',
                                    [
                                        'attribute' => 'publication',
                                        'value' => function ($model) {
                                            return $model->statusPublication ? $model->statusPublication : null;
                                        },
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => Yii::t('app', 'Action'),
                                        'headerOptions' => ['width' => '150'],
                                        'template' => '{view} {update} {delete} {registrant} {publish} {closed}',
                                        'buttons' => [
                                            'view' => function ($url, $model, $key) {
                                                return Html::a(
                                                    '<span class="bx bx-show"></span>',
                                                    [
                                                        Url::toRoute(['view', 'id' => Yii::$app->hasher->encode($model->id)])
                                                    ],
                                                    [
                                                        'data-toggle' => 'tooltip',
                                                        'title' => Yii::t('app', 'View'),
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
                                                            'data-toggle' => 'tooltip',
                                                            'title' => Yii::t('app', 'Update'),
                                                            'class' => 'btn btn-xs btn-warning',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                }
                                            },
                                            'delete' => function ($url, $model, $key) {
                                                return Html::a(
                                                    '<span class="bx bx-trash"></span>',
                                                    [
                                                        Url::toRoute(['delete', 'id' => Yii::$app->hasher->encode($model->id)])
                                                    ],
                                                    [
                                                        'data' => [
                                                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                            'method' => 'post',
                                                        ],
                                                        'data-toggle' => 'tooltip',
                                                        'title' => Yii::t('app', 'Create'),
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
                                            //                 Url::toRoute(['/construction/registrant-vacancies', 'id' => Yii::$app->hasher->encode($model->id)])
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
                                                if (Yii::$app->user->can('Badan Usaha')) {
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
                            ]); ?>
                        </div>
                    </div>
                    <?php Pjax::end(); ?>

                </div>
            </div>
            <div class="tab-pane fade" id="pub" role="tabpanel" aria-labelledby="home-tab">
                <div class="construction-vacancies-index">


                    <?php Pjax::begin(['id' => 'construction-vacancies-yea-pjax-publicated']); ?>

                    <?php // echo $this->render('_search', ['model' => $searchModel]); 
                    ?>

                    <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                        <div class="table-responsive">
                            <?= GridView::widget([
                                'dataProvider' => $dataProviderPublicated,
                                'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover'],
                                'filterModel' => $searchModel,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    'name',
                                    'description',
                                    [
                                        'attribute' => 'construction_work',
                                        'value' => function ($model) {
                                            return $model->constructionWork ? $model->constructionWork->name : null;
                                        },
                                    ],
                                    [
                                        'attribute' => 'skills',
                                        'value' => function ($model) {
                                            return $model->skillsRelation ? $model->skillsRelation->name : null;
                                        },
                                    ],
                                    [
                                        'attribute' => 'education',
                                        'value' => function ($model) {
                                            return $model->educationRelation ? $model->educationRelation->name : null;
                                        },
                                    ],
                                    [
                                        'attribute' => 'type_of_work',
                                        'value' => function ($model) {
                                            return $model->workType ? $model->workType : null;
                                        },
                                    ],
                                    'year_name',
                                    'nama_business_entity',
                                    [
                                        'attribute' => 'publication',
                                        'value' => function ($model) {
                                            return $model->statusPublication ? $model->statusPublication : null;
                                        },
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => Yii::t('app', 'Action'),
                                        'headerOptions' => ['width' => '150'],
                                        'template' => '{view} {update} {delete} {registrant} {publish} {closed}',
                                        'buttons' => [
                                            'view' => function ($url, $model, $key) {
                                                return Yii::$app->user->can('Tenaga Kerja') || Yii::$app->user->can('Badan Usaha') ?
                                                    Html::a(
                                                        '<span class="bx bx-show"></span>',
                                                        [
                                                            Url::toRoute(['view', 'id' => Yii::$app->hasher->encode($model->id)])
                                                        ],
                                                        [
                                                            'data-toggle' => 'tooltip',
                                                            'title' => Yii::t('app', 'View'),
                                                            'class' => 'btn btn-xs btn-primary',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    ) : '';
                                            },
                                            'update' => function ($url, $model, $key) {

                                                if ($model->publication == $model::UNPUBLICATED) {
                                                    return Yii::$app->user->can('Badan Usaha') ?
                                                        Html::a(
                                                            '<span class="bx bx-edit"></span>',
                                                            [
                                                                Url::toRoute(['update', 'id' => Yii::$app->hasher->encode($model->id)])
                                                            ],
                                                            [
                                                                'data-toggle' => 'tooltip',
                                                                'title' => Yii::t('app', 'Update'),
                                                                'class' => 'btn btn-xs btn-warning',
                                                                'style' => 'margin: 2px;width: 35px;',
                                                            ]
                                                        ) : '';
                                                }
                                            },
                                            'delete' => function ($url, $model, $key) {
                                                return Html::a(
                                                    '<span class="bx bx-trash"></span>',
                                                    [
                                                        Url::toRoute(['delete', 'id' => Yii::$app->hasher->encode($model->id)])
                                                    ],
                                                    [
                                                        'data' => [
                                                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                            'method' => 'post',
                                                        ],
                                                        'data-toggle' => 'tooltip',
                                                        'title' => Yii::t('app', 'Delete'),
                                                        'class' => 'btn btn-xs btn-danger',
                                                        'style' => 'margin: 2px;width: 35px;',
                                                    ]
                                                );
                                            },
                                            // 'registrant' => function ($url, $model, $key) {
                                            //     return Yii::$app->user->can('Administrator') && $model->publication !== 0 ?
                                            //         Html::a(
                                            //             '<span class="bx bx-user"></span>',
                                            //             [
                                            //                 Url::toRoute(['/construction/registrant-vacancies', 'id' => Yii::$app->hasher->encode($model->id)])
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
                            ]); ?>
                        </div>
                    </div>
                    <?php Pjax::end(); ?>

                </div>
            </div>
            <div class="tab-pane fade" id="more" role="tabpanel" aria-labelledby="home-tab">
                <div class="construction-vacancies-index">

                    <br>

                    <?php Pjax::begin(['id' => 'construction-vacancies-yea-pjax-closed']); ?>

                    <?php // echo $this->render('_search', ['model' => $searchModel]); 
                    ?>

                    <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                        <div class="table-responsive">
                            <?= GridView::widget([
                                'dataProvider' => $dataProviderClosed,
                                'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover'],
                                'filterModel' => $searchModel,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    'name',
                                    'description',
                                    [
                                        'attribute' => 'construction_work',
                                        'value' => function ($model) {
                                            return $model->constructionWork ? $model->constructionWork->name : null;
                                        },
                                    ],
                                    [
                                        'attribute' => 'skills',
                                        'value' => function ($model) {
                                            return $model->skillsRelation ? $model->skillsRelation->name : null;
                                        },
                                    ],
                                    [
                                        'attribute' => 'education',
                                        'value' => function ($model) {
                                            return $model->educationRelation ? $model->educationRelation->name : null;
                                        },
                                    ],
                                    [
                                        'attribute' => 'type_of_work',
                                        'value' => function ($model) {
                                            return $model->workType ? $model->workType : null;
                                        },
                                    ],
                                    'year_name',
                                    'nama_business_entity',
                                    [
                                        'attribute' => 'publication',
                                        'value' => function ($model) {
                                            return $model->statusPublication ? $model->statusPublication : null;
                                        },
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => Yii::t('app', 'Action'),
                                        'headerOptions' => ['width' => '150'],
                                        'template' => '{view} {update} {delete} {registrant} {publish} {closed}',
                                        'buttons' => [
                                            'view' => function ($url, $model, $key) {
                                                return Html::a(
                                                    '<span class="bx bx-show"></span>',
                                                    [
                                                        Url::toRoute(['view', 'id' => Yii::$app->hasher->encode($model->id)])
                                                    ],
                                                    [
                                                        'data-toggle' => 'tooltip',
                                                        'title' => Yii::t('app', 'View'),
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
                                                            'data-toggle' => 'tooltip',
                                                            'title' => Yii::t('app', 'Update'),
                                                            'class' => 'btn btn-xs btn-warning',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                }
                                            },
                                            'delete' => function ($url, $model, $key) {
                                                return Html::a(
                                                    '<span class="bx bx-trash"></span>',
                                                    [
                                                        Url::toRoute(['delete', 'id' => Yii::$app->hasher->encode($model->id)])
                                                    ],
                                                    [
                                                        'data' => [
                                                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                            'method' => 'post',
                                                        ],
                                                        'data-toggle' => 'tooltip',
                                                        'title' => Yii::t('app', 'Delete'),
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
                                            //                 Url::toRoute(['/construction/registrant-vacancies', 'id' => Yii::$app->hasher->encode($model->id)])
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
                                                if (Yii::$app->user->can('Badan Usaha')) {
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
                            ]); ?>
                        </div>
                    </div>
                    <?php Pjax::end(); ?>

                </div>
            </div>
        </div>

    <?php endif; ?>
    <?php if (!Yii::$app->user->can('Team') && Yii::$app->user->can('Tenaga Kerja')) : ?>
        <div class="construction-vacancies-index">

            <?php Pjax::begin(['id' => 'construction-vacancies-yea-pjax']); ?>

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px; padding: 20px;">
                <div class="table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProviderLabor,
                        'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover'],
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'name',
                            'description',
                            [
                                'attribute' => 'construction_work',
                                'value' => function ($model) {
                                    return $model->constructionWork ? $model->constructionWork->name : null;
                                },
                            ],
                            [
                                'attribute' => 'skills',
                                'value' => function ($model) {
                                    return $model->skillsRelation ? $model->skillsRelation->name : null;
                                },
                            ],
                            [
                                'attribute' => 'education',
                                'value' => function ($model) {
                                    return $model->educationRelation ? $model->educationRelation->name : null;
                                },
                            ],
                            [
                                'attribute' => 'type_of_work',
                                'value' => function ($model) {
                                    return $model->workType ? $model->workType : null;
                                },
                            ],
                            'year_name',
                            'nama_business_entity',
                            [
                                'attribute' => 'publication',
                                'value' => function ($model) {
                                    return $model->statusPublication ? $model->statusPublication : null;
                                },
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => Yii::t('app', 'Action'),
                                'headerOptions' => ['width' => '150'],
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
                                                'data-toggle' => 'tooltip',
                                                'title' => Yii::t('app', 'View'),
                                            ]
                                        );
                                    },
                                    'register' => function ($url, $model, $key) {
                                        if ($model->publication == $model::PUBLICATED) {
                                            $cekregister = RegistrantVacancies::find()->where(['id_vacancies' => $model->id, 'id_labor' => Yii::$app->session['tenaga_kerja']['id_labor']])->one();
                                            if(!$cekregister){
                                                return
                                                    Html::a(
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
                    ]); ?>
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
    <<<JS
(function($){
    $(document).on('ajaxError', function(event, xhr) {
      if (xhr.status === 401 || xhr.status === 403) {
        window.location.reload();
      }
    });
})(jQuery);
JS
);

?>