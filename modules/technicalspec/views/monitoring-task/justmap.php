<?php

use dosamigos\leaflet\layers\GeoJson;
use dosamigos\leaflet\layers\Marker;
use dosamigos\leaflet\layers\TileLayer;
use dosamigos\leaflet\LeafLet;
use dosamigos\leaflet\types\LatLng;
use dosamigos\leaflet\types\Type;
use dosamigos\leaflet\widgets\Map;
use yii\helpers\Html;
use newerton\fancybox3\FancyBox;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\models\MonitoringTask */

$this->title = Yii::t('app','Map').' '.$model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('monitoring', 'Monitorings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="monitoring-task-view">
            <div class="row">
                <div class="col-md-2">
                    <h4>Keterangan</h4>
                    <p>
                        Deskripsi
                    </p>
                </div>
                <div class="col-md-10">
                    <?php 
                        $lat = Yii::$app->appsetting->get('default_latitude') ? Yii::$app->appsetting->get('default_latitude') : '-7.5557674322944965';
                        $lng = Yii::$app->appsetting->get('default_longitude') ? Yii::$app->appsetting->get('default_longitude') :'110.82480558356784';

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
                            if(isset($locationModel->geojson) && $locationModel->geojson != ''){
                                $geojson = new GeoJson([
                                    'data'=>json_decode($locationModel->geojson),
                                    'clientOptions' => [
                                        // 'style' => new JsExpression("function (feature) {
                                        //     return {color: '#000000'};
                                        // }"),
                                    ],
                                ]);
                            }
                            if($geojson){
                                $leaflet->addLayer($geojson);
                            }
                        }

                        echo Map::widget(['leafLet' => $leaflet, 'height' => '600']);
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
