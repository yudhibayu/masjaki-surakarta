<?php

use app\overrides\leaflet\GeoJsonCustomColorIcon;
use dosamigos\leaflet\layers\GeoJson;
use dosamigos\leaflet\layers\Marker;
use dosamigos\leaflet\layers\TileLayer;
use dosamigos\leaflet\LeafLet;
use dosamigos\leaflet\types\LatLng;
use dosamigos\leaflet\widgets\Map;
use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\modules\locations\models\MonitoringTaskLocations */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => $model->monitoring_task_name, 'url' => ['default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="monitoring-task-locations-view">
            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), ['default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)], ['class' => 'btn btn-warning']) ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => Yii::$app->hasher->encode($model->id)], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                        'attributes' => [
                            'name:ntext',
                            [
                                'attribute' => 'monitoring_task_id',
                                'format' => 'html',
                                'value' => Html::a($model->monitoring_task_name, ['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]),
                            ],
                            'latitude',
                            'longitude',
                            // [
                            //     'attribute' => 'geojson',
                            //     'format' => 'html',
                            //     'value' => Html::a('Open at geojson.io','https://geojson.io/#data=data:application/json,'.urlencode($model->geojson),['target'=>'_blank']),
                            // ],
                            'description:ntext',
                            'created_at:datetime',
                            [
                                'attribute' => 'created_by',
                                'value' => $model->maker
                            ],
                            'updated_at:datetime',
                            [
                                'attribute' => 'updated_by',
                                'value' => $model->updater
                            ],
                        ],
                    ]) ?>
                </div>
                <div class="col-md-8">
                    <?php
                    $lat = Yii::$app->appsetting->get('default_latitude') ? Yii::$app->appsetting->get('default_latitude') : '-7.5557674322944965';
                    $lng = Yii::$app->appsetting->get('default_longitude') ? Yii::$app->appsetting->get('default_longitude') : '110.82480558356784';

                    $geojson = null;
                    if (isset($model->geojson) && $model->geojson != '') {
                        // $geojson = new GeoJson([
                        //     'data'=>json_decode($model->geojson)
                        // ]);
                        $geojson = new GeoJsonCustomColorIcon([
                            'data' => json_decode($model->geojson),
                            'color' => $model->objectType->map_color,
                            'iconurl' => $model->objectType->iconurl,
                        ]);
                    }

                    if ($model->latitude) {
                        $lat = $model->latitude;
                        $lng = $model->longitude;
                    }

                    $center = new LatLng(['lat' => $lat, 'lng' => $lng]);

                    // $marker = new Marker(['latLng' => $center, 'popupContent' => ($model->name) ? $model->name : Yii::t('app', 'I\'m here ')]);

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

                    if ($geojson) {
                        $leaflet->addLayer($geojson);
                    }

                    echo Map::widget(['leafLet' => $leaflet, 'height' => '600px']);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>