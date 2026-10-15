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
class BetterFrontAsset extends AssetBundle
{
	public $sourcePath = '@app/themes/better_front';
	public $css = [
		'//fonts.googleapis.com/css?family=Poppins:300,400,400i,500,600,700%7CPlayfair+Display:400,400i%7CDancing+Script:400,700',
		'//fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap',
		'assets/vendor/bootstrap/css/bootstrap.min.css',
		'assets/vendor/bootstrap-icons/bootstrap-icons.css',
		'assets/vendor/aos/aos.css',
		'assets/vendor/glightbox/css/glightbox.min.css',
		'assets/vendor/swiper/swiper-bundle.min.css',
		'assets/css/main.css',
	];
	public $js = [
		'assets/js/main.js',
		'assets/vendor/bootstrap/js/bootstrap.bundle.min.js',
		'assets/vendor/php-email-form/validate.js',
		'assets/vendor/aos/aos.js',
		'assets/vendor/glightbox/js/glightbox.min.js',
		'assets/vendor/purecounter/purecounter_vanilla.js',
		'assets/vendor/swiper/swiper-bundle.min.js',
		'assets/js/cleave.js',
	];

	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap5\BootstrapAsset',
		'yii\bootstrap5\BootstrapPluginAsset',
	];
}
