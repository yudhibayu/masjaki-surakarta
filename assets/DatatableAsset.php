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
class DatatableAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'plugins/datatables/datatables.min.css',
        'plugins/datatables/Buttons-1.5.6/css/buttons.dataTables.min.css',
        'plugins/datatables/Responsive-2.2.2/css/responsive.bootstrap.min.css',
    ];
    public $js = [
        'plugins/datatables/datatables.min.js',
        //'plugins/datatables/Buttons-1.5.6/js/dataTables.buttons.min.js',
        'plugins/datatables/Buttons-1.5.6/js/jszip.min.js',
        'plugins/datatables/Buttons-1.5.6/js/buttons.html5.min.js',
        'plugins/datatables/Responsive-2.2.2/js/dataTables.responsive.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapPluginAsset'
    ];

}
