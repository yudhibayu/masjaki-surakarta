<?php

/**
 * @copyright Copyright (c) 2013-2015 2amigOS! Consulting Group LLC
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace app\overrides\leaflet;

use dosamigos\leaflet\layers\GeoJson;
use dosamigos\leaflet\LeafLet;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * @inheritdoc
 */
class GeoJsonCustomColorIcon extends GeoJson
{
    /**
     * @var array geo spatial data interchange json object. For information related to GeoJSON format, please visit
     * [http://geojson.org/geojson-spec.html](http://geojson.org/geojson-spec.html). This component does not validate
     * this data, it just renders it. This array will be converted into a json object previous encoding.
     */
    public $data = [];
    public $color = '#3388ff';
    public $iconurl = '/images/icons/marker-icon.png';
    public $popupmessage = null;

    /**
     * Returns the javascript ready code for the object to render
     * @return string|JsExpression
     */
    public function encode()
    {
        $data = Json::encode($this->data, LeafLet::JSON_OPTIONS);
        $pointToLayer = "
            function(feature,latlng){
                if('" . $this->iconurl . "' != '/images/icons/marker-icon.png'){
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
                        iconUrl: '" . $this->iconurl . "',
                    });
                    let marker = L.marker(latlng,{
                        icon: newicon,
                    });
                    return marker;
                }
                let marker = L.marker(latlng);
                return marker;
            }
        ";
        $oneachfeature = "
            function (feature, layer){
                return layer;
            },
            ";
        if ($this->popupmessage != null) {
            $oneachfeature = "
            function (feature, layer){
                // console.log(layer);
                layer.bindPopup('" . $this->popupmessage . "');
                return layer;
            },
            ";
        }
        $name = $this->name;
        $map = $this->map;
        $js = "L.geoJson($data, {
            style: { color : '" . $this->color . "'},
            pointToLayer : $pointToLayer,
            onEachFeature : $oneachfeature
        })";
        $js = $js . ($map !== null ? ".addTo($map)" : "") . ";";
        if (!empty($name)) {
            $js = "var $name = $js";
        }

        return new JsExpression($js);
    }
}
