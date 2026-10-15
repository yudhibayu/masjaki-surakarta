<?php

/* @var $this yii\web\View */

use app\modules\monitoring\models\MonitoringTask;
use kartik\grid\GridView;
use miloschuman\highcharts\Highcharts;
use yii\bootstrap5\Html;
use yii\widgets\Pjax;

$this->title = Yii::$app->name;
$this->params['breadcrumbs'][] =  $this->title;

$formatter = Yii::$app->formatter;
$formatter->nullDisplay = '-';
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
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Total Paket</div>
                <h4 class="card-text"><?= $formatter->asDecimal($statdatapaket['jumlahpaket'], 0) ?></h4>
            </div>
        </div>
    </div>
</div>
<div class="row" style="text-align: center;">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Total Target Keuangan</div>
                <h4 class="card-text"><?= $formatter->asCurrency($statdatapaket['keuangan']['target']) ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Total Realisasi Keuangan</div>
                <h4 class="card-text"><?= $formatter->asCurrency($statdatapaket['keuangan']['realisasi']) ?></h4>
            </div>
        </div>
    </div>
</div>
<div class="row" style="text-align: center;">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Rerata Target Fisik</div>
                <h4 class="card-text"><?= $formatter->asDecimal($statdatapaket['fisik']['target'], 2) ?>%</h4>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Rerata Realisasi Fisik</div>
                <h4 class="card-text"><?= $formatter->asDecimal($statdatapaket['fisik']['realisasi'], 2) ?>%</h4>
            </div>
        </div>
    </div>
</div>

<div class="row" style="text-align: center;">
    <?php foreach ($statdatapaket['statuses'] as $key => $value) : ?>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Status <?= MonitoringTask::implementationStatusNames()[$key] ?></div>
                    <h4 class="card-text"><?= $formatter->asDecimal($statdatapaket['statuses'][$key], 0) ?></h4>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<div class="row">
    <div class="col-xs-12" style="text-align: center;">
        <h1>Total Target dan Realisasi Bulanan</h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 mb-3">
        <?php
        $target_keu_monthly = [];
        $realisasi_keu_monthly = [];
        $target_fisik_monthly = [];
        $realisasi_fisik_monthly = [];
        for ($i = 1; $i <= 12; $i++) {
            $target_keu_monthly[] = (float)$monthlymonitor[$i]['total']['target_keu'];
            $realisasi_keu_monthly[] = (float)$monthlymonitor[$i]['total']['realisasi_keu'];
            $target_fisik_monthly[] = (float)$monthlymonitor[$i]['total']['target_fisik'];
            $realisasi_fisik_monthly[] = (float)$monthlymonitor[$i]['total']['realisasi_fisik'];
        }
        ?>
        <?=
        Highcharts::widget([
            'options' => [
                'title' => ['text' => 'Total Target dan Realisasi Keuangan'],
                'xAxis' => [
                    'categories' => [
                        'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des',
                    ],
                ],
                'yAxis' => [
                    'title' => ['text' => 'Jumlah (Rp)'],
                ],
                'series' => [
                    ['name' => 'Target', 'data' => $target_keu_monthly],
                    ['name' => 'Realisasi', 'data' => $realisasi_keu_monthly],
                ],
            ],
        ]);
        ?>
    </div>
    <div class="col-lg-6">
        <?=
        Highcharts::widget([
            'options' => [
                'title' => ['text' => 'Rerata Target dan Realisasi Fisik'],
                'xAxis' => [
                    'categories' => [
                        'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des',
                    ],
                ],
                'yAxis' => [
                    'title' => ['text' => 'Pencapaian (%)'],
                ],
                'series' => [
                    ['name' => 'Target', 'data' => $target_fisik_monthly],
                    ['name' => 'Realisasi', 'data' => $realisasi_fisik_monthly],
                ],
            ],
        ]);
        ?>
    </div>
</div>

<div class="row pt-4">
    <div class="col-lg-12 mb-4 mb-lg-0">
        <div class="card">
            <div class="card-body">
                <?php Pjax::begin(); ?>
                <?= GridView::widget([
                    'dataProvider' => $totalbymonthprovider,
                    'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                    'pager' => [
                        'firstPageLabel' => Yii::t('app', 'First'),
                        'lastPageLabel' => Yii::t('app', 'Last'),
                    ],
                    'responsive' => true,
                    'responsiveWrap' => false,
                    // 'filterModel' => $searchModel,
                    // 'showFooter' => true,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute' => 'month',
                            'value' => 'month_name',
                            'label' => 'Bulan',
                        ],
                        [
                            'attribute' => 'price',
                            'format' => ['decimal', 2],
                            'label' => 'Total Nilai Kontrak (Rp)',
                        ],
                        [
                            'attribute' => 'target_keu',
                            'format' => ['decimal', 2],
                            'label' => 'Total Target Keuangan (Rp)',
                        ],
                        [
                            'attribute' => 'realisasi_keu',
                            'format' => ['decimal', 2],
                            'label' => 'Total Realisasi Keuangan (Rp)',
                        ],
                        [
                            'attribute' => 'target_keu_percent',
                            'format' => ['decimal', 2],
                            'label' => 'Total Target Keuangan (%)',
                        ],
                        [
                            'attribute' => 'realisasi_keu_percent',
                            'format' => ['decimal', 2],
                            'label' => 'Total Realisasi Keuangan (%)',
                        ],
                        [
                            'attribute' => 'target_fisik',
                            'format' => ['decimal', 2],
                            'label' => 'Rerata Target Fisik (%)',
                        ],
                        [
                            'attribute' => 'realisasi_fisik',
                            'format' => ['decimal', 2],
                            'label' => 'Rerata Realisasi Fisik (%)',
                        ],
                    ],
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>

<!-- end: page -->