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
class AdminCoreAsset extends AssetBundle
{
	public $sourcePath = '@app/themes/admin_core';
	public $css = [
		'//fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap',
		'assets/vendors/feather/feather.css',
		'assets/vendors/mdi/css/materialdesignicons.min.css',
		'assets/vendors/ti-icons/css/themify-icons.css',
		'assets/vendors/font-awesome/css/font-awesome.min.css',
		'assets/vendors/typicons/typicons.css',
		'assets/vendors/simple-line-icons/css/simple-line-icons.css',
		'assets/vendors/css/vendor.bundle.base.css',
		'assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css',
		'assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css',
		'assets/vendors/select2/select2.min.css',
		'assets/font-awesome/css/all.min.css',
		// 'assets/vendors/boxicons/css/boxicons.min.css',
		// 'assets/fonts/boxicons.css',
		// 'assets/fonts/boxicons.eot',
		// 'assets/fonts/boxicons.ttf',
		// 'assets/fonts/boxicons.woff',
		// 'assets/fonts/boxicons.woff2',
		// 'assets/vendor/fonts/boxicons.css',
		'assets/js/select.dataTables.min.css',
		'assets/css/style.css',
		// 'assets/css/light/common.css',
		// 'docs/style.css',
	];
	public $js = [
		'assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js',
		'assets/vendors/chart.js/chart.umd.js',
		'assets/vendors/progressbar.js/progressbar.min.js',
		'assets/js/jquery.cookie.js',
		// 'assets/js/chart.js',
		// 'assets/js/Chart.roundedBarCharts.js',
		// 'assets/js/codemirror.js',
		// 'assets/js/file-upload.js',
		'assets/js/hoverable-collapse.js',
		// 'assets/js/jquery-file-upload.js',
		// 'assets/js/jquery.cookie.js',
		'assets/js/off-canvas.js',
		// 'assets/js/select2.js',
		'assets/js/settings.js',
		'assets/js/template.js',
		'assets/js/todolist.js',
		'assets/js/cleave.js',
		// 'assets/vendors/js/vendor.bundle.base.js',
		// 'assets/js/typeahead.js',
		// 'docs/script.js',
	];

	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap5\BootstrapAsset',
		'yii\bootstrap5\BootstrapPluginAsset',
	];
}
