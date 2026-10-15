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
class BoxiconsAsset extends AssetBundle
{
	public $sourcePath = '@app/vendor/npm-asset/boxicons/';
	public $css = [
		// '//fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap',
		// 'npm-asset/boxicons/css/boxicons.css',
		'css/boxicons.min.css',
		'fonts/boxicons.eot',
		'fonts/boxicons.ttf',
		'fonts/boxicons.woff',
		'fonts/boxicons.woff2',
		'fonts/boxicons.svg',
	];
	// public $js = [
	// ];

	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap5\BootstrapAsset',
		'yii\bootstrap5\BootstrapPluginAsset',
	];
}
