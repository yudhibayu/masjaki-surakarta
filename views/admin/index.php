<?php

/* @var $this yii\web\View */

use app\modules\monitoring\models\MonitoringTask;
use app\modules\technicalspec\models\MonitoringSpec;
use kartik\grid\GridView;
use miloschuman\highcharts\Highcharts;
use newerton\fancybox3\FancyBox;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = Yii::$app->name;
$this->params['breadcrumbs'][] =  $this->title;

$formatter = Yii::$app->formatter;
$formatter->nullDisplay = '-';
?>
<?php
echo FancyBox::widget([
    'target' => '[data-fancybox="monitoring"]',
]);
?>
<!-- start: page -->
<div class="row">
    <div class="col-xs-12" style="text-align: center;">
        <div class="card">
            <div class="card-header">
                <h1>Data Paket Monitoring</h1>
                <h5>Tahun <?= isset(Yii::$app->session['tahun']) ? Yii::$app->session['tahun'] : date('Y'); ?> </h5>
            </div>
        </div>
    </div>
</div>
<div class="row" style="text-align: center;">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Total Paket <br>Tepat Waktu</div>
                <h4 class="card-text"><?= $formatter->asDecimal($totaltw, 0) ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Total Paket <br>Terlambat</div>
                <h4 class="card-text"><?= $formatter->asDecimal($totaltr, 0) ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Total Paket <br>Selesai</div>
                <h4 class="card-text"><?= $formatter->asDecimal($totals, 0) ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Total Paket <br>Dalam Proses</div>
                <h4 class="card-text"><?= $formatter->asDecimal($totaldp, 0) ?></h4>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Data Paket Tepat Waktu</div><br>
                <?php // echo $this->render('_search', ['model' => $searchModel]); 
                ?>
                <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                    <?= GridView::widget([
                        'id' => 'pakettw',
                        'dataProvider' => $dataProviderPaket,
                        'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                        'responsive' => true,
                        'responsiveWrap' => false,
                        'filterModel' => $searchModelPaket,
                        // 'showFooter' => true,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            'name:ntext',
                            'idrup',
                            'unit_name',
                            'fiscal_year',
                            [
                                'attribute' => 'latest_plan_financial_budget',
                                'filter' => false,
                                'label' => 'Target Keuangan (%)',
                                'value' => function ($model) {
                                    return '' . round((($model->latest_plan_financial_budget / $model->final_price) * 100), 3) . ' %';
                                }
                            ],
                            [
                                'attribute' => 'latest_plan_physical_percent',
                                'value' => function ($model) {
                                    return '' . round($model->latest_plan_physical_percent, 3) . ' %';
                                }
                            ],
                            [
                                'attribute' => 'latest_monitored_financial_budget',
                                'filter' => false,
                                'label' => 'Realisasi Keuangan (%)',
                                'value' => function ($model) {
                                    return '' . round((($model->latest_monitored_financial_budget / $model->final_price) * 100), 3) . ' %';
                                }
                            ],
                            [
                                'attribute' => 'latest_monitored_physical_percent',
                                'value' => function ($model) {
                                    return '' . round($model->latest_monitored_physical_percent, 3) . ' %';
                                }
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Data Paket Terlambat</div><br>
                <?php // echo $this->render('_search', ['model' => $searchModel]); 
                ?>
                <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                    <?= GridView::widget([
                        'id' => 'pakettr',
                        'dataProvider' => $dataProviderPaket2,
                        'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                        'responsive' => true,
                        'responsiveWrap' => false,
                        'filterModel' => $searchModelPaket2,
                        // 'showFooter' => true,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            'name:ntext',
                            'idrup',
                            'unit_name',
                            'fiscal_year',
                            [
                                'attribute' => 'latest_plan_financial_budget',
                                'filter' => false,
                                'label' => 'Target Keuangan (%)',
                                'value' => function ($model) {
                                    return '' . round((($model->latest_plan_financial_budget / $model->final_price) * 100), 3) . ' %';
                                }
                            ],
                            [
                                'attribute' => 'latest_plan_physical_percent',
                                'value' => function ($model) {
                                    return '' . round($model->latest_plan_physical_percent, 3) . ' %';
                                }
                            ],
                            [
                                'attribute' => 'latest_monitored_financial_budget',
                                'filter' => false,
                                'label' => 'Realisasi Keuangan (%)',
                                'value' => function ($model) {
                                    return '' . round((($model->latest_monitored_financial_budget / $model->final_price) * 100), 3) . ' %';
                                }
                            ],
                            [
                                'attribute' => 'latest_monitored_physical_percent',
                                'value' => function ($model) {
                                    return '' . round($model->latest_monitored_physical_percent, 3) . ' %';
                                }
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Data Paket Selesai</div><br>
                <?php // echo $this->render('_search', ['model' => $searchModel]); 
                ?>
                <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                    <?= GridView::widget([
                        'id' => 'pakettr',
                        'dataProvider' => $dataProviderPaket3,
                        'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                        'responsive' => true,
                        'responsiveWrap' => false,
                        'filterModel' => $searchModelPaket3,
                        // 'showFooter' => true,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            'name:ntext',
                            'idrup',
                            'unit_name',
                            'fiscal_year',
                            [
                                'attribute' => 'latest_plan_financial_budget',
                                'filter' => false,
                                'label' => 'Target Keuangan (%)',
                                'value' => function ($model) {
                                    return '' . round((($model->latest_plan_financial_budget / $model->final_price) * 100), 3) . ' %';
                                }
                            ],
                            [
                                'attribute' => 'latest_plan_physical_percent',
                                'value' => function ($model) {
                                    return '' . round($model->latest_plan_physical_percent, 3) . ' %';
                                }
                            ],
                            [
                                'attribute' => 'latest_monitored_financial_budget',
                                'filter' => false,
                                'label' => 'Realisasi Keuangan (%)',
                                'value' => function ($model) {
                                    return '' . round((($model->latest_monitored_financial_budget / $model->final_price) * 100), 3) . ' %';
                                }
                            ],
                            [
                                'attribute' => 'latest_monitored_physical_percent',
                                'value' => function ($model) {
                                    return '' . round($model->latest_monitored_physical_percent, 3) . ' %';
                                }
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Data Paket Dalam Proses</div><br>
                <?php // echo $this->render('_search', ['model' => $searchModel]); 
                ?>
                <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                    <?= GridView::widget([
                        'id' => 'pakettr',
                        'dataProvider' => $dataProviderPaket4,
                        'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                        'responsive' => true,
                        'responsiveWrap' => false,
                        'filterModel' => $searchModelPaket4,
                        // 'showFooter' => true,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            'name:ntext',
                            'idrup',
                            'unit_name',
                            'fiscal_year',
                            [
                                'attribute' => 'latest_plan_financial_budget',
                                'filter' => false,
                                'label' => 'Target Keuangan (%)',
                                'value' => function ($model) {
                                    return '' . round((($model->latest_plan_financial_budget / $model->final_price) * 100), 3) . ' %';
                                }
                            ],
                            [
                                'attribute' => 'latest_plan_physical_percent',
                                'value' => function ($model) {
                                    return '' . round($model->latest_plan_physical_percent, 3) . ' %';
                                }
                            ],
                            [
                                'attribute' => 'latest_monitored_financial_budget',
                                'filter' => false,
                                'label' => 'Realisasi Keuangan (%)',
                                'value' => function ($model) {
                                    return '' . round((($model->latest_monitored_financial_budget / $model->final_price) * 100), 3) . ' %';
                                }
                            ],
                            [
                                'attribute' => 'latest_monitored_physical_percent',
                                'value' => function ($model) {
                                    return '' . round($model->latest_monitored_physical_percent, 3) . ' %';
                                }
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Video Monitoring Spesifikasi</div><br>
                <?php if ($countvideo == 0 || $countvideo == 1) {
                    $totalvid = 1;
                } else {
                    $totalvid = 2;
                }
                ?>
                <div class="owl-carousel owl-theme" data-plugin-carousel data-plugin-options='{ "dots": false, "autoplay": false, "autoplayTimeout": 3000, "loop": true, "margin": 10, "nav": true, "responsive": {"0":{"items":1 }, "600":{"items":<?= $totalvid ?> }, "1000":{"items":<?= $totalvid ?> } }  }'>
                    <?php if ($video) {
                        foreach ($video as $vid) {
                            $montask_id = MonitoringSpec::find()->where(['id' => $vid['monitoring_spec_id']])->one()->monitoring_task_id; ?>
                            <div class="item">
                                <div class="img-thumbnail" style="width:80vh; height:50vh;">
                                    <video style=" width:100%; height:100%; object-fit: cover; background:black;" controls>
                                        <source src="<?= Url::to(['/technicalspec/monitoring-spec-files/fetch-file', 'id' => Yii::$app->hasher->encode($vid['id'])], true) ?>" type="video/mp4">
                                    </video>
                                    <center><?= MonitoringTask::find()->where(['id' => $montask_id])->one()->name; ?> - <?= MonitoringSpec::find()->where(['id' => $vid['monitoring_spec_id']])->one()->name; ?></center>
                                </div>
                            </div>
                    <?php }
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Foto Monitoring Spesifikasi</div><br>
                <?php if ($countfoto == 0 || $countfoto == 1) {
                    $totalfoto = 1;
                } else {
                    $totalfoto = 2;
                }
                ?>
                <div class="owl-carousel owl-theme" data-plugin-carousel data-plugin-options='{ "dots": false, "autoplay": false, "autoplayTimeout": 3000, "loop": true, "margin": 10, "nav": true, "responsive": {"0":{"items":1 }, "600":{"items":<?= $totalvid ?> }, "1000":{"items":<?= $totalvid ?> } }  }'>
                    <?php if ($foto) {
                        foreach ($foto as $ft) {
                            $montask_id = MonitoringSpec::find()->where(['id' => $ft['monitoring_spec_id']])->one()->monitoring_task_id; ?>
                            <div class="item">
                                <div class="img-thumbnail" style="width:80vh; height:50vh;">
                                    <?=
                                    Html::a(
                                        Html::img(Url::to(['/technicalspec/monitoring-spec-files/fetch-file', 'id' => Yii::$app->hasher->encode($ft['id'])], true), ['class' => 'img-thumbnail', 'alt' => 'image']),
                                        Url::to(['/technicalspec/monitoring-spec-files/fetch-file', 'id' => Yii::$app->hasher->encode($ft['id'])], true),
                                        [
                                            'data' => [
                                                'pjax' => 0,
                                                'fancybox' => "monitoring",
                                            ],
                                        ]
                                    );
                                    ?>
                                    <center><?= MonitoringTask::find()->where(['id' => $montask_id])->one()->name; ?> - <?= MonitoringSpec::find()->where(['id' => $ft['monitoring_spec_id']])->one()->name; ?></center>
                                </div>
                            </div>
                    <?php }
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- end: page -->