<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use app\modules\construction\models\PbjBusinessEntity;
use kartik\bs5dropdown\ButtonDropdown;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\rup\models\PbjSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('rup', 'Construction Work');
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->registerJs(
    <<< EOT_JS_CODE
    
    $(document).ready(function() {
        $('.setnavpbj').on('click', function() {
        let tab = $(this).data('tab');
            $.ajax({
                url: "pbj/set-tab-pbj",
                type: "POST",
                data: "tab=" + tab,
                success: function(data) {
                    console.log(data);
                }
            });
    });
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
        <h3><i class="menu-icon tf-icons bx bx-building"></i> <?= Html::encode($this->title) ?> - List Data</h3>
        <p>Manajemen Pekerjaan Konstruksi</p>
    </div>
    <div class="card-body">
        <div class="pbj-index">
            <div class="row">
                <div class="col-sm-12" style="padding-bottom: 20px;">
                    <div class="tabs-wrapper">
                        <ul class="nav nav-tabs nav-justified custom-nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link setnavpbj <?= $view_pbj['nav'][0] ?>" id="tender-tab"
                                    data-bs-toggle="tab" data-bs-target="#tender" type="button" role="tab"
                                    aria-controls="tender" aria-selected="true" data-tab="0">
                                    <i class="fa fa-gavel"></i> Tender
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link setnavpbj <?= $view_pbj['nav'][1] ?>" id="nontender-tab"
                                    data-bs-toggle="tab" data-bs-target="#nontender" type="button" role="tab"
                                    aria-controls="nontender" aria-selected="false" data-tab="1">
                                    <i class="fa fa-list-alt"></i> NonTender
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="tab-content" id="myTabContents">
                <!-- <php if (!Yii::$app->user->can('Team') && Yii::$app->user->can('Badan Usaha')): ?>
                <p>
                    <= Html::a('<i class="menu-icon tf-icons bx bx-plus"></i> ' . Yii::t('rup', 'Create Data'), ['create-rup'], ['class' => 'btn btn-success fw-bold']) ?>
                </p>
                <php endif; ?> -->
                <div class="tab-pane <?= $view_pbj['tab'][0] ?>" id="tender" role="tabpanel"
                    aria-labelledby="tender-tab">

                    <?php if (Yii::$app->user->can('Administrator')): ?>
                        <p>
                            <?= Html::a('<i class="menu-icon tf-icons bx bx-plus"></i> ' . Yii::t('rup', 'Add Construction Jobs'), ['create'], ['class' => 'btn btn-success fw-bold']) ?>
                            <?= Html::a('<i class="menu-icon tf-icons bx bx-sync"></i> Sinkronisasi Pekerjaan Konstruksi (Tender)', ['/data-sync/default/tender-pbj'], ['class' => 'btn btn-warning fw-bold']) ?>
                        </p>
                    <?php endif; ?>

                    <br>

                    <?php Pjax::begin(['id' => 'tender-pjax']); ?>

                    <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                        <div class="table-responsive">
                            <?= GridView::widget([
                                'dataProvider' => $dataProviderTender,
                                'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover', 'style' => 'font-size:11px'],
                                'pager' => [
                                    'firstPageLabel' => Yii::t('app', 'First'),
                                    'lastPageLabel' => Yii::t('app', 'Last'),
                                ],
                                'filterModel' => $searchModel,
                                'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '-'],
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    [
                                        'format' => 'raw',
                                        'header' => 'Menu '.Yii::t('app', 'Action'),
                                        'headerOptions' => ['width' => '100'],
                                        'value' => function ($model) {
                                            return ButtonDropdown::widget([
                                                'label' => Yii::t('app', 'Action'),
                                                'dropdown' => [
                                                    'items' => [
                                                        ['label' => Yii::t('app', 'View'), 'url' => Url::toRoute(['view', 'id' => Yii::$app->hasher->encode($model->kodelelang)])],
                                                        ['label' => Yii::t('app', 'Edit'), 'url' => Url::toRoute(['update', 'id' => Yii::$app->hasher->encode($model->kodelelang)])],
                                                        ['label' => Yii::t('app', 'Delete'), 'url' => Url::toRoute(['delete', 'id' => Yii::$app->hasher->encode($model->kodelelang)])],
                                                        ['label' => Yii::t('app', 'Create') . '' . Yii::t('app', 'Construction Accidents'), 'url' => Url::toRoute(['../construction/construction-accident/create', 'id' => Yii::$app->hasher->encode($model->kodelelang)])],
                                                        ['label' => Yii::t('app', 'Create') . '' . Yii::t('app', 'Construction Safeties'), 'url' => Url::toRoute(['../construction/construction-safety/create', 'id' => Yii::$app->hasher->encode($model->kodelelang)])],
                                                        ['label' => Yii::t('app', 'Add Business Entity'), 'url' => Url::toRoute(['../construction/pbj-business-entity/create', 'id' => Yii::$app->hasher->encode($model->kodelelang)])],
                                                    ],
                                                ]
                                            ]);
                                        },
                                    ],
                                    [
                                        'visible' => false,
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => Yii::t('app', 'Action'),
                                        'headerOptions' => ['width' => '100'],
                                        'template' => '{view} {update} {delete} {accident} {safety} {business}',
                                        'buttons' => [
                                            'view' => function ($url, $model, $key) {
                                                return Html::a(
                                                    '<span class="bx bx-show"></span>',
                                                    [
                                                        Url::toRoute(['view', 'id' => Yii::$app->hasher->encode($model->kodelelang)])
                                                    ],
                                                    [
                                                        'class' => 'btn btn-xs btn-primary',
                                                        'style' => 'margin: 2px;width: 35px;',
                                                    ]
                                                );
                                            },
                                            'update' => function ($url, $model, $key) {
                                                if (Yii::$app->user->can('Administrator')) {
                                                    return Html::a(
                                                        '<span class="bx bx-edit"></span>',
                                                        [
                                                            Url::toRoute(['update', 'id' => Yii::$app->hasher->encode($model->kodelelang)])
                                                        ],
                                                        [
                                                            'class' => 'btn btn-xs btn-warning',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                }
                                            },
                                            'delete' => function ($action, $model, $key) {
                                                if (Yii::$app->user->can('Administrator')) {
                                                    return Html::a(
                                                        '<span class="bx bx-trash"></span>',
                                                        [
                                                            Url::toRoute(['delete', 'id' => Yii::$app->hasher->encode($model->kodelelang)])
                                                        ],
                                                        [
                                                            'data' => [
                                                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                                'method' => 'post',
                                                                'params' => [],
                                                            ],
                                                            'class' => 'btn btn-xs btn-danger',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                }
                                            },
                                            'accident' => function ($url, $model, $key) {
                                                if (Yii::$app->user->can('Badan Usaha')) {
                                                    return Html::a(
                                                        '<span class="bx bxs-error"></span>',
                                                        [
                                                            Url::toRoute(['../construction/construction-accident/create', 'id' => Yii::$app->hasher->encode($model->kodelelang)])
                                                        ],
                                                        [
                                                            'data-toggle' => 'tooltip',
                                                            'title' => Yii::t('app', 'Create') . '' . Yii::t('app', 'Construction Accidents'),
                                                            'class' => 'btn btn-xs btn-info',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                }
                                            },
                                            'safety' => function ($url, $model, $key) {
                                                if (Yii::$app->user->can('Badan Usaha')) {
                                                    return Html::a(
                                                        '<span class="bx bx-check-shield"></span>',
                                                        [
                                                            Url::toRoute(['../construction/construction-safety/create', 'id' => Yii::$app->hasher->encode($model->kodelelang)])
                                                        ],
                                                        [
                                                            'data-toggle' => 'tooltip',
                                                            'title' => Yii::t('app', 'Create') . '' . Yii::t('app', 'Construction Safeties'),
                                                            'class' => 'btn btn-xs btn-success',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                }
                                            },
                                            'business' => function ($url, $model, $key) {
                                                if (Yii::$app->user->can('Administrator') && PbjBusinessEntity::findOne(['kode_pbj' => $model->kodelelang]) == null) {
                                                    return Html::a(
                                                        '<span class="bx bx-user"></span>',
                                                        [
                                                            Url::toRoute(['../construction/pbj-business-entity/create', 'id' => Yii::$app->hasher->encode($model->kodelelang)])
                                                        ],
                                                        [
                                                            'data-toggle' => 'tooltip',
                                                            'title' => Yii::t('app', 'Add Business Entity'),
                                                            'class' => 'btn btn-xs btn-warning',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                }
                                            },
                                        ],
                                    ],
                                    'kodelelang',
                                    'name:ntext',
                                    [
                                        'attribute' => 'pbj_type',
                                        'value' => function ($model) {
                                            return $model->pbjType ? $model->pbjType : null;
                                        },
                                        'filter' => false,
                                        // 'filter' => [
                                        //     0 => Yii::t('rup', 'Tender'),
                                        //     1 => Yii::t('rup', 'Non Tender'),
                                        // ],
                                    ],
                                    'rup_code',
                                    [
                                        'attribute' => 'budget',
                                        'format' => ['decimal', 2],
                                    ],
                                    'fiscal_year',
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
                                    'namasatker',
                                    'step',
                                    [
                                        'attribute' => 'source_of_funds',
                                        'value' => function ($model) {
                                            return $model->sourceOfFunds ? $model->sourceOfFunds->name : null;
                                        },
                                    ],
                                    'vendor_name:ntext',
                                    'jenispengadaan',

                                ],
                            ]); ?>
                        </div>
                    </div>
                    <?php Pjax::end(); ?>
                </div>

                <div class="tab-pane <?= $view_pbj['tab'][1] ?>" id="nontender" role="tabpanel"
                    aria-labelledby="nontender-tab">

                    <?php if (Yii::$app->user->can('Administrator')): ?>
                        <p>
                            <?= Html::a('<i class="menu-icon tf-icons bx bx-plus"></i> ' . Yii::t('rup', 'Add Construction Jobs'), ['create'], ['class' => 'btn btn-success fw-bold']) ?>
                            <?= Html::a('<i class="menu-icon tf-icons bx bx-sync"></i> Sinkronisasi Pekerjaan Konstruksi (Non Tender)', ['/data-sync/default/non-tender-pbj'], ['class' => 'btn btn-warning fw-bold']) ?>
                        </p>
                    <?php endif; ?>
                    <br>
                    <?php Pjax::begin(['id' => 'nontender-pjax']); ?>

                    <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                        <div class="table-responsive">
                            <?= GridView::widget([
                                'dataProvider' => $dataProviderNonTender,
                                'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover', 'style' => 'font-size:11px'],
                                'pager' => [
                                    'firstPageLabel' => Yii::t('app', 'First'),
                                    'lastPageLabel' => Yii::t('app', 'Last'),
                                ],
                                'filterModel' => $searchModel,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => Yii::t('app', 'Action'),
                                        'headerOptions' => ['width' => '100'],
                                        'template' => '{view} {update} {delete} {accident} {safety} {business}',
                                        'buttons' => [
                                            'view' => function ($url, $model, $key) {
                                                return Html::a(
                                                    '<span class="bx bx-show"></span>',
                                                    [
                                                        Url::toRoute(['view', 'id' => Yii::$app->hasher->encode($model->kodelelang)])
                                                    ],
                                                    [
                                                        'class' => 'btn btn-xs btn-primary',
                                                        'style' => 'margin: 2px;width: 35px;',
                                                    ]
                                                );
                                            },
                                            'update' => function ($url, $model, $key) {
                                                if (Yii::$app->user->can('Administrator')) {
                                                    return Html::a(
                                                        '<span class="bx bx-edit"></span>',
                                                        [
                                                            Url::toRoute(['update', 'id' => Yii::$app->hasher->encode($model->kodelelang)])
                                                        ],
                                                        [
                                                            'class' => 'btn btn-xs btn-warning',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                }
                                            },
                                            'delete' => function ($action, $model, $key) {
                                                if (Yii::$app->user->can('Administrator')) {
                                                    return Html::a(
                                                        '<span class="bx bx-trash"></span>',
                                                        [
                                                            Url::toRoute(['delete', 'id' => Yii::$app->hasher->encode($model->kodelelang)])
                                                        ],
                                                        [
                                                            'data' => [
                                                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                                'method' => 'post',
                                                                'params' => [],
                                                            ],
                                                            'class' => 'btn btn-xs btn-danger',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                }
                                            },
                                            'accident' => function ($url, $model, $key) {
                                                if (Yii::$app->user->can('Badan Usaha')) {
                                                    return Html::a(
                                                        '<span class="bx bxs-error"></span>',
                                                        [
                                                            Url::toRoute(['../construction/construction-accident/create', 'id' => Yii::$app->hasher->encode($model->kodelelang)])
                                                        ],
                                                        [
                                                            'data-toggle' => 'tooltip',
                                                            'title' => Yii::t('app', 'Create') . '' . Yii::t('app', 'Construction Accidents'),
                                                            'class' => 'btn btn-xs btn-info',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                }
                                            },
                                            'safety' => function ($url, $model, $key) {
                                                if (Yii::$app->user->can('Badan Usaha')) {
                                                    return Html::a(
                                                        '<span class="bx bx-check-shield"></span>',
                                                        [
                                                            Url::toRoute(['../construction/construction-safety/create', 'id' => Yii::$app->hasher->encode($model->kodelelang)])
                                                        ],
                                                        [
                                                            'data-toggle' => 'tooltip',
                                                            'title' => Yii::t('app', 'Create') . '' . Yii::t('app', 'Construction Safeties'),
                                                            'class' => 'btn btn-xs btn-success',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                }
                                            },
                                            'business' => function ($url, $model, $key) {
                                                if (Yii::$app->user->can('Administrator') && PbjBusinessEntity::findOne(['kode_pbj' => $model->kodelelang]) == null) {
                                                    return Html::a(
                                                        '<span class="bx bx-user"></span>',
                                                        [
                                                            Url::toRoute(['../construction/pbj-business-entity/create', 'id' => Yii::$app->hasher->encode($model->kodelelang)])
                                                        ],
                                                        [
                                                            'data-toggle' => 'tooltip',
                                                            'title' => Yii::t('app', 'Add Business Entity'),
                                                            'class' => 'btn btn-xs btn-warning',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                }
                                            },
                                        ],
                                    ],
                                    'kodelelang',
                                    'name:ntext',
                                    [
                                        'attribute' => 'pbj_type',
                                        'value' => function ($model) {
                                            return $model->pbjType ? $model->pbjType : null;
                                        },
                                        'filter' => false,
                                        // 'filter' => [
                                        //     0 => Yii::t('rup', 'Tender'),
                                        //     1 => Yii::t('rup', 'Non Tender'),
                                        // ],
                                    ],
                                    'rup_code',
                                    [
                                        'attribute' => 'budget',
                                        'format' => ['decimal', 2],
                                    ],
                                    'fiscal_year',
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
                                    'namasatker',
                                    'step',
                                    [
                                        'attribute' => 'source_of_funds',
                                        'value' => function ($model) {
                                            return $model->sourceOfFunds ? $model->sourceOfFunds->name : null;
                                        },
                                    ],
                                    'vendor_name:ntext',
                                    'jenispengadaan',

                                ],
                            ]); ?>
                        </div>
                    </div>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->registerJs(
    <<< EOT_JS_CODE

(function($){
    $(document).on('ajaxError', function(event, xhr) {
      if (xhr.status === 401 || xhr.status === 403) {
        window.location.reload();
      }
    });

    $(document).on('pjax:end', function() {
        $('[data-bs-toggle="tab"]').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });
})(jQuery);

EOT_JS_CODE

);
?>