<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\construction\models\LaborSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Labor');
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
        <h3><i class="menu-icon tf-icons bx bx-id-card"></i> <?= Html::encode($this->title) ?> - List Data</h3>
        <p>Manajemen Tenaga Kerja Konstruksi</p>
    </div>
    <?php if (Yii::$app->user->can('Team')): ?>

        <div class="d-sm-flex align-items-center justify-content-between border-bottom p-3">
            <div class="tabs-wrapper">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="all-tab" data-bs-toggle="tab" href="#overview" role="tab"
                            aria-controls="overview" aria-selected="true">
                            <i class='bx bx-buildings'></i> Semua
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="unverified-tab" data-bs-toggle="tab" href="#unverified" role="tab"
                            aria-controls="nonverif" aria-selected="false">
                            <i class='bx bx-x-circle'></i> Belum Terverifikasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="unemployed-tab" data-bs-toggle="tab" href="#verified" role="tab"
                            aria-controls="unemployed" aria-selected="false">
                            <i class='bx bx-check-circle'></i> Sudah Terverifikasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link border-0" id="blacklist-tab" data-bs-toggle="tab" href="#blacklist" role="tab"
                            aria-controls="blacklist" aria-selected="false">
                            <i class='bx bx-block'></i> Blacklist
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                    <a class="nav-link" id="menganggur-tab" data-bs-toggle="tab" href="#menganggur" role="tab"
                        aria-controls="menganggur" aria-selected="false">
                        <i class='bx bx-x-circle'></i> Menganggur
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="melamar-tab" data-bs-toggle="tab" href="#melamar" role="tab"
                        aria-controls="melamar" aria-selected="false">
                        <i class='bx bx-check-circle'></i> Sedang Melamar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link border-0" id="bekerja-tab" data-bs-toggle="tab" href="#bekerja" role="tab"
                        aria-controls="bekerja" aria-selected="false">
                        <i class='bx bx-block'></i> Sedang Bekerja
                    </a>
                </li> -->
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <div class="tab-content tab-content-basic">
        <?php if (Yii::$app->user->can('Team')): ?>
            <div class="margin-button">
                <p class="d-flex">
                    <?= Html::a('<i class="menu-icon tf-icons bx bx-plus fw-bold"></i> ' .  Yii::t('app', 'Create') . Yii::t('app', 'Labor'), ['create'], ['class' => 'btn btn-success fw-bold']) ?>
                </p>
            </div>
        <?php endif; ?>
        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="home-tab">
            <div class="labor-index">

                <?php Pjax::begin(['id' => 'labor-yea-pjax']); ?>

                <?php // echo $this->render('_search', ['model' => $searchModel]); 
                ?>

                <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                    <div class="table-responsive">
                        <?= GridView::widget(
                            [
                                'dataProvider' => $dataProvider,
                                'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover', 'style' => 'font-size:11px'],
                                'filterModel' => $searchModel,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],

                                    'name',
                                    'nik_passport_number',
                                    'npwp',
                                    [
                                        'attribute' => 'phone_number',
                                        'value' => function ($model) {
                                            return '+62 ' . ltrim($model->phone_number, '0');
                                        },
                                    ],
                                    'email:email',
                                    [
                                        'attribute' => 'citizenship',
                                        'value' => function ($model) {
                                            return $model->wniWna;
                                        },
                                    ],
                                    [
                                        'attribute' => 'account_verification',
                                        'value' => 'statusAccountVerification',
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => Yii::t('app', 'Action'),
                                        'headerOptions' => ['width' => '100'],
                                        'template' => '{view} {update} {delete} {verifikasi} {blacklist}',
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

                                                if ($model->account_verification !== $model::BLACKLIST) {
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

                                                if (Yii::$app->user->can('Team')) {
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
                                                }
                                            },
                                            'verifikasi' => function ($url, $model, $key) {
                                                if (Yii::$app->user->can('Team')) {
                                                    if ($model->account_verification == 1) {
                                                        return Html::a(
                                                            '<span class="bx bx-x"></span>',
                                                            [
                                                                Url::toRoute(['unverifikasi', 'id' => Yii::$app->hasher->encode($model->id)])
                                                            ],
                                                            [
                                                                'class' => 'btn btn-xs btn-danger',
                                                                'data' => [
                                                                    'confirm' => Yii::t('app', 'Are you sure you want to unverify this item?'),
                                                                    'method' => 'post',
                                                                    'params' => [], // <- extra parameters if needed
                                                                ],
                                                                'data-toggle' => 'tooltip',
                                                                'title' => Yii::t('app', 'Unverify'),
                                                                'style' => 'margin: 2px;width: 35px;',
                                                            ]
                                                        );
                                                    } elseif ($model->account_verification == 2) {
                                                        return false;
                                                    } else {
                                                        return Html::a(
                                                            '<span class="bx bx-check"></span>',
                                                            [
                                                                Url::toRoute(['verifikasi', 'id' => Yii::$app->hasher->encode($model->id)])
                                                            ],
                                                            [
                                                                'class' => 'btn btn-xs btn-success',
                                                                'data' => [
                                                                    'pjax' => 0,
                                                                ],
                                                                'data-toggle' => 'tooltip',
                                                                'title' => Yii::t('app', 'Verify'),
                                                                'style' => 'margin: 2px;width: 35px;',
                                                            ]
                                                        );
                                                    }
                                                }
                                            },


                                            'blacklist' => function ($action, $model, $key) {
                                                if (Yii::$app->user->can('Team')) {

                                                    if ($model->account_verification !== 2) {
                                                        return Html::a(
                                                            '<span class="bx bx-block"></span>',
                                                            [
                                                                Url::toRoute(['blacklist', 'id' => Yii::$app->hasher->encode($model->id)])
                                                            ],
                                                            [
                                                                'data' => [
                                                                    'confirm' => Yii::t('app', 'Are you sure you want to block this item?'),
                                                                    'method' => 'post',
                                                                    'params' => [], // <- extra level
                                                                ],
                                                                'data-toggle' => 'tooltip',
                                                                'title' => Yii::t('app', 'Blacklist'),
                                                                'class' => 'btn btn-xs btn-danger',
                                                                'style' => 'margin: 2px;width: 35px;',
                                                            ]
                                                        );
                                                    } else {
                                                        return Html::a(
                                                            '<span class="bx bx-undo"></span>',
                                                            [
                                                                Url::toRoute(['unblacklist', 'id' => Yii::$app->hasher->encode($model->id)])
                                                            ],
                                                            [
                                                                'data' => [
                                                                    'confirm' => Yii::t('app', 'Are you sure you want to unblock this item?'),
                                                                    'method' => 'post',
                                                                    'params' => [], // <- extra level
                                                                ],
                                                                'data-toggle' => 'tooltip',
                                                                'title' => Yii::t('app', 'Unblacklist'),
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
        </div>
        <div class="tab-pane fade" id="unverified" role="tabpanel" aria-labelledby="home-tab">
            <div class="labor-index">

                <?php Pjax::begin(['id' => 'labor-yea-pjax-unverified']); ?>

                <?php // echo $this->render('_search', ['model' => $searchModel]); 
                ?>

                <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                    <div class="table-responsive">
                        <?= GridView::widget(
                            [
                                'dataProvider' => $dataProviderUnverified,
                                'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover', 'style' => 'font-size:11px'],
                                'filterModel' => $searchModel,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],

                                    'name',
                                    'nik_passport_number',
                                    'npwp',
                                    [
                                        'attribute' => 'phone_number',
                                        'value' => function ($model) {
                                            return '+62 ' . ltrim($model->phone_number, '0');
                                        },
                                    ],
                                    'email:email',
                                    [
                                        'attribute' => 'citizenship',
                                        'value' => function ($model) {
                                            return $model->wniWna;
                                        },
                                    ],
                                    [
                                        'attribute' => 'account_verification',
                                        'value' => 'statusAccountVerification',
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => Yii::t('app', 'Action'),
                                        'headerOptions' => ['width' => '100'],
                                        'template' => '{view} {update} {delete} {verifikasi} {blacklist}',
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

                                                if ($model->account_verification == 0) {
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
                                            'verifikasi' => function ($url, $model, $key) {
                                                if ($model->account_verification == 1) {
                                                    return Html::a(
                                                        '<span class="bx bx-x"></span>',
                                                        [
                                                            Url::toRoute(['unverifikasi', 'id' => Yii::$app->hasher->encode($model->id)])
                                                        ],
                                                        [
                                                            'class' => 'btn btn-xs btn-danger',
                                                            'data' => [
                                                                'confirm' => Yii::t('app', 'Are you sure you want to unverify this item?'),
                                                                'method' => 'post',
                                                                'params' => [], // <- extra parameters if needed
                                                            ],
                                                            'data-toggle' => 'tooltip',
                                                            'title' => Yii::t('app', 'Unverify'),
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                } elseif ($model->account_verification == 2) {
                                                    return false;
                                                } else {
                                                    return Html::a(
                                                        '<span class="bx bx-check"></span>',
                                                        [
                                                            Url::toRoute(['verifikasi', 'id' => Yii::$app->hasher->encode($model->id)])
                                                        ],
                                                        [
                                                            'class' => 'btn btn-xs btn-success',
                                                            'data' => [
                                                                'pjax' => 0,
                                                            ],
                                                            'data-toggle' => 'tooltip',
                                                            'title' => Yii::t('app', 'Verify'),
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                }
                                            },


                                            'blacklist' => function ($action, $model, $key) {

                                                if ($model->account_verification !== 2) {
                                                    return Html::a(
                                                        '<span class="bx bx-block"></span>',
                                                        [
                                                            Url::toRoute(['blacklist', 'id' => Yii::$app->hasher->encode($model->id)])
                                                        ],
                                                        [
                                                            'data' => [
                                                                'confirm' => Yii::t('app', 'Are you sure you want to block this item?'),
                                                                'method' => 'post',
                                                                'params' => [], // <- extra level
                                                            ],
                                                            'data-toggle' => 'tooltip',
                                                            'title' => Yii::t('app', 'Blacklist'),
                                                            'class' => 'btn btn-xs btn-danger',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                } else {
                                                    return Html::a(
                                                        '<span class="bx bx-undo"></span>',
                                                        [
                                                            Url::toRoute(['unblacklist', 'id' => Yii::$app->hasher->encode($model->id)])
                                                        ],
                                                        [
                                                            'data' => [
                                                                'confirm' => Yii::t('app', 'Are you sure you want to unblock this item?'),
                                                                'method' => 'post',
                                                                'params' => [], // <- extra level
                                                            ],
                                                            'data-toggle' => 'tooltip',
                                                            'title' => Yii::t('app', 'Unblacklist'),
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
        <div class="tab-pane fade" id="verified" role="tabpanel" aria-labelledby="home-tab">
            <div class="labor-index">

                <?php Pjax::begin(['id' => 'labor-yea-pjax-verified']); ?>

                <?php // echo $this->render('_search', ['model' => $searchModel]); 
                ?>

                <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                    <div class="table-responsive">
                        <?= GridView::widget(
                            [
                                'dataProvider' => $dataProviderVerified,
                                'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover', 'style' => 'font-size:11px'],
                                'filterModel' => $searchModel,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],

                                    'name',
                                    'nik_passport_number',
                                    'npwp',
                                    [
                                        'attribute' => 'phone_number',
                                        'value' => function ($model) {
                                            return '+62 ' . ltrim($model->phone_number, '0');
                                        },
                                    ],
                                    'email:email',
                                    [
                                        'attribute' => 'citizenship',
                                        'value' => function ($model) {
                                            return $model->wniWna;
                                        },
                                    ],
                                    [
                                        'attribute' => 'account_verification',
                                        'value' => 'statusAccountVerification',
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => Yii::t('app', 'Action'),
                                        'headerOptions' => ['width' => '100'],
                                        'template' => '{view} {update} {delete} {verifikasi} {blacklist}',
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

                                                if ($model->account_verification == 0) {
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
                                            'verifikasi' => function ($url, $model, $key) {
                                                if ($model->account_verification == 1) {
                                                    return Html::a(
                                                        '<span class="bx bx-x"></span>',
                                                        [
                                                            Url::toRoute(['unverifikasi', 'id' => Yii::$app->hasher->encode($model->id)])
                                                        ],
                                                        [
                                                            'class' => 'btn btn-xs btn-danger',
                                                            'data' => [
                                                                'confirm' => Yii::t('app', 'Are you sure you want to unverify this item?'),
                                                                'method' => 'post',
                                                                'params' => [], // <- extra parameters if needed
                                                            ],
                                                            'data-toggle' => 'tooltip',
                                                            'title' => Yii::t('app', 'Unverify'),
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                } elseif ($model->account_verification == 2) {
                                                    return false;
                                                } else {
                                                    return Html::a(
                                                        '<span class="bx bx-check"></span>',
                                                        [
                                                            Url::toRoute(['verifikasi', 'id' => Yii::$app->hasher->encode($model->id)])
                                                        ],
                                                        [
                                                            'class' => 'btn btn-xs btn-success',
                                                            'data' => [
                                                                'pjax' => 0,
                                                            ],
                                                            'data-toggle' => 'tooltip',
                                                            'title' => Yii::t('app', 'Verify'),
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                }
                                            },


                                            'blacklist' => function ($action, $model, $key) {

                                                if ($model->account_verification !== 2) {
                                                    return Html::a(
                                                        '<span class="bx bx-block"></span>',
                                                        [
                                                            Url::toRoute(['blacklist', 'id' => Yii::$app->hasher->encode($model->id)])
                                                        ],
                                                        [
                                                            'data' => [
                                                                'confirm' => Yii::t('app', 'Are you sure you want to block this item?'),
                                                                'method' => 'post',
                                                                'params' => [], // <- extra level
                                                            ],
                                                            'data-toggle' => 'tooltip',
                                                            'title' => Yii::t('app', 'Blacklist'),
                                                            'class' => 'btn btn-xs btn-danger',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                } else {
                                                    return Html::a(
                                                        '<span class="bx bx-undo"></span>',
                                                        [
                                                            Url::toRoute(['unblacklist', 'id' => Yii::$app->hasher->encode($model->id)])
                                                        ],
                                                        [
                                                            'data' => [
                                                                'confirm' => Yii::t('app', 'Are you sure you want to unblock this item?'),
                                                                'method' => 'post',
                                                                'params' => [], // <- extra level
                                                            ],
                                                            'data-toggle' => 'tooltip',
                                                            'title' => Yii::t('app', 'Unblacklist'),
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
        <div class="tab-pane fade" id="blacklist" role="tabpanel" aria-labelledby="home-tab">
            <div class="labor-index">

                <?php Pjax::begin(['id' => 'labor-yea-pjax-blacklist']); ?>

                <?php // echo $this->render('_search', ['model' => $searchModel]); 
                ?>

                <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                    <div class="table-responsive">
                        <?= GridView::widget(
                            [
                                'dataProvider' => $dataProviderBlacklist,
                                'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover', 'style' => 'font-size:11px'],
                                'filterModel' => $searchModel,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],

                                    'name',
                                    'nik_passport_number',
                                    'npwp',
                                    [
                                        'attribute' => 'phone_number',
                                        'value' => function ($model) {
                                            return '+62 ' . ltrim($model->phone_number, '0');
                                        },
                                    ],
                                    'email:email',
                                    [
                                        'attribute' => 'citizenship',
                                        'value' => function ($model) {
                                            return $model->wniWna;
                                        },
                                    ],
                                    [
                                        'attribute' => 'account_verification',
                                        'value' => 'statusAccountVerification',
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => Yii::t('app', 'Action'),
                                        'headerOptions' => ['width' => '100'],
                                        'template' => '{view} {update} {delete} {verifikasi} {blacklist}',
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

                                                if ($model->account_verification == 0) {
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
                                            'verifikasi' => function ($url, $model, $key) {
                                                if ($model->account_verification == 1) {
                                                    return Html::a(
                                                        '<span class="bx bx-x"></span>',
                                                        [
                                                            Url::toRoute(['unverifikasi', 'id' => Yii::$app->hasher->encode($model->id)])
                                                        ],
                                                        [
                                                            'class' => 'btn btn-xs btn-danger',
                                                            'data' => [
                                                                'confirm' => Yii::t('app', 'Are you sure you want to unverify this item?'),
                                                                'method' => 'post',
                                                                'params' => [], // <- extra parameters if needed
                                                            ],
                                                            'data-toggle' => 'tooltip',
                                                            'title' => Yii::t('app', 'Verify'),
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                } elseif ($model->account_verification == 2) {
                                                    return false;
                                                } else {
                                                    return Html::a(
                                                        '<span class="bx bx-check"></span>',
                                                        [
                                                            Url::toRoute(['verifikasi', 'id' => Yii::$app->hasher->encode($model->id)])
                                                        ],
                                                        [
                                                            'class' => 'btn btn-xs btn-success',
                                                            'data' => [
                                                                'pjax' => 0,
                                                            ],
                                                            'data-toggle' => 'tooltip',
                                                            'title' => Yii::t('app', 'Verify'),
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                }
                                            },


                                            'blacklist' => function ($action, $model, $key) {

                                                if ($model->account_verification !== 2) {
                                                    return Html::a(
                                                        '<span class="bx bx-block"></span>',
                                                        [
                                                            Url::toRoute(['blacklist', 'id' => Yii::$app->hasher->encode($model->id)])
                                                        ],
                                                        [
                                                            'data' => [
                                                                'confirm' => Yii::t('app', 'Are you sure you want to block this item?'),
                                                                'method' => 'post',
                                                                'params' => [], // <- extra level
                                                            ],
                                                            'data-toggle' => 'tooltip',
                                                            'title' => Yii::t('app', 'Blacklist'),
                                                            'class' => 'btn btn-xs btn-danger',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                } else {
                                                    return Html::a(
                                                        '<span class="bx bx-undo"></span>',
                                                        [
                                                            Url::toRoute(['unblacklist', 'id' => Yii::$app->hasher->encode($model->id)])
                                                        ],
                                                        [
                                                            'data' => [
                                                                'confirm' => Yii::t('app', 'Are you sure you want to unblock this item?'),
                                                                'method' => 'post',
                                                                'params' => [], // <- extra level
                                                            ],
                                                            'data-toggle' => 'tooltip',
                                                            'title' => Yii::t('app', 'Unblacklist'),
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
    </div>
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