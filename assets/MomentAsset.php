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
class MomentAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'plugins/moments/moment.min.js',
        'plugins/moments/moment.id.js',
        //'plugins/moments/moment-with-locales.min.js',
        //'plugins/moments/my-clock.js',
    ];
    public $depends = [
    	'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapPluginAsset'
    ];
}
