<?php

use app\assets\LeafletSidebarAsset;
use dosamigos\leaflet\layers\GeoJson;
use dosamigos\leaflet\LeafLetAsset;

LeafLetAsset::register($this);
LeafletSidebarAsset::register($this);

?>
<div class="row">
    <div class="col-md-12">
        <div id="sidebar" class="sidebar collapsed">
            <!-- Nav tabs -->
            <div class="sidebar-tabs">
                <ul role="tablist">
                    <li><a href="#home" role="tab"><i class="fa fa-bars"></i></a></li>
                    <li><a href="#profile" role="tab"><i class="fa fa-user"></i></a></li>
                    <li class="disabled"><a href="#messages" role="tab"><i class="fa fa-envelope"></i></a></li>
                    <!-- <li><a href="https://github.com/Turbo87/sidebar-v2" role="tab" target="_blank"><i class="fa fa-github"></i></a></li> -->
                </ul>

                <ul role="tablist">
                    <li><a href="#settings" role="tab"><i class="fa fa-gear"></i></a></li>
                </ul>
            </div>

            <!-- Tab panes -->
            <div class="sidebar-content">
                <div class="sidebar-pane" id="home">
                    <h1 class="sidebar-header">
                        sidebar-v2
                        <span class="sidebar-close"><i class="fa fa-caret-left"></i></span>
                    </h1>

                    <p>A responsive sidebar for mapping libraries like <a href="http://leafletjs.com/">Leaflet</a> or <a href="http://openlayers.org/">OpenLayers</a>.</p>

                    <p class="lorem">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>

                    <p class="lorem">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>

                    <p class="lorem">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>

                    <p class="lorem">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
                </div>

                <div class="sidebar-pane" id="profile">
                    <h1 class="sidebar-header">Profile<span class="sidebar-close"><i class="fa fa-caret-left"></i></span></h1>
                </div>

                <div class="sidebar-pane" id="messages">
                    <h1 class="sidebar-header">Messages<span class="sidebar-close"><i class="fa fa-caret-left"></i></span></h1>
                </div>

                <div class="sidebar-pane" id="settings">
                    <h1 class="sidebar-header">Settings<span class="sidebar-close"><i class="fa fa-caret-left"></i></span></h1>
                </div>
            </div>
        </div>
        
        <!-- <div id="map" style="width: 800px; height: 600px; border: 1px solid rgb(204, 204, 204); position: relative;" tabindex="0"><div class="leaflet-pane leaflet-map-pane" style="transform: translate3d(0px, 0px, 0px);"><div class="leaflet-pane leaflet-tile-pane"><div class="leaflet-layer " style="z-index: 1; opacity: 1;"><div class="leaflet-tile-container leaflet-zoom-animated" style="z-index: 18; transform: translate3d(0px, 0px, 0px) scale(1);"><img alt="" role="presentation" src="http://b.tile.openstreetmap.org/13/4094/2723.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(121px, 9px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://c.tile.openstreetmap.org/13/4095/2723.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(377px, 9px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://c.tile.openstreetmap.org/13/4094/2724.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(121px, 265px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://a.tile.openstreetmap.org/13/4095/2724.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(377px, 265px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://a.tile.openstreetmap.org/13/4094/2722.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(121px, -247px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://b.tile.openstreetmap.org/13/4095/2722.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(377px, -247px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://a.tile.openstreetmap.org/13/4093/2723.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(-135px, 9px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://a.tile.openstreetmap.org/13/4096/2723.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(633px, 9px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://b.tile.openstreetmap.org/13/4093/2724.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(-135px, 265px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://b.tile.openstreetmap.org/13/4096/2724.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(633px, 265px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://a.tile.openstreetmap.org/13/4094/2725.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(121px, 521px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://b.tile.openstreetmap.org/13/4095/2725.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(377px, 521px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://c.tile.openstreetmap.org/13/4093/2722.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(-135px, -247px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://c.tile.openstreetmap.org/13/4096/2722.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(633px, -247px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://c.tile.openstreetmap.org/13/4093/2725.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(-135px, 521px, 0px); opacity: 1;"><img alt="" role="presentation" src="http://c.tile.openstreetmap.org/13/4096/2725.png" class="leaflet-tile leaflet-tile-loaded" style="width: 256px; height: 256px; transform: translate3d(633px, 521px, 0px); opacity: 1;"></div></div></div><div class="leaflet-pane leaflet-shadow-pane"></div><div class="leaflet-pane leaflet-overlay-pane"></div><div class="leaflet-pane leaflet-marker-pane"></div><div class="leaflet-pane leaflet-tooltip-pane"></div><div class="leaflet-pane leaflet-popup-pane"></div><div class="leaflet-proxy leaflet-zoom-animated"></div></div><div class="leaflet-control-container"><div class="leaflet-top leaflet-left"><div class="leaflet-control-zoom leaflet-bar leaflet-control"><a class="leaflet-control-zoom-in" href="#" title="Zoom in" role="button" aria-label="Zoom in">+</a><a class="leaflet-control-zoom-out" href="#" title="Zoom out" role="button" aria-label="Zoom out">−</a></div><div class="leaflet-control-layers leaflet-control-layers-expanded leaflet-control" aria-haspopup="true"><a class="leaflet-control-layers-toggle" href="#" title="Layers"></a><form class="leaflet-control-layers-list"><div class="leaflet-control-layers-base"><label><div><input type="radio" class="leaflet-control-layers-selector" name="leaflet-base-layers" checked="checked"><span> osm</span></div></label><label><div><input type="radio" class="leaflet-control-layers-selector" name="leaflet-base-layers"><span> google</span></div></label></div><div class="leaflet-control-layers-separator"></div><div class="leaflet-control-layers-overlays"><label><div><input type="checkbox" class="leaflet-control-layers-selector" checked=""><span> drawlayer</span></div></label></div></form></div><div class="leaflet-draw leaflet-control"><div class="leaflet-draw-section"><div class="leaflet-draw-toolbar leaflet-bar leaflet-draw-toolbar-top"><a class="leaflet-draw-draw-polyline" href="#" title="Draw a polyline"><span class="sr-only">Draw a polyline</span></a><a class="leaflet-draw-draw-polygon" href="#" title="Draw a polygon"><span class="sr-only">Draw a polygon</span></a><a class="leaflet-draw-draw-rectangle" href="#" title="Draw a rectangle"><span class="sr-only">Draw a rectangle</span></a><a class="leaflet-draw-draw-circle" href="#" title="Draw a circle"><span class="sr-only">Draw a circle</span></a><a class="leaflet-draw-draw-marker" href="#" title="Draw a marker"><span class="sr-only">Draw a marker</span></a><a class="leaflet-draw-draw-circlemarker" href="#" title="Draw a circlemarker"><span class="sr-only">Draw a circlemarker</span></a></div><ul class="leaflet-draw-actions"></ul></div><div class="leaflet-draw-section"><div class="leaflet-draw-toolbar leaflet-bar"><a class="leaflet-draw-edit-edit leaflet-disabled" href="#" title="No layers to edit"><span class="sr-only">Edit layers</span></a><a class="leaflet-draw-edit-remove leaflet-disabled" href="#" title="No layers to delete"><span class="sr-only">Delete layers</span></a></div><ul class="leaflet-draw-actions"></ul></div></div></div><div class="leaflet-top leaflet-right"></div><div class="leaflet-bottom leaflet-left"></div><div class="leaflet-bottom leaflet-right"><div class="leaflet-control-attribution leaflet-control"><a href="http://leafletjs.com" title="A JS library for interactive maps">Leaflet</a> | © <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors</div></div></div></div> -->
        <div id="map" class="sidebar-map" style="width: 100vw; height: 100vh; border: 1px solid rgb(204, 204, 204); position: relative;"></div>
        <a href="https://github.com/Turbo87/sidebar-v2/"><img style="position: fixed; top: 0; right: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_right_darkblue_121621.png" alt="Fork me on GitHub"></a>
    </div>
</div>
<?php

$lat = Yii::$app->appsetting->get('default_latitude') ? Yii::$app->appsetting->get('default_latitude') : '-7.5557674322944965';
$lng = Yii::$app->appsetting->get('default_longitude') ? Yii::$app->appsetting->get('default_longitude') :'110.82480558356784';
// $lat = $model->latitude ? $model->latitude : $lat;
// $lng = $model->longitude ? $model->longitude : $lng;
// if($model->geojson){
//     $geojsondata = $model->geojson;
// }
// else{
    $geojsondata = json_encode([]);
// }
$locations = json_encode($locations);
$this->registerJs(
<<< EOT_JS_CODE
let osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
// let osmUrl = 'http://{s}.tile.osm.org/{z}/{x}/{y}.png';
let osmAttrib = '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMaps</a> contributors';
let osm = L.tileLayer(osmUrl, { maxZoom: 18, attribution: osmAttrib });
// let osm = L.tileLayer(osmUrl, { maxZoom: 18});
let map = new L.Map('map', { center: new L.LatLng($lat, $lng), zoom: 13 });

let locations = $locations;
let layers = [];
console.log(locations);
for(let i = 0; i < locations.length; i++){
    let geoJsonData = JSON.parse(locations[i]);
    let dataJson = L.geoJSON(geoJsonData,{
        pointToLayer: (feature, latlng) => {
            if (feature.properties.radius) {
                return new L.Circle(latlng, feature.properties.radius);
            } else {
                return new L.Marker(latlng);
            }
        },
        onEachFeature: (feature, layer) => {
            layer.addTo(map);
        },
    });
}

osm.addTo(map);
let sidebar = L.control.sidebar('sidebar').addTo(map);

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
// map.pm.addControls(options);

// $("#testclick").on("click",function(){
map.on("pm:create",convertToGeoJSON);
map.on("pm:change",convertToGeoJSON);
map.on("pm:update",convertToGeoJSON);
map.on("pm:drag",convertToGeoJSON);
map.on("pm:remove",convertToGeoJSON);
map.on("pm:cut",convertToGeoJSON);
map.on("pm:edit",convertToGeoJSON);
map.on("pm:rotate",convertToGeoJSON);

function convertToGeoJSON(){
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