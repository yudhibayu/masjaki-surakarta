<?php

use dosamigos\leaflet\LeafLetAsset;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\modules\locations\models\MonitoringTaskLocations */
/* @var $form yii\widgets\ActiveForm */

//register assets
LeafLetAsset::register($this);
?>

<div class="monitoring-task-locations-form">
    <div class="row">
        <div class="col-md-12">
            <!-- <div id="map" style="width: 800px; height: 600px; border: 1px solid rgb(204, 204, 204); position: relative;" tabindex="0"><div class="leaflet-pane leaflet-map-pane" style="transform: translate3d(0px, 0px, 0px);"><div class="leaflet-pane leaflet-tile-pane"><div class="leaflet-layer " style="z-index: 1; opacity: 1;"><div class="leaflet-tile-container leaflet-zoom-animated" style="z-index: 18; transform: translate3d(0px, 0px, 0px) scale(1);"><img alt="" role="presentation" src="http://b.tile.openstreetmap.org/13/4094/2723.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(121px, 9px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://c.tile.openstreetmap.org/13/4095/2723.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(377px, 9px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://c.tile.openstreetmap.org/13/4094/2724.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(121px, 265px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://a.tile.openstreetmap.org/13/4095/2724.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(377px, 265px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://a.tile.openstreetmap.org/13/4094/2722.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(121px, -247px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://b.tile.openstreetmap.org/13/4095/2722.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(377px, -247px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://a.tile.openstreetmap.org/13/4093/2723.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(-135px, 9px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://a.tile.openstreetmap.org/13/4096/2723.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(633px, 9px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://b.tile.openstreetmap.org/13/4093/2724.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(-135px, 265px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://b.tile.openstreetmap.org/13/4096/2724.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(633px, 265px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://a.tile.openstreetmap.org/13/4094/2725.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(121px, 521px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://b.tile.openstreetmap.org/13/4095/2725.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(377px, 521px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://c.tile.openstreetmap.org/13/4093/2722.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(-135px, -247px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://c.tile.openstreetmap.org/13/4096/2722.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(633px, -247px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://c.tile.openstreetmap.org/13/4093/2725.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(-135px, 521px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://c.tile.openstreetmap.org/13/4096/2725.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(633px, 521px, 0px); opacity: 1;"></div></div></div><div class="leaflet-pane leaflet-shadow-pane"></div><div class="leaflet-pane leaflet-overlay-pane"></div><div class="leaflet-pane leaflet-marker-pane"></div><div class="leaflet-pane leaflet-tooltip-pane"></div><div class="leaflet-pane leaflet-popup-pane"></div><div class="leaflet-proxy leaflet-zoom-animated"></div></div><div class="leaflet-control-container"><div class="leaflet-top leaflet-left"><div class="leaflet-control-zoom leaflet-bar leaflet-control"><a class="leaflet-control-zoom-in" href="#" title="Zoom in" role="button" aria-label="Zoom in">+</a><a class="leaflet-control-zoom-out" href="#" title="Zoom out" role="button" aria-label="Zoom out">−</a></div><div class="leaflet-control-layers leaflet-control-layers-expanded leaflet-control" aria-haspopup="true"><a class="leaflet-control-layers-toggle" href="#" title="Layers"></a><form class="leaflet-control-layers-list"><div class="leaflet-control-layers-base"><label><div><input type="radio" class="leaflet-control-layers-selector" name="leaflet-base-layers" checked="checked"><span> osm</span></div></label><label><div><input type="radio" class="leaflet-control-layers-selector" name="leaflet-base-layers"><span> google</span></div></label></div><div class="leaflet-control-layers-separator"></div><div class="leaflet-control-layers-overlays"><label><div><input type="checkbox" class="leaflet-control-layers-selector" checked=""><span> drawlayer</span></div></label></div></form></div><div class="leaflet-draw leaflet-control"><div class="leaflet-draw-section"><div class="leaflet-draw-toolbar leaflet-bar leaflet-draw-toolbar-top"><a class="leaflet-draw-draw-polyline" href="#" title="Draw a polyline"><span class="sr-only">Draw a polyline</span></a><a class="leaflet-draw-draw-polygon" href="#" title="Draw a polygon"><span class="sr-only">Draw a polygon</span></a><a class="leaflet-draw-draw-rectangle" href="#" title="Draw a rectangle"><span class="sr-only">Draw a rectangle</span></a><a class="leaflet-draw-draw-circle" href="#" title="Draw a circle"><span class="sr-only">Draw a circle</span></a><a class="leaflet-draw-draw-marker" href="#" title="Draw a marker"><span class="sr-only">Draw a marker</span></a><a class="leaflet-draw-draw-circlemarker" href="#" title="Draw a circlemarker"><span class="sr-only">Draw a circlemarker</span></a></div><ul class="leaflet-draw-actions"></ul></div><div class="leaflet-draw-section"><div class="leaflet-draw-toolbar leaflet-bar"><a class="leaflet-draw-edit-edit leaflet-disabled" href="#" title="No layers to edit"><span class="sr-only">Edit layers</span></a><a class="leaflet-draw-edit-remove leaflet-disabled" href="#" title="No layers to delete"><span class="sr-only">Delete layers</span></a></div><ul class="leaflet-draw-actions"></ul></div></div></div><div class="leaflet-top leaflet-right"></div><div class="leaflet-bottom leaflet-left"></div><div class="leaflet-bottom leaflet-right"><div class="leaflet-control-attribution leaflet-control"><a href="http://leafletjs.com" title="A JS library for interactive maps">Leaflet</a> | © <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors</div></div></div></div> -->
            <div id="map" style="height: 600px; border: 1px solid rgb(204, 204, 204); position: relative;"></div>
        </div>
    </div>
    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'name')->textInput() ?>
        </div>
        <div class='col-md-3'>
            <?= $form->field($model, 'latitude')->textInput(['id' => 'latlng-latitude']) ?>
        </div>
        <div class='col-md-3'>
            <?= $form->field($model, 'longitude')->textInput(['id' => 'latlng-longitude']) ?>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-12'>
            <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-12'>
            <?= $form->field($model, 'geojson')->hiddenInput(['id' => 'modelgeojson'])->label(false); ?>
        </div>
    </div>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), ['default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)], ['class' => 'btn btn-warning']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
<!-- <button id="testclick" type="button">
Test Click
</button> -->
<script>

</script>
<?php

$lat = Yii::$app->appsetting->get('default_latitude') ? Yii::$app->appsetting->get('default_latitude') : '-7.5557674322944965';
$lng = Yii::$app->appsetting->get('default_longitude') ? Yii::$app->appsetting->get('default_longitude') : '110.82480558356784';
$lat = $model->latitude ? $model->latitude : $lat;
$lng = $model->longitude ? $model->longitude : $lng;
if ($model->geojson) {
    $geojsondata = $model->geojson;
} else {
    $geojsondata = json_encode([]);
}
$objectType = $model->objectType;
$this->registerJs(
    <<< EOT_JS_CODE
let osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
// let osmUrl = 'http://{s}.tile.osm.org/{z}/{x}/{y}.png';
let osmAttrib = '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMaps</a> contributors';
let osm = L.tileLayer(osmUrl, { maxZoom: 18, attribution: osmAttrib });
// let osm = L.tileLayer(osmUrl, { maxZoom: 18});
let map = new L.Map('map', { center: new L.LatLng($lat, $lng), zoom: 13 });
let geoJsonData = $geojsondata;
let dataJson = L.geoJSON(geoJsonData,{
    style: { color : '$objectType->map_color'},
    pointToLayer: (feature, latlng) => {
        if (feature.properties.radius) {
            return new L.Circle(latlng, feature.properties.radius);
        } else {
            if('$objectType->iconurl' != '/images/icons/marker-icon.png'){
                let baseIcon = L.Icon.extend({
                    options: {
                        iconSize: [32, 32],
                        // iconAnchor: [-16, -16],
                        // popupAnchor: [-3, -76],
                        shadowUrl: '/images/icon_map/marker-shadow.png',
                        shadowSize: [32, 32],
                        shadowAnchor: [8, 16]
                    }
                });
                let newicon = new baseIcon({
                    iconUrl: '$objectType->iconurl',
                });
                let marker = L.marker(latlng,{
                    icon: newicon,
                });
                return marker;
            }
            let marker = L.marker(latlng);
            return marker;
        }
    },
    onEachFeature: (feature, layer) => {
        layer.on("pm:change",convertToGeoJSON);
        layer.addTo(map);
    },
});

osm.addTo(map);

// add Leaflet-Geoman controls with some options to the map
let options = {
    position: 'topleft', // toolbar position, options are 'topleft', 'topright', 'bottomleft', 'bottomright'
    drawMarker: true,  // adds button to draw markers
    drawPolygon: true,  // adds button to draw a polygon
    drawPolyline: true,  // adds button to draw a polyline
    drawCircle: true,  // adds button to draw a cricle
    drawCircleMarker: false,  // adds button to draw a cricle marker
    drawText: false,  // adds button to draw a text marker
    editPolygon: true,  // adds button to toggle global edit mode
    deleteLayer: true   // adds a button to delete layers
};
map.pm.addControls(options);

map.on("pm:create",convertToGeoJSON);
map.on("pm:change",convertToGeoJSON);
map.on("pm:update",convertToGeoJSON);
map.on("pm:edit",convertToGeoJSON);
map.on("pm:drag",convertToGeoJSON);
map.on("pm:remove",convertToGeoJSON);
map.on("pm:cut",convertToGeoJSON);
map.on("pm:rotate",convertToGeoJSON);

map.on("pm:create",function(e) {
    // console.log(e);
    if(e.shape == "Marker"){
        let latlng = e.layer.getLatLng();
        let lat = latlng.lat;
        let lng = latlng.lng;
        $("#latlng-latitude").val(lat);
        $("#latlng-longitude").val(lng);
        // console.log(latlng);
    }
    e.layer.on("pm:change",convertToGeoJSON);
});

function convertToGeoJSON(e){
    var layers = L.PM.Utils.findLayers(map);
    var group = L.featureGroup();
    layers.forEach((layer)=>{
        if (layer instanceof L.Circle) {
            // let feature = layer.feature = layer.feature || {};
            // feature.type = feature.type || "Feature";
            // let props = feature.properties = feature.properties || {};
            // props.radius = layer.getRadius();
            // console.log(layer.getRadius());
            layer = L.PM.Utils.circleToPolygon(layer, 60);
        }
        if(layer instanceof L.Marker){
            console.log(layer);
            try {
                console.log(layer.pm.getElement().value);
            } catch (error) {
                
            }
        }
        group.addLayer(layer);
    });
    shapes = group.toGeoJSON();
    $("#modelgeojson").val(JSON.stringify(shapes));
}

EOT_JS_CODE
);
?>