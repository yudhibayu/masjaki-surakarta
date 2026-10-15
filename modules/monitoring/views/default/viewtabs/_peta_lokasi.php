<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\overrides\leaflet\GeoJsonCustomColorIcon;
use dosamigos\leaflet\layers\GeoJson;
use dosamigos\leaflet\layers\TileLayer;
use dosamigos\leaflet\LeafLet;
use dosamigos\leaflet\types\LatLng;
use dosamigos\leaflet\widgets\Map;

?>
<div class="row">
    <div class="col-md-12 table-responsive">
        <h4><?= Yii::t('monitoring', 'Locations') ?></h4>
        <?=
        GridView::widget([
            'dataProvider' => $locationsDataProvider,
            // 'tableOptions' => ['class' => 'table table-striped table-bordered', 'style'=>'font-size:11px'],
            'filterModel' => $locationsModel,
            'showFooter' => true,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'name:ntext',
                // [
                //     'attribute' => 'geojson',
                //     'format' => 'html',
                //     'value' => function($model){
                //         return Html::a('Open at geojson.io','https://geojson.io/#data=data:application/json,'.urlencode($model->geojson),[]);
                //     },
                // ],
                // 'latitude',
                // 'longitude',
                // 'site_plan:ntext',
                // 'site_trace:ntext',
                'description:ntext',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => Yii::t('app', 'Action'),
                    'headerOptions' => ['width' => '80'],
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            return Html::a(
                                '<span class="fas fa-eye"></span>',
                                [
                                    Url::toRoute(['locations/view', 'id' => Yii::$app->hasher->encode($model->id)])
                                ],
                                [
                                    'data' => ['pjax' => 0],
                                    'class' => 'btn btn-xs btn-primary',
                                    'style' => 'margin: 2px;width: 35px;',
                                ]
                            );
                        },
                    ],
                    'footer' => Html::a(Yii::t('app', 'Add'), Url::to(['locations/create', 'id' => Yii::$app->hasher->encode($model->id)]), ['class' => 'btn btn-primary', 'data' => ['pjax' => 0],]),
                ],
            ],
        ]);
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php
        $lat = Yii::$app->appsetting->get('default_latitude') ? Yii::$app->appsetting->get('default_latitude') : '-7.5557674322944965';
        $lng = Yii::$app->appsetting->get('default_longitude') ? Yii::$app->appsetting->get('default_longitude') : '110.82480558356784';
        if ($model->default_lat && $model->default_lng) {
            $lat = $model->default_lat;
            $lng = $model->default_lng;
        }

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
            'clientEvents' => [
                'viewreset' => 'function(){ map.invalidateSize()}',
                'click' => 'function(){ map.invalidateSize()}',
                // 'mouseover' => 'function(){ map.invalidateSize()}',
            ],
        ]);
        $leaflet->addLayer($tileLayer);
        $leaflet->appendJs("$('#peta-lokasi-tab').on('click', function(){ map.invalidateSize()});");
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

        echo Map::widget(['leafLet' => $leaflet, 'height' => '600px']);
        ?>
    </div>
</div>