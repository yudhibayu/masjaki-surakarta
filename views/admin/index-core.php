<?php

/* @var $this yii\web\View */
/* @var $totalk int */

// use Yii;
// use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\DatePicker;
use newerton\fancybox3\FancyBox;
use miloschuman\highcharts\Highcharts;

$this->title = Yii::$app->name . ' - ' . Yii::t('app', 'Dashboard');
$this->params['breadcrumbs'][] = $this->title;

$formatter = Yii::$app->formatter;
$formatter->nullDisplay = '-';
?>
<?php
echo FancyBox::widget([
    'target' => '[data-fancybox="image-fancybox"]',
]);
?>

<div class="row" style="margin-top: -30px;">
    <!-- <php if (Yii::$app->user->can('Administrator')): ?> -->
    <a href="index-core#kecelakaan" class="col-md-4 mb-2">
        <div class="card card-rounded" style="background-color: #2C3E50;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-2"> <?= Yii::t('app', 'Accidents Report') ?></h5>
                    <h3 class="mb-0"><?= $formatter->asDecimal($totalk, 0) ?> Orang</h3>
                </div>
                <i class="fas fa-exclamation-triangle fa-2x icon-color" style="color: #2C3E50;"></i>
            </div>
        </div>
    </a>
    <!-- <php endif; ?> -->
    <!-- <php if (!Yii::$app->user->can('Team') && Yii::$app->user->can('Tenaga Kerja')): ?>
        <a href="index-core#pelatihan" class="col-md-4 mb-2">
            <div class="card card-rounded" style="background-color: #2C3E50;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-2"> <= Yii::t('app', 'Work Training') ?></h5>
                        <h3 class="mb-0"><= $formatter->asDecimal($totaltr, 0) ?> </h3>
                    </div>
                    <i class="bx bx-hard-hat fa-2x icon-color" style="color: #2C3E50;"></i>
                </div>
            </div>
        </a>
    <php endif; ?> -->
    <a href="index-core#keselamatan" class="col-md-4 mb-2">
        <div class="card card-rounded" style="background-color: #F0A500">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-2"><?= Yii::t('app', 'Safeties Report') ?></h5>
                    <h3 class="mb-0"><?= $formatter->asDecimal($totalks, 0) ?> Orang</h3>
                </div>
                <i class="fas fa-shield-alt fa-2x icon-color" style="color: #F0A500"></i>
            </div>
        </div>
    </a>
    <a href="index-core#lowongan" class="col-md-4 mb-2">
        <div class="card card-rounded" style="background-color: #E74C3C;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-2"><?= Yii::t('app', 'Construction Vacancies') ?></h5>
                    <h3 class="mb-0"><?= $formatter->asDecimal($totallow, 0) ?> Posisi</h3>
                </div>
                <i class="fas fa-hard-hat fa-2x icon-color" style="color: #E74C3C;"></i>
            </div>
        </div>
    </a>
</div>
<div class="row">
    <?php if (Yii::$app->user->can('Badan Usaha')): ?>
        <a href="index-core#pbj" class="col-md-6 mb-2">
            <div class="card card-rounded" style="background-color: #855DA6;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-2"><?= Yii::t('app', 'Construction Work') ?></h5>
                        <h3 class="mb-0"><?= $formatter->asDecimal($totalpk, 0) ?> </h3>
                    </div>
                    <i class="fas fa-tools fa-2x icon-color" style="color: #855DA6;"></i>
                </div>
            </div>
        </a>
    <?php endif; ?>
    <?php if (Yii::$app->user->can('Administrator')): ?>
        <a href="index-core#rup" class="col-md-6 mb-2">
            <div class="card card-rounded" style="background-color: #327F6F;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-2"><?= Yii::t('app', 'Construction Plan') ?></h5>
                        <h3 class="mb-0"><?= $formatter->asDecimal($totalrk, 0) ?> </h3>
                    </div>
                    <i class="fas fa-drafting-compass fa-2x icon-color" style="color: #327F6F;"></i>
                </div>
            </div>
        </a>
    <?php endif; ?>
    <?php if (!Yii::$app->user->can('Team') && Yii::$app->user->can('Badan Usaha')): ?>
        <a href="index-core#market" class="col-md-6 mb-2">
            <div class="card card-rounded" style="background-color: #327F6F;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-2"><?= Yii::t('app', 'Construction Market') ?></h5>
                        <h3 class="mb-0"><?= $formatter->asDecimal($totalmk, 0) ?> </h3>
                    </div>
                    <i class="fas fa-drafting-compass fa-2x icon-color" style="color: #327F6F;"></i>
                </div>
            </div>
        </a>
    <?php endif; ?>
</div>
<!-- <div class="row">
    <div class="col-6"> -->
<?php
echo Highcharts::widget([
    'options' => [
        'chart' => [
            'type' => 'spline'
        ],
        'title' => [
            'text' => Yii::t('app', 'Pengunjung Per Tahun') . '<br>Total: ' . $totalDataPerTahun,
            'useHTML' => true
        ],
        'plotOptions' => [
            'spline' => [
                'dataLabels' => [
                    'enabled' => true,
                    'style' => [
                        'color' => 'black',
                        'fontWeight' => 'bold'
                    ]
                ],
                'marker' => [
                    'enabled' => true,
                    'radius' => 8,
                    'symbol' => 'circle',
                    'fillColor' => 'white',
                    'lineWidth' => 2,
                    'lineColor' => 'rgba(54,162,235,1)'
                ]
            ]
        ],
        'series' => [
            [
                'name' => Yii::t('app', 'Jumlah Pengunjung'),
                'data' => $dataPerTahun,
                'color' => 'rgba(54,162,235,1)',
                'marker' => [
                    'enabled' => true,
                    'radius' => 8,
                    'symbol' => 'circle',
                    'fillColor' => 'white',
                    'lineWidth' => 2,
                    'lineColor' => 'rgba(54,162,235,1)'
                ]
            ]
        ],
        'legend' => [
            'enabled' => true,
            'align' => 'right',
            'verticalAlign' => 'middle',
            'layout' => 'vertical',
            'itemStyle' => [
                'fontSize' => '14px',
                'color' => '#333'
            ]
        ],
        'tooltip' => [
            'enabled' => true,
            'useHTML' => true,
            'headerFormat' => '<b>{point.key}</b><br>',
            'pointFormat' => Yii::t('app', '<b>{point.y}</b> Pengunjung')
        ],
        'xAxis' => [
            'categories' => [
                'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember'
            ],
            'title' => ['text' => Yii::t('app', 'Bulan')]
        ],
        'yAxis' => [
            'title' => ['text' => Yii::t('app', 'Jumlah Pengunjung')]
        ]
    ]
]);
?>

<!-- </div>
    <div class="col-6"> -->
<?php
$currentMonthYear = Yii::t('app', date("F Y"));

echo Highcharts::widget([
    'options' => [
        'chart' => [
            'type' => 'spline'
        ],
        'title' => [
            'text' => Yii::t('app', 'Pengunjung Per Bulan') . '<br>Total: ' . $totalDataPerBulan,
            'useHTML' => true
        ],
        'plotOptions' => [
            'spline' => [
                'dataLabels' => [
                    'enabled' => true,
                    'style' => [
                        'color' => 'black',
                        'fontWeight' => 'bold'
                    ]
                ],
            ]
        ],
        'series' => [
            [
                'name' => Yii::t('app', 'Jumlah Pengunjung'),
                'data' => $dataPerBulan,
                'color' => 'rgba(54,162,235,1)',
                'marker' => [
                    'enabled' => true,
                    'radius' => 8,
                    'symbol' => 'circle',
                    'fillColor' => 'white',
                    'lineWidth' => 2,
                    'lineColor' => 'rgba(54,162,235,1)'
                ]
            ]
        ],
        'legend' => [
            'enabled' => true,
            'align' => 'right',
            'verticalAlign' => 'middle',
            'layout' => 'vertical',
            'itemStyle' => [
                'fontSize' => '14px',
                'color' => '#333'
            ]
        ],

        'tooltip' => [
            'enabled' => true,
            'useHTML' => true,
            'headerFormat' => '<b>{point.key} ' . $currentMonthYear . '</b><br>',
            'pointFormat' => Yii::t('app', '<b>{point.y}</b> Pengunjung')
        ],
        'xAxis' => [
            'categories' => range(1, $daysInMonth),
            'title' => ['text' => Yii::t('app', 'Hari')]
        ],
        'yAxis' => [
            'title' => ['text' => Yii::t('app', 'Jumlah Pengunjung')]
        ]
    ]
]);
?>
<!-- </div>
</div> -->

<!-- <php if (Yii::$app->user->can('Administrator')): ?> -->
<section id="kecelakaan">
    <div class="row">
        <div class="col-md-12">
            <div class="cardd">
                <div class="cardd-body">
                    <h3><?= Yii::t('app', 'Accidents Report Data') ?></h3><br>
                    <div class="overflow-hidden" style="border-radius: 12px;">
                        <div class="table-responsive">
                            <?= GridView::widget(
                                [
                                    'dataProvider' => $constructionAccidentDataProvider,
                                    'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover'],
                                    'filterModel' => $constructionAccidentModel,
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
                                        ['attribute' => 'date', 'format' => 'date', 'filter' => DatePicker::widget(['language' => 'id', 'model' => $constructionAccidentModel, 'attribute' => 'date', 'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']]),],
                                        'accident:boolean',
                                        'accident_category',
                                        [
                                            'class' => 'yii\grid\ActionColumn',
                                            'header' => Yii::t('app', 'Action'),
                                            'headerOptions' => ['width' => '150'],
                                            'template' => '{view}',
                                            'buttons' => [
                                                'view' => function ($url, $model, $key) {
                                                    return Html::a(
                                                        '<span class="bx bx-show"></span>',
                                                        [
                                                            Url::toRoute(['/construction/construction-accident/view', 'id' => Yii::$app->hasher->encode($model->id)])
                                                        ],
                                                        [
                                                            'class' => 'btn btn-xs btn-primary',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                },
                                            ],
                                        ],
                                    ],
                                ]
                            ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- <php endif; ?> -->

<!-- <php if (!Yii::$app->user->can('Team') && Yii::$app->user->can('Tenaga Kerja')): ?>
    <section id="pelatihan">
        <div class="row">
            <div class="col-md-12">
                <div class="cardd">
                    <div class="cardd-body">
                        <h3><= Yii::t('app', 'Work Training Data') ?></h3><br>
                        <div class="overflow-hidden" style="border-radius: 12px;">
                            <div class="table-responsive">
                                <= GridView::widget(
                                    [
                                        'dataProvider' => $trDataProvider,
                                        'tableOptions' => ['class' => 'table table-light table-striped table-borderless table-hover'],
                                        'filterModel' => $trModel,
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
                                            //     'attribute' => 'publication',
                                            //     'value' => function ($model) {
                                            //         return $model->statusPublication ? $model->statusPublication : null;
                                            //     },
                                            // ],
                                            [
                                                'class' => 'yii\grid\ActionColumn',
                                                'header' => Yii::t('app', 'Action'),
                                                'headerOptions' => ['width' => '100'],
                                                'template' => '{view}',
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
                                                ],
                                            ],
                                        ],
                                    ]
                                ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>  
<php endif; ?> -->

<section id="keselamatan">
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="cardd">
                <div class="cardd-body">
                    <h3><?= Yii::t('app', 'Safeties Report Data') ?></h3><br>
                    <div class="overflow-hidden" style="border-radius: 12px;">
                        <div class="table-responsive">
                            <?= GridView::widget([
                                'dataProvider' => $constructionSafetyDataProvider,
                                'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover'],
                                'filterModel' => $constructionSafetyModel,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    ['attribute' => 'date', 'format' => 'date', 'filter' => DatePicker::widget(['language' => 'id', 'model' => $constructionSafetyModel, 'attribute' => 'date', 'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']]),],
                                    [
                                        'attribute' => 'report_file',
                                        'format' => 'raw',
                                        'filter' => false,
                                        'value' => function ($model) {
                                            $photoPath = ['/construction/construction-safety/fetch-file', 'id' => Yii::$app->hasher->encode($model->id)];
                                            if ($model->file_extension == 'pdf') {
                                                $pdfUrl = Url::to($photoPath);
                                                return Html::a(
                                                    '<i class="bx bxs-file-pdf"></i> Preview PDF',
                                                    $pdfUrl,
                                                    [
                                                        'class' => 'badge badge-warning',
                                                        'data' => [
                                                            'pjax' => 0,
                                                        ],
                                                    ]
                                                ) . ' ' .
                                                    Html::a(
                                                        '<i class="bx bx-download"></i> Download PDF',
                                                        $pdfUrl,
                                                        [
                                                            'class' => 'badge badge-success',
                                                            'download' => true,
                                                            'data' => [
                                                                'pjax' => 0,
                                                            ],
                                                        ]
                                                    );
                                            } elseif ($model->file_extension == 'png' || 'jpg' || 'jpeg') {
                                                $photoUrl = Url::to($photoPath);
                                                return Html::a(
                                                    Html::img($photoUrl, ['width' => '100px']),
                                                    $photoUrl,
                                                    [
                                                        'class' => 'badge',
                                                        'data' => [
                                                            'pjax' => 0,
                                                            'fancybox' => "image-fancybox",
                                                        ],
                                                    ]
                                                );
                                            }
                                        },
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => Yii::t('app', 'Action'),
                                        'headerOptions' => ['width' => '150'],
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model, $key) {

                                                return Html::a(
                                                    '<span class="bx bx-show"></span>',
                                                    [
                                                        Url::toRoute(['/construction/construction-safety/view', 'id' => Yii::$app->hasher->encode($model->id)])
                                                    ],
                                                    [
                                                        'class' => 'btn btn-xs btn-primary',
                                                        'style' => 'margin: 2px;width: 35px;',
                                                    ]
                                                );
                                            },
                                        ],
                                    ],
                                ],
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="lowongan">
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="cardd">
                <div class="cardd-body">
                    <h3><?= Yii::t('app', 'Construction Vacancies Data') ?></h3><br>
                    <div class="overflow-hidden" style="border-radius: 12px;">
                        <div class="table-responsive">
                            <?= GridView::widget([
                                'dataProvider' => $constructionVacanciesDataProvider,
                                'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover'],
                                'filterModel' => $constructionVacanciesModel,
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
                                    // [
                                    //     'attribute' => 'publication',
                                    //     'value' => function ($model) {
                                    //         return $model->statusPublication ? $model->statusPublication : null;
                                    //     },
                                    // ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => Yii::t('app', 'Action'),
                                        'headerOptions' => ['width' => '150'],
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model, $key) {
                                                return Html::a(
                                                    '<span class="bx bx-show"></span>',
                                                    [
                                                        Url::toRoute(['/construction/construction-vacancies/view', 'id' => Yii::$app->hasher->encode($model->id)])
                                                    ],
                                                    [
                                                        'class' => 'btn btn-xs btn-primary',
                                                        'style' => 'margin: 2px;width: 35px;',
                                                        'data-toggle' => 'tooltip',
                                                        'title' => Yii::t('app', 'View'),
                                                    ]
                                                );
                                            },
                                        ],
                                    ],
                                ],
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (Yii::$app->user->can('Badan Usaha')): ?>
    <section id="pbj">
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="cardd">
                    <div class="cardd-body">
                        <h3><?= Yii::t('app', 'Construction Work Data') ?></h3><br>
                        <div class="overflow-hidden" style="border-radius: 12px;">
                            <div class="table-responsive">
                                <?= GridView::widget([
                                    'dataProvider' => $pbjDataProvider,
                                    'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover', 'style' => 'font-size:11px'],
                                    'pager' => [
                                        'firstPageLabel' => Yii::t('app', 'First'),
                                        'lastPageLabel' => Yii::t('app', 'Last'),
                                    ],
                                    'filterModel' => $pbjModel,
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
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
                                        [
                                            'class' => 'yii\grid\ActionColumn',
                                            'header' => Yii::t('app', 'Action'),
                                            'headerOptions' => ['width' => '100'],
                                            'template' => '{view}',
                                            'buttons' => [
                                                'view' => function ($url, $model, $key) {
                                                    return Html::a(
                                                        '<span class="bx bx-show"></span>',
                                                        [
                                                            Url::toRoute(['/rup/pbj/view', 'id' => Yii::$app->hasher->encode($model->kodelelang)])
                                                        ],
                                                        [
                                                            'class' => 'btn btn-xs btn-primary',
                                                            'style' => 'margin: 2px;width: 35px;',
                                                        ]
                                                    );
                                                },
                                            ],
                                        ],
                                    ],
                                ]); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php if (Yii::$app->user->can('Administrator')): ?>
    <section id="rup">
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="cardd">
                    <div class="cardd-body">
                        <h3><?= Yii::t('app', 'Construction Plan Data') ?></h3><br>
                        <div class="overflow-hidden" style="border-radius: 12px;">
                            <div class="table-responsive">
                                <?= GridView::widget(
                                    [
                                        'dataProvider' => $rupDataProvider,
                                        'tableOptions' => ['class' => 'table table-striped table-bordered table-hover', 'style' => 'font-size:11px'],
                                        'filterModel' => $rupModel,
                                        // 'pager' => [
                                        //     'firstPageLabel' => Yii::t('app', 'First'),
                                        //     'lastPageLabel' => Yii::t('app', 'Last'),
                                        // ],
                                        'columns' => [
                                            ['class' => 'yii\grid\SerialColumn'],

                                            'idrup',
                                            // [
                                            //     'attribute' => 'statusumumkan',
                                            //     'filter' => $statusumumkanfilter,
                                            // ],
                                            'namapaket:ntext',
                                            'tahunanggaran',
                                            [
                                                'attribute' => 'tipe_rup',
                                                'value' => function ($model) {
                                                    $data = [
                                                        0 => Yii::t('rup', 'RUP Penyedia'),
                                                        1 => Yii::t('rup', 'RUP Swakelola'),
                                                    ];
                                                    return $data[$model->tipe_rup];
                                                },
                                                'filter' => [
                                                    0 => Yii::t('rup', 'RUP Penyedia'),
                                                    1 => Yii::t('rup', 'RUP Swakelola'),
                                                ],
                                            ],
                                            [
                                                'attribute' => 'jumlahpagu',
                                                'format' => ['decimal', 2],
                                            ],
                                            // [
                                            //     'attribute' => 'sumberdana',
                                            //     'filter' => $sumberdanafilter,
                                            // ],
                                            // [
                                            //     'attribute' => 'jenispengadaan',
                                            //     'value' => 'jenispengadaan',
                                            //     'filter' => $jenispengadaan,
                                            // ],
                                            [
                                                'class' => 'yii\grid\ActionColumn',
                                                'header' => Yii::t('app', 'Action'),
                                                'headerOptions' => ['width' => '80'],
                                                'template' => '{view}',
                                                'buttons' => [
                                                    'view' => function ($url, $model, $key) {

                                                        return Html::a(
                                                            '<span class="bx bx-show"></span>',
                                                            [
                                                                Url::toRoute(['/rup/default/view', 'id' => Yii::$app->hasher->encode($model->id)])
                                                            ],
                                                            [
                                                                'class' => 'btn btn-xs btn-primary',
                                                                'style' => 'margin: 2px;width: 35px;',
                                                            ]
                                                        );
                                                    },
                                                ],
                                            ],
                                        ],
                                    ],
                                ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php if (!Yii::$app->user->can('Team') && Yii::$app->user->can('Badan Usaha')): ?>
    <section id="market">
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="cardd">
                    <div class="cardd-body">
                        <h3><?= Yii::t('app', 'Construction Market Data') ?></h3><br>
                        <div class="overflow-hidden" style="border-radius: 12px;">
                            <div class="table-responsive">
                                <?= GridView::widget(
                                    [
                                        'dataProvider' => $mkDataProvider,
                                        'tableOptions' => ['class' => 'table table-striped table-bordered table-hover', 'style' => 'font-size:11px'],
                                        'filterModel' => $mkModel,
                                        'columns' => [
                                            ['class' => 'yii\grid\SerialColumn'],

                                            'idrup',
                                            'namapaket:ntext',
                                            'tahunanggaran',
                                            [
                                                'attribute' => 'tipe_rup',
                                                'value' => function ($model) {
                                                    $data = [
                                                        0 => Yii::t('rup', 'RUP Penyedia'),
                                                        1 => Yii::t('rup', 'RUP Swakelola'),
                                                    ];
                                                    return $data[$model->tipe_rup];
                                                },
                                                'filter' => [
                                                    0 => Yii::t('rup', 'RUP Penyedia'),
                                                    1 => Yii::t('rup', 'RUP Swakelola'),
                                                ],
                                            ],
                                            [
                                                'attribute' => 'jumlahpagu',
                                                'format' => ['decimal', 2],
                                            ],
                                            [
                                                'class' => 'yii\grid\ActionColumn',
                                                'header' => Yii::t('app', 'Action'),
                                                'headerOptions' => ['width' => '80'],
                                                'template' => '{view}',
                                                'buttons' => [
                                                    'view' => function ($url, $model, $key) {

                                                        return Html::a(
                                                            '<span class="bx bx-show"></span>',
                                                            [
                                                                Url::toRoute(['/rup/default/view', 'id' => Yii::$app->hasher->encode($model->id)])
                                                            ],
                                                            [
                                                                'class' => 'btn btn-xs btn-primary',
                                                                'style' => 'margin: 2px;width: 35px;',
                                                            ]
                                                        );
                                                    },
                                                ],
                                            ],
                                        ],
                                    ],
                                ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');

    a {
        text-decoration: none;
        color: inherit;
    }

    body {
        font-family: 'Inter', sans-serif;
        margin: 0;
    }

    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #55AD9B;
        /* transition: transform 0.2s, box-shadow 0.2s; */
        height: 110px;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .card-body {
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-title {
        font-size: 0.875em;
        font-weight: 600;
        color: #ffffff;
    }

    .card-body h3 {
        color: #ffffff;
        font-size: 1.25em;
        margin: 0;
    }

    .icon-color {
        color: #55AD9B;
        background-color: #ffffff;
        border-radius: 35%;
        padding: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 50px;
        min-height: 50px;
        font-size: 1.5em;
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 0.75rem;
        }

        .card-title {
            font-size: 0.8em;
        }

        .card-body h3 {
            font-size: 1.1em;
        }

        .icon-color {
            min-width: 40px;
            min-height: 40px;
            padding: 8px;
            font-size: 1.2em;
        }
    }

    .cardd {
        border-radius: 8px;
        /* box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); */
        background-color: var(--bs-bg-card-tbl);
        padding: 15px;
    }

    .cardd .card-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
    }

    .cardd .table-responsive {
        margin-top: 15px;
    }
</style>