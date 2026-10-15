<?php

use miloschuman\highcharts\Highcharts;

?>
<div class="row">
    <div class="col-md-12 table-responsive">
        <?php echo Highcharts::widget([
            'id' => 'pieid',
            'options' => [
                // 'chart' => [
                //     'plotBackgroundColor' => null,
                //     'plotBorderWidth' => null,
                //     'plotShadow' => false,
                // ],
                'title' => [
                    'text' => 'Grafik Progress',
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