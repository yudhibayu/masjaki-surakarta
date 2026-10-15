<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class FrontMapAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/frontMap/assets';
    public $css = [
        'font/css/all.min.css',
    	'css/leaflet.css',
    	'css/leaflet-sidebar.min.css',
    	'css/front-map.css',
    ];
    public $js = [
        'font/js/all.min.js',
        'js/leaflet.js',
        //'js/leaflet.js.map',
        'js/leaflet-sidebar.min.js',
        'js/front-map.js',
    ];
    public $depends = [
        //'yii\web\YiiAsset',
        //'yii\bootstrap5\BootstrapAsset',
        //'app/assets/StandardFrontAsset',
    ];
}
