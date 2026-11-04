<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Menu;
use app\widgets\Alert;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\ArrayHelper;
use app\assets\BoxiconsAsset;
use app\components\MenuFront;
use app\assets\AdminCoreAsset;
use app\assets\FooterLogosAsset;
use yii\bootstrap5\Breadcrumbs;
use app\assets\BetterFrontAsset;
use app\modules\references\References;
use app\modules\hitcounter\models\HitCounter;
use app\modules\app_setting\models\App_setting;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Content;

BetterFrontAsset::register($this);
$footerLogosAsset = FooterLogosAsset::register($this);
// AdminCoreAsset::register($this);
BoxiconsAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Masjaki Surakarta </title>
  <link rel="shortcut icon" href="/images/logoska.png" />
  <?php $this->registerCsrfMetaTags() ?>
  <?php $this->head() ?>
</head>
<?php
$global_setting_db = App_setting::find()->select('key, value')->all();
$global_setting = ArrayHelper::map($global_setting_db, 'key', 'value');
// $company_name = $global_setting['company_name'];
// $company_email = $global_setting['company_email'];
$moduleUser = \Yii::$app->getModule('user'); // find user module setting
$moduleYear = \Yii::$app->getModule('references'); // find user module setting
$regcheck = $moduleUser->enableRegistration; // if true, user registration button and access are enabled
$logincheck = Yii::$app->params['enableLogin']; // if true, user registration button and access are enabled
$user = Yii::$app->user->identity;
$menuItems = MenuFront::setMenu('better_front');
?>
<?php $this->beginBody() ?>

<body class="index-page">
  <!-- <div class="container"> -->
  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container position-relative d-flex align-items-center header-container">

      <a href="/" class="logo d-flex align-items-center" style="margin-right: 12px">
        <img src="/images/logo-ska.png" alt="">
        <h1 class="sitename" style="color: var(--bs-primary);">Masjaki Surakarta</h1>

      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="/"><?= Yii::t('app', 'Home') ?></a></li>
          <li><a href="/pbj"><?= Yii::t('rup', 'Construction Work') ?></a></li>
          <li><a href="/peraturan-jasa-konstruksi">Peraturan Jasa Konstruksi</a></li>
          <li><a href="/rekap-sertifikasi-tkk">Rekap Sertifikasi TKK</a></li>
          <li><a href="/rekap-pembinaan">Rekap Pembinaan</a></li>
          <li><a href="/rekap-pengawasan-tertib">Rekap Pengawasan</a></li>
          <li><a href="/cons-market"><?= Yii::t('app', 'Construction Market') ?></a></li>
          <li><a href="/berita"><?= Yii::t('app', 'News') ?></a></li>
          <!-- untuk kebutuhan mobile view -->
          <?php if (Yii::$app->user->isGuest) : ?>
            <li class="hidden"><a href="/new-request/select"><?= Yii::t('user', 'Register') ?></a></li>
            <li class="hidden"><a href="/user/security/login"><?= Yii::t('user', 'Sign In') ?></a></li>
          <?php endif; ?>
          <?php if (!Yii::$app->user->isGuest) : ?>
            <li class="hidden"><a href="<?= Yii::$app->urlManager->createUrl(['/admin/index-core']) ?>">
                <?= Yii::t('user', 'Halaman Admin') ?></a></li>
          <?php endif; ?>
          <!-- -->
        </ul>
        <i class="mobile-nav-toggle d-xxl-none bi bi-list"></i>
      </nav>
      <?php if (Yii::$app->user->isGuest) : ?>
        <div class="header-buttons">
          <a class="btn btn-outline-danger me-3" href="/new-request/select"><?= Yii::t('user', 'Register') ?></a>
          <a class="btn btn-danger" href="/user/security/login"><?= Yii::t('user', 'Sign In') ?></a>
        </div>
        <!-- <php else : ?>
          <div class="header-buttons d-flex align-items-center">
            <a class="btn btn-outline-danger" href="<= Yii::$app->urlManager->createUrl(['/site/logout']) ?>" data-method="post">
              <= Yii::t('user', 'Keluar ({0}) "{1}"', [
                Yii::$app->user->identity->username,
                // Yii::$app->user->identity->role,
              ]) ?>
            </a>
          </div> -->
      <?php endif; ?>
      <?php if (!Yii::$app->user->isGuest) : ?>
        <div class="header-buttons">
          <a class="btn btn-outline-danger" href="<?= Yii::$app->urlManager->createUrl(['/admin/index-core']) ?>">
            <?= Yii::t('user', 'Halaman Admin') ?>
          </a>
        </div>
      <?php endif; ?>

    </div>
  </header>

  <div class="row ms-5 me-5" style="padding: 10px">
    <?= Alert::widget() ?>
  </div>
  <?= $content ?>

  <footer id="footer" class="footer dark-background">
    <div class="container footer-content">
      <div class="footer-logos footer-logos-left">
        <span class="footer-logo-badge footer-logo-pemkot">
          <img src="<?= Html::encode($footerLogosAsset->baseUrl . '/logo-pemkot-footer.png') ?>" alt="Lambang Pemerintah Kota Surakarta">
        </span>
        <span class="footer-logo-badge footer-logo-dpupr">
          <img src="<?= Html::encode($footerLogosAsset->baseUrl . '/logo-dpupr-footer.png') ?>" alt="Logo Dinas Pekerjaan Umum dan Penataan Ruang Kota Surakarta">
        </span>
      </div>
      <?php
      $key = "hitcountthismonth";
      $cache = Yii::$app->cache;
      $hitsPerMonth = $cache->getOrSet($key, function () {
        return HitCounter::find()->select('cookie_mark')->distinct()
          ->where(['>=', 'created_at', date('Y-m-01')])
          ->andWhere(['<=', 'created_at', date('Y-m-t')])
          ->count();
      }, 3600);
      ?>
      <div class="footer-copy">
        &copy; Copyright <script>
          document.write(new Date().getFullYear() + ' ');
        </script> version <?= Yii::$app->params['version'] ?>. All Rights Reserved.
        <p>Pengunjung Bulan Ini : <?= $hitsPerMonth ?> orang
        </p>
      </div>
      <div class="footer-logos footer-logos-right">
        <span class="footer-logo-badge footer-logo-berakhlak">
          <img src="<?= Html::encode($footerLogosAsset->baseUrl . '/logo-berakhlak-footer.png') ?>" alt="Logo nilai dasar ASN BerAKHLAK">
        </span>
        <span class="footer-logo-badge footer-logo-bangga">
          <img src="<?= Html::encode($footerLogosAsset->baseUrl . '/logo-bangga-footer.png') ?>" alt="Logo Bangga Melayani Bangsa">
        </span>
      </div>
    </div>
  </footer>
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>
  <div id="preloader"></div>

  <!-- </div> -->
</body>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const currentPath = window.location.pathname;
    const menuItems = document.querySelectorAll(".navmenu ul li a");

    menuItems.forEach(function(menuItem) {
      const href = menuItem.getAttribute("href");

      if (href === "/" && currentPath === "/") {
        menuItem.classList.add("active");
      } else if (href !== "/" && currentPath.startsWith(href)) {
        menuItem.classList.add("active");
      }
    });
  });
</script>

<?php $this->endBody() ?>

</html>
<?php $this->endPage() ?>
