<?php

use app\assets\LeafletSidebarAsset;

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
                    <li><a href="#erasemap" role="button"><i class="fa fa-eraser"></i></a></li>
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

                <div class="sidebar-pane" id="erasemap">
                    <h1 class="sidebar-header">Erase Maps<span class="sidebar-close"><i class="fa fa-caret-left"></i></span></h1>
                </div>

                <div class="sidebar-pane" id="settings">
                    <h1 class="sidebar-header">Settings<span class="sidebar-close"><i class="fa fa-caret-left"></i></span></h1>
                </div>
            </div>
        </div>
        
        <div id="map" class="sidebar-map" style="width: 99vw; height: 98vh; border: 1px solid rgb(204, 204, 204); position: relative;"></div>
    </div>
</div>
<?php

$lat = Yii::$app->appsetting->get('default_latitude') ? Yii::$app->appsetting->get('default_latitude') : '-7.5557674322944965';
$lng = Yii::$app->appsetting->get('default_longitude') ? Yii::$app->appsetting->get('default_longitude') :'110.82480558356784';
$locations = json_encode($locations);
$this->registerJs(
<<< EOT_JS_CODE
let osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
let osmAttrib = '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMaps</a> contributors';
let osm = L.tileLayer(osmUrl, { maxZoom: 18, attribution: osmAttrib });
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
map.pm.addControls(options);

EOT_JS_CODE
);
?>