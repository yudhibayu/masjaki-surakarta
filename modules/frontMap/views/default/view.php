<?php

use app\overrides\leaflet\GeoJsonCustomColorIcon;
use dosamigos\leaflet\layers\TileLayer;
use dosamigos\leaflet\LeafLet;
use dosamigos\leaflet\types\LatLng;
use dosamigos\leaflet\widgets\Map;
use miloschuman\highcharts\Highcharts;
use newerton\fancybox3\FancyBox;
use yii\bootstrap5\Html;
use yii\widgets\DetailView;

// FrontMapAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\monitoring\models\MonitoringTaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Map');

echo FancyBox::widget([
    'target' => '[data-fancybox="monitoring"]',
]);
?>

<div class="view-frontmap-task" style="margin: 20px;">
    <div class="row">
        <div class="col-md-12">
            <span style="display:block; height: 80px;"></span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <?php
            $lat = Yii::$app->appsetting->get('default_latitude') ? Yii::$app->appsetting->get('default_latitude') : '-7.5557674322944965';
            $lng = Yii::$app->appsetting->get('default_longitude') ? Yii::$app->appsetting->get('default_longitude') : '110.82480558356784';

            $center = new LatLng(['lat' => $lat, 'lng' => $lng]);

            // $marker = new Marker(['latLng' => $center, 'popupContent' => Yii::t('app', 'I\'m here ')]);

            $tileLayer = new TileLayer([
                'urlTemplate' => 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                'clientOptions' => [],
            ]);


            $leaflet = new LeafLet([
                'center' => $center,
                'zoom' => 13,
                'clientOptions' => [
                    'dragging' => true,
                    'scrollWheelZoom' =>  true
                ],
            ]);

            $leaflet->addLayer($tileLayer);
            // $leaflet->addLayer($marker);

            foreach ($locationsDataProvider->query->all() as $locationModel) {
                $geojson = null;
                if (isset($locationModel->geojson) && $locationModel->geojson != '') {
                    $geojson = new GeoJsonCustomColorIcon([
                        'data' => json_decode($locationModel->geojson),
                        'color' => $locationModel->objectType->map_color,
                        'iconurl' => $locationModel->objectType->iconurl,
                        'popupmessage' => $locationModel->name,
                    ]);
                }
                if ($geojson) {
                    $leaflet->addLayer($geojson);
                }
            }

            echo Map::widget(['leafLet' => $leaflet, 'height' => '70vh']);
            ?>
        </div>
        <div class="col-md-1"></div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10" id="scurve-<?= $model->id ?>">
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
        <div class="col-md-1"></div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-5">
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
        <div class="col-md-5">
            <h4>Detail Monitoring</h4>
            <?php if (!Yii::$app->user->isGuest && Yii::$app->user->can('Administrator')) : ?>
                <!-- <p>
                    <?=
                    Html::a(Yii::t('app', 'Detail'), ['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($model->id)], [
                        'class' => 'btn btn-primary',
                    ]);
                    ?>
                </p> -->
            <?php endif; ?>
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
        <div class="col-md-1"></div>
    </div>
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
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
        <div class="col-md-1"></div>
    </div>
</div>
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


<!-- Modal -->
<div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body" id="target-modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>