<?php

use miloschuman\highcharts\Highcharts;
use yii\bootstrap5\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\models\MonitoringTask */

$this->title = $model->name;

?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
        <p>
            <?php if (!Yii::$app->user->isGuest) : ?>
                <!-- <?=
                        Html::a(Yii::t('app', 'Detail'), ['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($model->id)], [
                            'class' => 'btn btn-primary',
                        ]);
                        ?> -->
            <?php endif; ?>
            <!-- <?=
                    Html::a(Yii::t('app', 'Individual Map'), ['view', 'id' => Yii::$app->hasher->encode($model->id)], [
                        'class' => 'btn btn-secondary',
                        'target' => '_blank',
                    ]);
                    ?> -->
        </p>
    </div>
    <div class="card-body">
        <div class="monitoring-task-view">
            <br>
            <div class="row">
                <div class="plans hidden" id="plansdata" hidden><?= json_encode($plans) ?></div>
                <div class="realisations hidden" id="realisationsdata" hidden><?= json_encode($realisations) ?></div>
                <div class="weeks hidden" id="weeksdata" hidden><?= json_encode($weeks) ?></div>
                <div class="scurveid hidden" id="scurveid" hidden><?= "scurve-" . $model->id ?></div>
                <div class="col-md-12" id="scurve-<?= $model->id ?>">
                    <?php echo Highcharts::widget([
                        // 'id' => 'scurve-'.$model->id,
                        'options' => [
                            // 'chart' => [
                            //     'plotBackgroundColor' => null,
                            //     'plotBorderWidth' => null,
                            //     'plotShadow' => false,
                            // ],
                            'credits' => ['enabled' => false],
                            'chart' => [
                                'renderTo' => 'scurve-' . $model->id,
                            ],
                            'title' => [
                                'text' => 'Kurva S',
                                'align' => 'center',
                            ],
                            'subtitle' => [
                                'text' => 'Total Data : ',
                                'align' => 'center',
                            ],
                            'xAxis' => [
                                'categories' => $weeks,
                                'title' => [
                                    'text' => 'Minggu ke-'
                                ],
                            ],
                            'yAxis' => [
                                'title' => [
                                    'text' => 'Progress (%)'
                                ],
                            ],
                            'tooltip' => [
                                'pointFormat' => '{series.name}: <b>{point.y}</b>%'
                            ],
                            'accessibility' => [
                                'point' => [
                                    'valueSuffix' => '%',
                                ]
                            ],
                            'plotOptions' => [
                                'pie' => [
                                    'allowPointSelect' => true,
                                    'cursor' => 'pointer',
                                    'dataLabels' => [
                                        'enabled' => true,
                                        'format' => '<b>{point.name}</b>: {point.y}%'
                                    ]
                                ]
                            ],
                            'series' => [
                                [
                                    'name' => 'Target',
                                    // 'colorByPoint' => true,
                                    'data' => $plans,
                                ],
                                [
                                    'name' => 'Realisasi',
                                    // 'colorByPoint' => true,
                                    'data' => $realisations,
                                ],
                            ]
                        ]
                    ]);
                    ?>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12" style="border-radius: 12px;">
                    <h4>Detail RUP</h4>
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                        'attributes' => [
                            'rup.namasatker',
                            'rup.namapaket',
                            'rup.metodepengadaan',
                            'rup.sumberdana',
                            'rup.tanggalawalpemilihan:date',
                            'rup.tanggalakhirpemilihan:date',
                            'rup.tanggalawalpekerjaan:date',
                            'rup.tanggalakhirpekerjaan:date',
                            [
                                'attribute' => 'rup.jumlahpagu',
                                'format' => ['decimal', 2],
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12" style="border-radius: 12px;">
                    <h4>Detail Monitoring</h4>
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                        'attributes' => [
                            'latlng',
                            'location_description:ntext',
                            'objectType.name',
                            'contractor_name',
                            'contract_number',
                            [
                                'attribute' => 'implementation_status',
                                'value' => function ($model) {
                                    return $model->implementationStatusNames()[$model->implementation_status];
                                }
                            ],
                            [
                                'attribute' => 'final_price',
                                'format' => ['decimal', 2],
                            ],
                            'contract_date:date',
                            'work_days',
                            'latest_monitored_physical_percent',
                        ],
                    ]) ?>
                </div>
            </div>
            <div class="accordion" id="monthsaccordion">
                <?php
                $i = 0;
                foreach ($model->getMonthMonitoringAvaiable() as $monthavailable) {
                    // echo '<pagebreak>';
                    echo $this->render('report_monitoring_month_progresses', [
                        'task' => $model,
                        'month' => $monthavailable,
                        'iterate' => $i,
                    ]);
                    $i++;
                }
                ?>
            </div>
        </div>
    </div>
</section>