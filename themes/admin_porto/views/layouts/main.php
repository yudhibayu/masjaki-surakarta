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
use app\assets\EmergencyAsset;
use app\models\UserSatker;
use app\modules\references\References;
use yii\helpers\Url;

// use Yii;

AdminPortoAsset::register($this);
EmergencyAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html class="fixed header-dark" lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="HTML5 Admin Template" />
    <meta name="description" content="Porto Admin - Responsive HTML5 Template">
    <meta name="author" content="okler.net">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title><?= Html::encode($this->title) ?></title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">
    <link rel="icon" type="image/png" href="/images/logo-ska.png">
    <?php $this->registerCsrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<?php
$global_setting_db = App_setting::find()->select('key, value')->all();
$global_setting = ArrayHelper::map($global_setting_db, 'key', 'value');
$company_name = $global_setting['company_name'];
$company_email = $global_setting['company_email'];
$moduleUser = \Yii::$app->getModule('user'); // find user module setting
$moduleYear = \Yii::$app->getModule('references'); // find user module setting
$regcheck = $moduleUser->enableRegistration; // if true, user registration button and access are enabled
$logincheck = Yii::$app->params['enableLogin']; // if true, user registration button and access are enabled
$user = Yii::$app->user->identity;
$dataunit = '';
if (Yii::$app->session->has('milestone')) {
    if (!Yii::$app->user->can('Team') && Yii::$app->user->can('OPD')) {
        $dataunit = UserSatker::find()->where(['user_id' => $user, 'fiscal_year' => Yii::$app->session['milestone']['year_name']])->one();
    }
}
?>
<?php $this->beginBody() ?>


<?php if ($user) { ?>

    <body>
        <section class="body">

            <!-- start: header -->
            <header class="header">
                <div class="logo-container">
                    <a href="<?= Yii::$app->params['homeUrl'] ?>" class="logo">
                        <img src="/images/logo-ska.png" height="40" alt="Porto Admin" />
                    </a>

                    <div class="d-md-none toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
                        <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
                    </div>

                </div>

                <div class="header-right">

                    <span class="separator"></span>

                    <div id="userbox" class="userbox">
                        <a href="#" data-bs-toggle="dropdown">
                            <figure class="profile-picture">
                                <?php if ($user->profile) : ?>
                                    <img src="/uploads/propic/<?= $user->profile->file_name ?>" class="rounded-circle" alt="user_profile_pic">
                                <?php else : ?>
                                    <img src="/images/blank-propic.png" class="rounded-circle" alt="user_profile_pic">
                                <?php endif; ?>
                                <!-- <img src="img/!logged-user.jpg" alt="Joseph Doe" class="rounded-circle" data-lock-picture="img/!logged-user.jpg" /> -->
                            </figure>
                            <div class="profile-info" data-lock-name="John Doe" data-lock-email="johndoe@okler.com">
                                <span class="name"><?= Yii::$app->user->identity->username; ?></span>
                                <span class="role"><?php echo $user->roleName; ?></span>
                            </div>

                            <i class="fa custom-caret"></i>
                        </a>

                        <div class="dropdown-menu">
                            <ul class="list-unstyled mb-2">
                                <li class="divider"></li>
                                <li>
                                    <a role="menuitem" tabindex="-1" href="/user/settings/profileadmin"><i class="bx bx-user-circle"></i> My Profile</a>
                                </li>
                                <li>
                                    <a role="menuitem" tabindex="-1" href="/user/security/logout" data-method="post"><i class="bx bx-power-off"></i> Logout</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>

            <div class="inner-wrapper">
                <!-- start: sidebar -->
                <aside id="sidebar-left" class="sidebar-left">

                    <div class="sidebar-header">
                        <div class="sidebar-title">
                            <?= Yii::t('app', 'Navigation') ?>
                        </div>
                        <div class="sidebar-toggle d-none d-md-block" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
                            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
                        </div>
                    </div>

                    <div class="nano">
                        <div class="nano-content">
                            <nav id="menu" class="nav-main" role="navigation">

                                <ul class="nav nav-main">


                                    <?php if (Yii::$app->user->can('Super Administrator')) : ?>
                                        <!-- s-masjaki -->
                                        <h6 class="menu-section"><?= Yii::t('app', 'Masjaki'); ?></h6>

                                        <li class="nav-parent">
                                            <a class="nav-link" href="#">
                                                <i class="bx bx-data" aria-hidden="true"></i>
                                                <span><?= Yii::t('app', 'Master Data') ?> Masjaki</span>
                                            </a>
                                            <ul class="nav nav-children">
                                                <li>
                                                    <a class="nav-link" href="/references-masjaki/news">
                                                        <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('app', 'News') ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="nav-link" href="/references-masjaki/lpse">
                                                        <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('app', 'LPSE') ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="nav-link" href="/references-masjaki/form-of-business">
                                                        <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('app', 'Form of Business') ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="nav-link" href="/references-masjaki/type-of-business-license">
                                                        <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('app', 'Type of Business License') ?>
                                                    </a>
                                                </li>
                                                <!-- ------ -->
                                                <li>
                                                    <a class="nav-link" href="/references-masjaki/construction-sbu">
                                                        <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('app', 'Construction SBU') ?>
                                                    </a>
                                                </li>

                                                <li>
                                                    <a class="nav-link" href="/references-masjaki/kbli-2020">
                                                        <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('app', 'KBLI 2020') ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="nav-link" href="/references-masjaki/ownership-type">
                                                        <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('app', 'Ownership Type') ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="nav-link" href="/references-masjaki/type-of-management">
                                                        <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('app', 'Type of Management') ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="nav-link" href="/references-masjaki/education">
                                                        <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('app', 'Education') ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="nav-link" href="/references-masjaki/skills">
                                                        <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('app', 'Skills') ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="nav-link" href="/references-masjaki/source-of-funds">
                                                        <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('app', 'Source of funds') ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a class="nav-link" href="/business-entity/business-entity">
                                                <i class="bx bx-home-alt" aria-hidden="true"></i>
                                                <span><?= Yii::t('app', 'Business Entity') ?></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-link" href="/construction/labor">
                                                <i class="bx bx-home-alt" aria-hidden="true"></i>
                                                <span><?= Yii::t('app', 'Labor') ?></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-link" href="/construction/construction-work">
                                                <i class="bx bx-home-alt" aria-hidden="true"></i>
                                                <span><?= Yii::t('app', 'Construction Work') ?></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-link" href="/construction/construction-vacancies">
                                                <i class="bx bx-home-alt" aria-hidden="true"></i>
                                                <span><?= Yii::t('app', 'Construction Vacancies') ?></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-link" href="/construction/work-training">
                                                <i class="bx bx-home-alt" aria-hidden="true"></i>
                                                <span><?= Yii::t('app', 'Work Training') ?></span>
                                            </a>
                                        </li>
                                        <!-- e-masjaki -->

                                    <?php endif ?>

                                    <h6 class="menu-section"><?= Yii::t('app', 'Simonela'); ?></h6>


                                    <li>
                                        <a class="nav-link" href="/admin">
                                            <i class="bx bx-home-alt" aria-hidden="true"></i>
                                            <span><?= Yii::t('app', 'Dashboard') ?></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="nav-link" href="/admin/year">
                                            <i class="bx bx-sitemap" aria-hidden="true"></i>
                                            <span><?= Yii::t('app', 'Set Year'); ?></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="nav-link" href="/front-map">
                                            <i class="bx bx-map" aria-hidden="true"></i>
                                            <span><?= Yii::t('app', 'Map'); ?></span>
                                        </a>
                                    </li>
                                    <?php if (Yii::$app->session->has('satker')) : ?>
                                        <li><a style="font-weight:bold;" href="#"><span><?= Yii::t('app', 'Satker: '); ?>
                                                    <?= isset(Yii::$app->session['satker']['satker_name']) ? Yii::$app->session['satker']['satker_name'] : '' ?></span></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if (isset(Yii::$app->session['year']['name'])) { ?>
                                        <li><a style="font-weight:bold;" href="#"><span><?= Yii::t('app', 'Year:'); ?>
                                                    <?= Yii::$app->session['year']['name'] ?></span></a></li>
                                        <h6 class="menu-section"><?= Yii::t('app', 'Menu'); ?></h6>
                                        <?php if (Yii::$app->user->can('Administrator')) : ?>
                                            <li class="nav-parent">
                                                <a class="nav-link" href="#">
                                                    <i class="bx bx-data" aria-hidden="true"></i>
                                                    <span><?= Yii::t('app', 'Master Data') ?></span>
                                                </a>
                                                <ul class="nav nav-children">
                                                    <li>
                                                        <a class="nav-link" href="/references/object-type">
                                                            <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('app', 'Object Types') ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="nav-link" href="/references/kecamatan">
                                                            <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('app', 'Kecamatans') ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="nav-link" href="/references/kelurahan">
                                                            <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('app', 'Kelurahans') ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="nav-link" href="/references/tagging">
                                                            <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('app', 'Taggings') ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="nav-link" href="/references/satuan">
                                                            <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('app', 'Satuans') ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="nav-link" href="/references/cuaca">
                                                            <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('app', 'Cuacas') ?>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>

                                        <?php endif; ?>
                                        <?php if (Yii::$app->user->can('Administrator')) : ?>
                                            <li class="nav-parent">
                                                <a class="nav-link" href="#">
                                                    <i class="bx bx-data" aria-hidden="true"></i>
                                                    <span><?= Yii::t('rup', 'Master Rup & Pbj') ?></span>
                                                </a>
                                                <ul class="nav nav-children">
                                                    <li>
                                                        <a class="nav-link" href="/rup">
                                                            <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('rup', 'Rups') ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="nav-link" href="/rup/pbj">
                                                            <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('rup', 'Pbjs') ?>
                                                        </a>
                                                    </li>
                                                    <?php if (Yii::$app->user->can('Administrator')) : ?>
                                                        <li>
                                                            <a class="nav-link" href="/rup/metode-pengadaan">
                                                                <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('rup', 'Metode Pengadaan') ?>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="nav-link" href="/rup/jenis-pengadaan">
                                                                <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('rup', 'Jenis Pengadaan') ?>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="nav-link" href="/rup/program">
                                                                <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('rup', 'Program') ?>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="nav-link" href="/rup/kegiatan">
                                                                <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('rup', 'Kegiatan') ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (Yii::$app->user->can('OPD')) : ?>
                                            <?php if ($dataunit) { ?>
                                                <li class="nav-parent">
                                                    <a class="nav-link" href="#">
                                                        <i class="bx bx-data" aria-hidden="true"></i>
                                                        <span><?= Yii::t('rup', 'Master Rup & Pbj') ?></span>
                                                    </a>
                                                    <ul class="nav nav-children">
                                                        <li>
                                                            <a class="nav-link" href="/rup">
                                                                <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('rup', 'Rups') ?>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="nav-link" href="/rup/pbj">
                                                                <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('rup', 'Pbjs') ?>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            <?php } ?>
                                        <?php endif; ?>
                                        <?php if (Yii::$app->user->can('Administrator')) : ?>
                                            <li class="nav-parent">
                                                <a class="nav-link" href="#">
                                                    <i class="bx bx-body" aria-hidden="true"></i>
                                                    <span><?= Yii::t('app', 'Entities') ?></span>
                                                </a>
                                                <ul class="nav nav-children">
                                                    <li>
                                                        <a class="nav-link" href="/team">
                                                            <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('app', 'Team') ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="nav-link" href="/account-request">
                                                            <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('app', 'Account Request') ?>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (Yii::$app->session->has('team')) : ?>
                                            <li><a style="font-weight:bold; text-transform:uppercase;" href="#"><span><?= Yii::t('app', 'Team: '); ?>
                                                        <?= Yii::$app->session['team']['team_name'] ?></span></a></li>
                                        <?php endif; ?>
                                        <?php if (Yii::$app->user->can('OPD')) {
                                            if ($dataunit) { ?>
                                                <li>
                                                    <a class="nav-link" href="/monitoring">
                                                        <i class="bx bx-line-chart" aria-hidden="true"></i>
                                                        <span><?= Yii::t('app', 'Monitoring') ?></span>
                                                    </a>
                                                </li>
                                        <?php }
                                        } ?>

                                        <?php if (Yii::$app->user->can('Administrator')) : ?>
                                            <li>
                                                <a class="nav-link" href="/monitoring">
                                                    <i class="bx bx-line-chart" aria-hidden="true"></i>
                                                    <span><?= Yii::t('app', 'Monitoring') ?></span>
                                                </a>
                                            </li>
                                            <li class="nav-parent">
                                                <a class="nav-link" href="#">
                                                    <i class="bx bx-grid" aria-hidden="true"></i>
                                                    <span><?= Yii::t('app', 'Technical Specs') ?></span>
                                                </a>
                                                <ul class="nav nav-children">
                                                    <li>
                                                        <a class="nav-link" href="/technicalspec">
                                                            <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('app', 'DEDs') ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="nav-link" href="/technicalspec/component-category">
                                                            <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('technicalspec', 'Component Category') ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="nav-link" href="/technicalspec/component">
                                                            <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('technicalspec', 'Component') ?>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li>
                                                <a class="nav-link" href="/monitoring/recap">
                                                    <i class="bx bx-list-ul" aria-hidden="true"></i>
                                                    <span><?= Yii::t('monitoring', 'Recap Monitoring') ?></span>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if ((!Yii::$app->user->can('OPD') && Yii::$app->user->can('Planner')) || (!Yii::$app->user->can('Planner') && Yii::$app->user->can('Supervisor'))) : ?>
                                            <li>
                                                <a class="nav-link" href="/monitoring">
                                                    <i class="bx bx-line-chart" aria-hidden="true"></i>
                                                    <span><?= Yii::t('app', 'Monitoring') ?></span>
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if (!Yii::$app->user->can('Supervisor') && Yii::$app->user->can('Eksekutor')) : ?>
                                            <li>
                                                <a class="nav-link" href="/monitoring">
                                                    <i class="bx bx-line-chart" aria-hidden="true"></i>
                                                    <span><?= Yii::t('app', 'Monitoring') ?></span>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (Yii::$app->user->can('OPD')) {
                                            if ($dataunit) { ?>
                                                <li class="nav-parent">
                                                    <a class="nav-link" href="#">
                                                        <i class="bx bx-grid" aria-hidden="true"></i>
                                                        <span><?= Yii::t('app', 'Technical Specs') ?></span>
                                                    </a>
                                                    <ul class="nav nav-children">
                                                        <li>
                                                            <a class="nav-link" href="/monitoring/recap/index">
                                                                <i class="bx bx-list-ul" aria-hidden="true"></i>
                                                                <span><?= Yii::t('monitoring', 'Recap Monitoring') ?></span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                        <?php }
                                        } ?>
                                        <?php if (Yii::$app->user->can('Super Administrator')) : ?>

                                            <h6 class="menu-section"><?= Yii::t('app', 'App Setting'); ?></h6>
                                            <li class="nav-parent">
                                                <a class="nav-link" href="#">
                                                    <i class="fa fa-cogs" aria-hidden="true"></i>
                                                    <span><?= Yii::t('app', 'Setting') ?></span>
                                                </a>
                                                <ul class="nav nav-children">
                                                    <li>
                                                        <a class="nav-link" href="/app_setting">
                                                            <i class="bx bx-cog" aria-hidden="true"></i><?= Yii::t('app', 'Global Application Setting') ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="nav-link" href="/user/admin">
                                                            <i class="fas fa-users" aria-hidden="true"></i><?= Yii::t('user', 'User Setting') ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="nav-link" href="<?= Url::to(['/data-sync'], true) ?>">
                                                            <i class="fas fa-sync" aria-hidden="true"></i><?= Yii::t('app', 'Sync Data') ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="nav-link" href="<?= Url::to(['/backups'], true) ?>">
                                                            <i class="fas fa-database" aria-hidden="true"></i><?= Yii::t('app', 'Backup') ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="nav-link" href="<?= Url::to(['/log-reader'], true) ?>">
                                                            <i class="fas fa-history" aria-hidden="true"></i>Log Reader
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>

                                            <li class="nav-parent">
                                                <a class="nav-link" href="#">
                                                    <i class="bx bx-hourglass" aria-hidden="true"></i>
                                                    <span><?= Yii::t('app', 'Milestone') ?></span>
                                                </a>
                                                <ul class="nav nav-children">
                                                    <li>
                                                        <a class="nav-link" href="/references/year">
                                                            <i class="bx bx-calendar" aria-hidden="true"></i><?= References::t('year_lang', 'Fiscal Year Setting') ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="nav-link" href="/user-satker">
                                                            <i class="bx bx-user-circle" aria-hidden="true"></i><?= Yii::t('app', 'User and OPD') ?>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>

                                            <li class="nav-parent">
                                                <a class="nav-link" href="#">
                                                    <i class="bx bx-book-content" aria-hidden="true"></i>
                                                    <span><?= Yii::t('app', 'SOT & OPD') ?></span>
                                                </a>
                                                <ul class="nav nav-children">
                                                    <li>
                                                        <a class="nav-link" href="/manage_sot">
                                                            <i class="bx bx-book" aria-hidden="true"></i><?= Yii::t('app', 'SOTK') ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="nav-link" href="/satker">
                                                            <i class="bx bx-list-ul" aria-hidden="true"></i><?= Yii::t('app', 'Satker') ?>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>

                                        <?php endif; ?>
                                    <?php } ?>
                                </ul>
                            </nav>

                            <hr class="separator" />

                        </div>

                        <script>
                            if (typeof localStorage !== 'undefined') {
                                if (localStorage.getItem('sidebar-left-position') !== null) {
                                    var initialPosition = localStorage.getItem('sidebar-left-position'),
                                        sidebarLeft = document.querySelector('#sidebar-left .nano-content');

                                    sidebarLeft.scrollTop = initialPosition;
                                }
                            }
                        </script>


                    </div>

                </aside>

                <section role="main" class="content-body">

                    <header class="page-header">
                    </header>
                    <div class="breadcrumb-background">
                        <?= Breadcrumbs::widget([
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        ]) ?>
                    </div>
                    <div class="row" style="padding: 10px">
                        <?= Alert::widget() ?>
                    </div>
                    <?= $content ?>
                </section>
            </div>

        </section>
    </body>
<?php } else { ?>

    <body style="background-image: url(<?= Yii::getAlias('@web') ?>/images/balkotsolo.jpg);background-repeat: no-repeat;background-size: cover;background-position: center;">
        <section class="body-sign">
            <div class="center-sign">
                <a href="/" class="logo float-left">
                    <img style="display: block;
  margin-left: auto;
  margin-right: auto;" src="/images/logo-ska.png" height="90" alt="Porto Admin" />
                </a>

                <div class="panel card-sign">
                    <div class="card-title-sign mt-3 text-end">
                        <!-- <h2 class="title text-uppercase font-weight-bold m-0"><i class="bx bx-user-circle me-1 text-6 position-relative top-5"></i> <= Yii::t('user', 'Sign in') ?></h2> -->
                    </div>
                    <div class="card-body">
                        <!-- <h4 class="text-uppercase font-weight-bold">
              <center>
                Login</center>
            </h4> -->
                        <!-- <div class="breadcrumb-background">
              <= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
              ]) ?>
            </div> -->
                        <div class="row" style="padding: 10px">
                            <?= Alert::widget() ?>
                        </div>
                        <?= $content ?>
                    </div>
                </div>

                <p class="text-center mt-3 mb-3" style="color: #fff"> &copy; Copyright <script>
                        document.write(new Date().getFullYear() + ' ');
                    </script> version <?= Yii::$app->params['version'] ?>. All Rights Reserved.</p>
            </div>
        </section>
    </body>
<?php } ?>


<?php $this->endBody() ?>

</html>
<?php $this->endPage() ?>