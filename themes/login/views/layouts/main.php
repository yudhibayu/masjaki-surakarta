<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\widgets\Menu;
use yii\bootstrap5\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Nav;
use app\widgets\Alert;
use app\modules\app_setting\models\App_setting;
use app\assets\AdminPortoAsset;
use app\modules\references\References;
use yii\helpers\Url;

// use Yii;

AdminPortoAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html class="fixed" lang="<?= Yii::$app->language ?>">

<head>
  <meta charset="<?= Yii::$app->charset ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="keywords" content="HTML5 Admin Template" />
  <meta name="description" content="Porto Admin - Responsive HTML5 Template">
  <meta name="author" content="okler.net">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title><?= Html::encode($this->title) ?></title>
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">
  <link rel="icon" type="image/png" href="/images/logoska.png">
  <?php $this->registerCsrfMetaTags() ?>
  <?php $this->head() ?>
</head>
<?php
// $global_setting_db = App_setting::find()->select('key, value')->all();
// $global_setting = ArrayHelper::map($global_setting_db, 'key', 'value');
// $company_name = $global_setting['company_name'];
// $company_email = $global_setting['company_email'];
// $moduleUser = \Yii::$app->getModule('user'); // find user module setting
// $moduleYear = \Yii::$app->getModule('references'); // find user module setting
// $regcheck = $moduleUser->enableRegistration; // if true, user registration button and access are enabled
// $logincheck = Yii::$app->params['enableLogin']; // if true, user registration button and access are enabled
// $user = Yii::$app->user->identity;
?>
<?php $this->beginBody() ?>

<body>
  <div class="row ms-5 me-5" style="padding: 10px">
    <?= Alert::widget() ?>
  </div>
  <?= $content ?>

</body>
<?php $this->endBody() ?>

</html>
<?php $this->endPage() ?>