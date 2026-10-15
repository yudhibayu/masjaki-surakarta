<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Menu;
use app\widgets\Alert;
use yii\bootstrap5\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap5\Breadcrumbs;
use app\assets\StandardFrontAsset;
use app\components\MenuFront;
use yii\helpers\Url;

StandardFrontAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <link rel="icon" type="image/png" href="/images/logo-ska.png">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

</head>

<?php $this->beginBody() ?> <?php
$moduleUser = \Yii::$app->getModule('user'); // find user module setting
$moduleYear = \Yii::$app->getModule('references'); // find user module setting
$regcheck = $moduleUser->enableRegistration; // if true, user registration button and access are enabled
$logincheck = Yii::$app->params['enableLogin']; // if true, user registration button and access are enabled
?>

<body>

    <!-- ======= Top Bar ======= -->
    <section id="topbar" class="fixed-top d-flex align-items-center">
        <div class="container d-flex justify-content-center justify-content-md-between">
            <div class="contact-info d-flex align-items-center">
                <i class="bi bi-envelope d-flex align-items-center"><a href="mailto:adm-pembangunan@surakarta.go.id">adm-pembangunan@surakarta.go.id</a></i>
            </div>
            <div class="social-links d-none d-md-flex">
                <span>No. Telp: &nbsp;0271-635964</span>
            </div>
        </div>
    </section>

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top d-flex align-items-center">
        <div class="container d-flex align-items-center justify-content-between">

            <div class="logo">
                <!-- <h1><a href="index.html">My<span>Biz</span></a></h1> -->
                <!-- Uncomment below if you prefer to use an image logo -->
                <a href="/" class="logo"><img src="/images/apsimonela1.png" alt="" width="100px" class="img-fluid"></a>

            </div>

            <nav id="navbar" class="navbar">
                <?php echo Menu::widget(MenuFront::setMenu(Yii::$app->params['frontEndTheme'])); ?>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav><!-- .navbar -->

        </div>
    </header><!-- End Header -->

    <!--== End Header Area Wrapper ==-->
    <!-- <div class="breadcrumb-background">
        <= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
    </div>
    <div class="row" style="padding: 10px">
        <= Alert::widget() ?>
    </div> -->
    <?= $content ?>
    <!--== Start Footer Area Wrapper ==-->
    <!-- ======= Footer ======= -->
    <footer id="footer">
        <div class="container">
            <!-- <div class="social-links">
                <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
                <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
                <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
                <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
                <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
            </div> -->
            <div class="copyright">
                &copy; Copyright <script>
                    document.write(new Date().getFullYear() + ' ');
                </script> version <?= Yii::$app->params['version'] ?>. All Rights Reserved.
            </div>
            <div class="credits">
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you purchased the pro version. -->
                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/family-multipurpose-html-bootstrap-template-free/ -->
                <!-- Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> -->
            </div>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

</body>

<?php $this->endBody() ?>

</html>
<?php $this->endPage() ?>