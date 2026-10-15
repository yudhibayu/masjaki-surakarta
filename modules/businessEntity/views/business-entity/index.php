<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\businessEntity\models\BusinessEntitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Business Entity');
// $this->params['breadcrumbs'][] = $this->title;
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
        <h3><i class="menu-icon tf-icons bx bx-buildings"></i> <?= Html::encode($this->title) ?> - List Data</h3>
        <p>Manajemen Badan Usaha Konstruksi</p>
    </div>

    <?php if (Yii::$app->user->can('Team')): ?>

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
                        <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#nonverif" role="tab"
                            aria-selected="false">
                            <i class='bx bx-x-circle'></i> Belum Terverifikasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#verif" role="tab"
                            aria-selected="false">
                            <i class='bx bx-check-circle'></i> Sudah Terverifikasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link border-0" id="more-tab" data-bs-toggle="tab" href="#more" role="tab"
                            aria-selected="false">
                            <i class='bx bx-block'></i> Blacklist
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <div class="tab-content tab-content-basic">
        <?php if (Yii::$app->user->can('Team')): ?>
            <div class="margin-button">
                <p class="d-flex">
                    <?= Html::a('<i class="menu-icon tf-icons bx bx-plus fw-bold"></i> ' . Yii::t('app', 'Create') . Yii::t('app', 'Business Entity'), ['create'], ['class' => 'btn btn-success fw-bold']) ?>
                </p>
            </div>
        <?php endif; ?>

        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="home-tab">
            <div class="business-entity-index">

                <?php Pjax::begin(['id' => 'business-entity-yea-pjax']); ?>

                <div class="card shadow-sm overflow-hidden" style="border-radius: 12px; padding: 20px;">
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover', 'style' => 'font-size:11px'],
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                'npwp',
                                'company_name',
                                [
                                    'attribute' => 'phone_number',
                                    'value' => function ($model) {
                                        return '+62 ' . ltrim($model->phone_number, '0');
                                    },
                                ],
                                'province',
                                'regency',
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
                                                    // 'style' => 'margin: 2px;font-size: 12px;',
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
                                                            'params' => [], // <- extra level
                                                        ],
                                                        'class' => 'btn btn-xs btn-danger',
                                                        'style' => 'margin: 2px;width: 35px;',
                                                    ]
                                                );
                                            }
                                        },
                                        'verifikasi' => function ($url, $model, $key) {
                                            if (Yii::$app->user->can('Team')) {
                                                if ($model->account_verification == $model::VERIFICATION) {
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
                                                            'style' => 'margin: 2px; width: 35px;',
                                                        ]
                                                    );
                                                } elseif ($model->account_verification == $model::BLACKLIST) {
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
                                                            'style' => 'margin: 2px; width: 35px;',
                                                        ]
                                                    );
                                                }
                                            }
                                        },

                                        'blacklist' => function ($action, $model, $key) {

                                            if (Yii::$app->user->can('Team')) {
                                                if ($model->account_verification !== $model::BLACKLIST) {
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
                        ]); ?>
                    </div>
                </div>

                <?php Pjax::end(); ?>
            </div>
        </div>
        <div class="tab-pane fade" id="nonverif" role="tabpanel" aria-labelledby="nonverif">
            <div class="business-entity-index">

                <?php Pjax::begin(['id' => 'business-entity-yea-pjax-unverified']); ?>

                <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'dataProvider' => $dataProviderUnverified,
                            'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover', 'style' => 'font-size:11px'],
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                'npwp',
                                'company_name',
                                [
                                    'attribute' => 'phone_number',
                                    'value' => function ($model) {
                                        return '+62 ' . ltrim($model->phone_number, '0');
                                    },
                                ],
                                'province',
                                'regency',
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
                                                    // 'style' => 'margin: 2px;font-size: 12px;',
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
                                            return Html::a(
                                                '<span class="bx bx-trash"></span>',
                                                [
                                                    Url::toRoute(['delete', 'id' => Yii::$app->hasher->encode($model->id)])
                                                ],
                                                [
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
                                        'verifikasi' => function ($url, $model, $key) {
                                            if ($model->account_verification == $model::VERIFICATION) {
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
                                                        'style' => 'margin: 2px; width: 35px;',
                                                    ]
                                                );
                                            } elseif ($model->account_verification == $model::BLACKLIST) {
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
                                                        'style' => 'margin: 2px; width: 35px;',
                                                    ]
                                                );
                                            }
                                        },

                                        'blacklist' => function ($action, $model, $key) {

                                            if ($model->account_verification !== $model::BLACKLIST) {
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
                        ]); ?>
                    </div>
                </div>

                <?php Pjax::end(); ?>
            </div>
        </div>
        <div class="tab-pane fade" id="verif" role="tabpanel" aria-labelledby="verif">
            <div class="business-entity-index">
                <?php Pjax::begin(['id' => 'business-entity-yea-pjax-verified']); ?>

                <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'dataProvider' => $dataProviderVerified,
                            'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover', 'style' => 'font-size:11px', 'id' => 'font-size:11px'],
                            'filterModel' => $searchModel,
                            'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => '-'],
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                'npwp',
                                'company_name',
                                [
                                    'attribute' => 'phone_number',
                                    'value' => function ($model) {
                                        return '+62 ' . ltrim($model->phone_number, '0');
                                    },
                                ],
                                'province',
                                'regency',
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
                                                    // 'style' => 'margin: 2px;font-size: 12px;',
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
                                            return Html::a(
                                                '<span class="bx bx-trash"></span>',
                                                [
                                                    Url::toRoute(['delete', 'id' => Yii::$app->hasher->encode($model->id)])
                                                ],
                                                [
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
                                        'verifikasi' => function ($url, $model, $key) {
                                            if ($model->account_verification == $model::VERIFICATION) {
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
                                                        'style' => 'margin: 2px; width: 35px;',
                                                    ]
                                                );
                                            } elseif ($model->account_verification == $model::BLACKLIST) {
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
                                                        'style' => 'margin: 2px; width: 35px;',
                                                    ]
                                                );
                                            }
                                        },

                                        'blacklist' => function ($action, $model, $key) {

                                            if ($model->account_verification !== $model::BLACKLIST) {
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
                        ]); ?>
                    </div>
                </div>

                <?php Pjax::end(); ?>
            </div>
        </div>
        <div class="tab-pane fade" id="more" role="tabpanel" aria-labelledby="nonverif">
            <div class="business-entity-index">

                <?php Pjax::begin(['id' => 'business-entity-yea-pjax-blacklist']); ?>

                <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'dataProvider' => $dataProviderBlacklist,
                            'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover', 'style' => 'font-size:11px'],
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                'npwp',
                                'company_name',
                                [
                                    'attribute' => 'phone_number',
                                    'value' => function ($model) {
                                        return '+62 ' . ltrim($model->phone_number, '0');
                                    },
                                ],
                                'province',
                                'regency',
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
                                                    // 'style' => 'margin: 2px;font-size: 12px;',
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
                                            return Html::a(
                                                '<span class="bx bx-trash"></span>',
                                                [
                                                    Url::toRoute(['delete', 'id' => Yii::$app->hasher->encode($model->id)])
                                                ],
                                                [
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
                                        'verifikasi' => function ($url, $model, $key) {
                                            if ($model->account_verification == $model::VERIFICATION) {
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
                                                        'style' => 'margin: 2px; width: 35px;',
                                                    ]
                                                );
                                            } elseif ($model->account_verification == $model::BLACKLIST) {
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
                                                        'style' => 'margin: 2px; width: 35px;',
                                                    ]
                                                );
                                            }
                                        },

                                        'blacklist' => function ($action, $model, $key) {

                                            if ($model->account_verification !== $model::BLACKLIST) {
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
                        ]); ?>
                    </div>
                </div>

                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>

    <div class="card-body">
    </div>

</section>
<style>
    .navbar-brand {
        display: flex;
        align-items: center;
        text-decoration: none;
    }

    .brand-logo img {
        margin-right: 10px;
    }

    .brand-text {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .brand-name {
        font-weight: bold;
        margin: 0;
        font-size: 16px;
    }

    .brand-location {
        margin: 0;
        font-size: 12px;
    }

    .custom-nav-tabs {
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .custom-nav-tabs .nav-item {
        margin: 0 5px;
        border-radius: 8px;
    }

    .custom-nav-tabs .nav-link {
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: var(--bs-primary);
        padding: 10px 20px;
        border-radius: 5px;
        background-color: var(--bs-tertiary);
        transition: background-color 0.3s, color 0.3s, transform 0.3s;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .custom-nav-tabs .nav-link i {
        font-size: 1.4em;
        margin-right: 8px;
    }

    .custom-nav-tabs .nav-link:hover {
        background-color: var(--bs-primary);
        color: var(--bs-hover-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .custom-nav-tabs .nav-link.active {
        background-color: var(--bs-active-bg);
        color: var(--bs-active-color);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

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

    .nav-tabs .nav-link:hover {
        background-color: var(--bs-primary);
        color: #fff;
        border-radius: 5px;
    }

    .tab-pane {
        border-radius: 5px;
        background-color: #f9f9f9;
        padding: 15px;
    }

    .btn-group {
        margin-bottom: 20px;
    }

    /* =============================================================================== */
    .nav-link {
        border: 1px solid transparent;
        border-radius: .375rem;
        padding: .5rem 1rem;
        font-weight: 500;
        transition: background-color .3s, color .3s;
    }

    .tab-pane {
        transition: opacity .3s ease;
    }

    .tab-pane.fade.show.active {
        opacity: 1;
    }

    .tab-pane.fade {
        opacity: 0;
    }
</style>
<?=
$this->registerJs(
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