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
class AdminPortoAsset extends AssetBundle
{
    public $sourcePath = '@app/themes/admin_porto';
    public $css = [
        '//fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800|Shadows+Into+Light',
        'vendor/animate/animate.compat.css',
        'vendor/font-awesome/css/all.min.css',
        'vendor/boxicons/css/boxicons.min.css',
        'vendor/magnific-popup/magnific-popup.css',
        'vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css',
        'vendor/jquery-ui/jquery-ui.css',
        'vendor/jquery-ui/jquery-ui.theme.css',
        'vendor/bootstrap-multiselect/css/bootstrap-multiselect.css',
        'vendor/morris/morris.css',
		'vendor/owl.carousel/assets/owl.carousel.css',
		'vendor/owl.carousel/assets/owl.theme.default.css',
        'css/theme.css',
        'css/skins/default.css',
        'css/custom.css',
		'fonts/icomoon/style.css',
		'fonts/flaticon/font/flaticon.css',
    ];
    public $js = [
        // 'vendor/jquery/jquery.js',
        'vendor/modernizr/modernizr.js',
		'vendor/jquery-browser-mobile/jquery.browser.mobile.js',
		'vendor/popper/umd/popper.min.js',
		// 'vendor/bootstrap/js/bootstrap.bundle.min.js',
		'vendor/bootstrap-datepicker/js/bootstrap-datepicker.js',
		'vendor/common/common.js',
		'vendor/nanoscroller/nanoscroller.js',
		'vendor/magnific-popup/jquery.magnific-popup.js',
		'vendor/jquery-placeholder/jquery.placeholder.js',
		'vendor/jquery-ui/jquery-ui.js',
		'vendor/jqueryui-touch-punch/jquery.ui.touch-punch.js',
		'vendor/jquery-appear/jquery.appear.js',
		'vendor/bootstrapv5-multiselect/js/bootstrap-multiselect.js',
		'vendor/jquery.easy-pie-chart/jquery.easypiechart.js',
		'vendor/flot/jquery.flot.js',
		'vendor/flot.tooltip/jquery.flot.tooltip.js',
		'vendor/flot/jquery.flot.pie.js',
		'vendor/flot/jquery.flot.categories.js',
		'vendor/flot/jquery.flot.resize.js',
		'vendor/jquery-sparkline/jquery.sparkline.js',
		'vendor/raphael/raphael.js',
		'vendor/morris/morris.js',
		'vendor/gauge/gauge.js',
		'vendor/snap.svg/snap.svg.js',
		'vendor/liquid-meter/liquid.meter.js',
		'vendor/jqvmap/jquery.vmap.js',
		'vendor/jqvmap/data/jquery.vmap.sampledata.js',
		'vendor/jqvmap/maps/jquery.vmap.world.js',
		'vendor/jqvmap/maps/continents/jquery.vmap.africa.js',
		'vendor/jqvmap/maps/continents/jquery.vmap.asia.js',
		'vendor/jqvmap/maps/continents/jquery.vmap.australia.js',
		'vendor/jqvmap/maps/continents/jquery.vmap.europe.js',
		'vendor/jqvmap/maps/continents/jquery.vmap.north-america.js',
		'vendor/jqvmap/maps/continents/jquery.vmap.south-america.js',
		'vendor/owl.carousel/owl.carousel.js',
		'js/theme.js',
		'js/custom.js',
		'js/theme.init.js',
		'js/examples/examples.dashboard.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
        'yii\bootstrap5\BootstrapPluginAsset',
    ];
}
