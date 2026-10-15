<?php

namespace app\assets;

use yii\web\AssetBundle;

class LeafletSidebarAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'leaflet/leaflet-sidebar.min.css',
        'fontawesome-free-6.4.2-web/css/all.min.css',
    ];
    public $js = [
        'leaflet/leaflet-sidebar.min.js',
    ];
    public $depends = [
        'dosamigos\leaflet\LeafLetAsset',
    ];
}
