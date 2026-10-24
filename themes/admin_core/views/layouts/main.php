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
use app\assets\AdminCoreAsset;
use app\assets\BoxiconsAsset;
use app\modules\references\References;
use yii\helpers\Url;

// use Yii;

AdminCoreAsset::register($this);
BoxiconsAsset::register($this);
?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="en" class="dark-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="assets/"
    data-template="vertical-menu-template-no-customizer">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Masjaki Surakarta </title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="/images/logoska.png" />
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
?>
<style>
    .select2-container .select2-selection--single {
        box-sizing: border-box;
        cursor: pointer;
        display: block;
        height: 38px;
        user-select: none;
        -webkit-user-select: none;
    }

    .select2-container {
        box-sizing: border-box;
        display: block;
        margin: 0;
        position: relative;
    }
</style>
<?php $this->beginBody() ?>

<body class="with-welcome-text">
    <div class="container-scroller">
        <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                <div class="me-3">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button"
                        data-bs-toggle="minimize">
                        <span class="icon-menu"></span>
                    </button>
                </div>
                <div>
                    <a href="<?= Yii::$app->params['homeUrl'] ?>" class="navbar-brand brand-logo">
                        <img src="/images/logo-ska.png" height="40" alt="Porto Admin" class="me-3" />
                        <div class="brand-text justify-content-start">
                            <p class="brand-name align-item-start">Masjaki</p>
                            <p class="brand-location">Kota Surakarta</p>
                        </div>
                    </a>
                    <a class="navbar-brand brand-logo-mini ms-1" href="<?= Yii::$app->params['homeUrl'] ?>">
                        <img src="/images/logo-ska.png" alt="logo" />
                    </a>
                </div>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-top">
                <ul class="navbar-nav ms-auto">
                    <!-- <li class="nav-item d-flex mt-2" style="align-items: center; justify-content:center;">
                        <div id="mode-switch-container">
                            <button id="mode-switch" onclick="toggleMode()"
                                style="border: none; border-radius: 10px; cursor: pointer;">
                                <i id="mode-icon" class='bx bx-sun' style="font-size: 20px;"></i>
                            </button>
                        </div>
                    </li> -->

                    <?php if ($user) { ?>
                        <li class="nav-item dropdown d-lg-block user-dropdown">
                            <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php if ($user->profile) : ?>
                                    <img class="img-xs rounded-circle"
                                        src="<?= Url::to(['/user/settings/uploaded-picture', 'data' => $user->profile->file_name]) ?>"
                                        alt="Profile image"> </a>
                        <?php else : ?>
                            <img class="img-xs rounded-circle" src="/images/blank-propic.png" alt="Profile image"> </a>
                        <?php endif; ?>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                            <div class="dropdown-header text-center">
                                <?php if ($user->profile) : ?>
                                    <img class="img-md rounded-circle"
                                        src="<?= Url::to(['/user/settings/uploaded-picture', 'data' => $user->profile->file_name]) ?>"
                                        alt="Profile image" width="50px">
                                <?php else : ?>
                                    <img class="img-md rounded-circle" src="/images/blank-propic.png" alt="Profile image"
                                        width="50px">
                                <?php endif; ?>
                                <p class="mb-1 mt-3 fw-semibold"><?= Yii::$app->user->identity->username; ?></p>
                                <p class="fw-light text-muted mb-0"><?php echo $user->roleName; ?></p>
                            </div>
                            <a class="dropdown-item" href="/user/settings/profileadmin"><i
                                    class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> My Profile
                            </a>
                            <a class="dropdown-item" href="/user/security/logout" data-method="post"><i
                                    class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</a>
                        </div>
                        </li>
                        <!-- <li class="nav-item dropdown d-lg-block user-dropdown">
                            <div id="userbox" class="userbox" style="position: relative; display: inline-block;">
                                <a href="#" data-bs-toggle="dropdown" onclick="toggleDropdown()">
                                    <div class="profile-info" data-lock-name="John Doe" data-lock-email="johndoe@okler.com"
                                        style="display: inline-block; vertical-align: middle; margin-left: 10px;">
                                        <span class="name"
                                            style="display: block; font-weight: bold;"><= Yii::$app->user->identity->username; ?></span>
                                        <span class="role"
                                            style="display: block; font-size: 12px; color: gray;"><php echo $user->roleName; ?></span>
                                    </div>
                                    <i class='bx bx-chevron-down' id="dropdownIcon"
                                        style="margin-left: 5px; transition: transform 0.3s;"></i>
                                </a>
                                <div class="dropdown-menu"
                                    style="display: none; position: absolute; top: 100%; left: 0; z-index: 1000; min-width: 160px; padding: 5px 0; margin: 2px 0 0; font-size: 14px; color: #333; text-align: left; list-style: none; background-color: #fff; background-clip: padding-box; border: 1px solid rgba(0, 0, 0, 0.15); border-radius: 4px;">
                                    <ul class="list-unstyled mb-2" style="margin: 0; padding: 0;">
                                        <li class="divider"
                                            style="height: 1px; margin: 9px 0; overflow: hidden; background-color: #e5e5e5;">
                                        </li>
                                        <li>
                                            <a role="menuitem" tabindex="-1" href="/user/settings/profileadmin"
                                                data-method="post"
                                                style="display: block; width: 100%; padding: 3px 20px; clear: both; font-weight: normal; color: #333; white-space: nowrap;">Settings</a>
                                        </li>
                                        <li>
                                            <a role="menuitem" tabindex="-1" href="/user/security/logout" data-method="post"
                                                style="display: block; width: 100%; padding: 3px 20px; clear: both; font-weight: normal; color: #333; white-space: nowrap;">Logout</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li> -->
                    <?php } ?>
                    <!-- <li class="nav-item dropdown d-lg-block user-dropdown">
                        <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <php if ($user->profile) : ?>
                                <img class="img-xs rounded-circle" src="/uploads/propic/<= $user->profile->file_name ?>" alt="Profile image">
                                <php else : ?>
                                    <img class="img-xs rounded-circle" src="/images/blank-propic.png" alt="Profile image">
                                    <php endif; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                            <div class="dropdown-header text-center">
                                <php if ($user->profile) : ?>
                                    <img class="img-md rounded-circle" src="/uploads/propic/<= $user->profile->file_name ?>" width="50px" alt="Profile image">
                                    <php else : ?>
                                        <img class="img-md rounded-circle" src="/images/blank-propic.png" alt="Profile image">
                                        <php endif; ?>
                                            <p class="mb-1 mt-3 fw-semibold">
                                                <= Yii::$app->user->identity->username; ?>
                                            </p>
                                            <p class="fw-light text-muted mb-0">
                                                <php echo $user->roleName; ?>
                                            </p>
                            </div>
                            <a class="dropdown-item" href="/user/settings/profileadmin"><i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i>Settings</a>
                            <a class="dropdown-item" href="/user/security/logout" data-method="post"><i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</a>
                        </div>
                    </li> -->
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center me-2" type="button"
                    data-bs-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/index-core">
                            <!-- <i class="mdi mdi-grid-large menu-icon"></i> -->
                            <i class="menu-icon bx bx-grid-alt"></i>
                            <span class="menu-title"><?= Yii::t('app', 'Dashboard') ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/year">
                            <!-- <i class="mdi mdi-grid-large menu-icon"></i> -->
                            <i class="menu-icon bx bx-calendar"></i>
                            <span class="menu-title"><?= Yii::t('app', 'Set Year') ?></span>
                        </a>
                    </li>
                    <?php if (Yii::$app->session->has('milestone')) { ?>
                        <?php if (Yii::$app->user->can('Administrator')) : ?>
                            <li class="nav-item nav-category">Master</li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false"
                                    aria-controls="ui-basic">
                                    <!-- <i class="menu-icon fa fa-gears"></i> -->
                                    <i class="menu-icon bx bx-data"></i>
                                    <span class="menu-title"><?= Yii::t('app', 'Master Data') ?> Masjaki</span>
                                    <i class="menu-arrow"></i>
                                </a>
                                <div class="collapse" id="ui-basic">
                                    <ul class="nav flex-column sub-menu">
                                        <li class="nav-item">
                                            <a class="nav-link" href="/references-masjaki/news">
                                                <?= Yii::t('app', 'News') ?>
                                            </a>
                                        </li>
                                        <!-- <li class="nav-item">
                                            <a class="nav-link" href="/references-masjaki/lpse">
                                                <= Yii::t('app', 'LPSE') ?>
                                            </a>
                                        </li> -->
                                        <li class="nav-item">
                                            <a class="nav-link" href="/references-masjaki/form-of-business">
                                                <?= Yii::t('app', 'Form of Business') ?>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/references-masjaki/type-of-business-license">
                                                <?= Yii::t('app', 'Type of Business License') ?>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/references-masjaki/construction-sbu">
                                                <?= Yii::t('app', 'Construction SBU') ?>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/references-masjaki/kbli-2020">
                                                <?= Yii::t('app', 'KBLI 2020') ?>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/references-masjaki/ownership-type">
                                                <?= Yii::t('app', 'Ownership Type') ?>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/references-masjaki/type-of-management">
                                                <?= Yii::t('app', 'Type of Management') ?>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/references-masjaki/education">
                                                <?= Yii::t('app', 'Education') ?>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/references-masjaki/skills">
                                                <?= Yii::t('app', 'Skills') ?>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/references-masjaki/source-of-funds">
                                                <?= Yii::t('app', 'Source of funds') ?>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/references-masjaki/ref-negara">
                                                <?= Yii::t('app', 'Country') ?>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/references-masjaki/ref-provinsi">
                                                <?= Yii::t('app', 'Province') ?>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/references-masjaki/ref-kabkota">
                                                <?= Yii::t('app', 'Regency') ?>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/references-masjaki/ref-kecamatan">
                                                <?= Yii::t('app', 'Subdistrict') ?>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/references-masjaki/ref-kelurahan">
                                                <?= Yii::t('app', 'Ward') ?>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/business-entity/business-entity">
                                    <i class="menu-icon bx bx-briefcase"></i>
                                    <span class="menu-title"><?= Yii::t('app', 'Business Entity') ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/construction/labor">
                                    <i class="menu-icon bx bx-id-card"></i>
                                    <span class="menu-title"><?= Yii::t('app', 'Labor') ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/construction/construction-vacancies">
                                    <i class="menu-icon bx bx-building-house"></i>
                                    <span class="menu-title"><?= Yii::t('app', 'Construction Vacancies') ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/construction/work-training">
                                    <i class="menu-icon bx bx-hard-hat"></i>
                                    <span class="menu-title"><?= Yii::t('app', 'Work Training') ?></span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="/rup/default">
                                    <i class="menu-icon bx bx-calendar"></i>
                                    <span class="menu-title"><?= Yii::t('rup', 'Construction Plan') ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/construction/construction-market">
                                    <i class="menu-icon bx bx-store"></i>
                                    <span class="menu-title"><?= Yii::t('app', 'Construction Market') ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/rup/pbj">
                                    <i class="menu-icon bx bx-building"></i>
                                    <span class="menu-title"><?= Yii::t('rup', 'Construction Work') ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/references-masjaki/standar-harga">
                                    <i class="menu-icon bx bx-file"></i>
                                    <span class="menu-title">Peraturan Jasa Konstruksi</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/references-masjaki/tkk">
                                    <i class="menu-icon bx bx-badge-check"></i>
                                    <span class="menu-title">Rekap Sertifikasi TKK</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/references-masjaki/pembinaan">
                                    <i class="menu-icon bx bx-bar-chart-alt-2"></i>
                                    <span class="menu-title">Pembinaan BUJK dan Masyarakat</span>
                                </a>
                            </li>
                            <?php if (Yii::$app->user->can('Administrator') || Yii::$app->user->can('Super Administrator')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="/monitoring/tertib">
                                        <i class="menu-icon bx bx-clipboard"></i>
                                        <span class="menu-title">Pengawasan Tertib</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/report/report">
                                    <i class="menu-icon bx bx-file"></i>
                                    <span class="menu-title"><?= Yii::t('app', 'Report K3') ?></span>
                                </a>
                            </li>
                        <?php endif ?>
                        <?php if (!Yii::$app->user->can('Administrator') && Yii::$app->user->can('Team')) : ?>
                            <li class="nav-item nav-category">Menu</li>
                            <li class="nav-item">
                                <a class="nav-link" href="/business-entity/business-entity">
                                    <i class="menu-icon bx bx-briefcase"></i>
                                    <span class="menu-title"><?= Yii::t('app', 'Business Entity') ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/construction/labor">
                                    <i class="menu-icon bx bx-id-card"></i>
                                    <span class="menu-title"><?= Yii::t('app', 'Labor') ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/rup/pbj">
                                    <i class="menu-icon bx bx-building"></i>
                                    <span class="menu-title"><?= Yii::t('rup', 'Construction Work') ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/report/report">
                                    <i class="menu-icon bx bx-file"></i>
                                    <span class="menu-title"><?= Yii::t('app', 'Report K3') ?></span>
                                </a>
                            </li>
                        <?php endif ?>
                        <?php if (!Yii::$app->user->can('Team') && Yii::$app->user->can('Badan Usaha')) : ?>
                            <li class="nav-item nav-category">Menu</li>
                            <li class="nav-item">
                                <a class="nav-link" href="/business-entity/business-entity">
                                    <i class="menu-icon bx bx-briefcase"></i>
                                    <span class="menu-title"><?= Yii::t('app', 'Business Entity') ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/construction/construction-vacancies">
                                    <i class="menu-icon bx bx-building-house"></i>
                                    <span class="menu-title"><?= Yii::t('app', 'Construction Vacancies') ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/construction/construction-market">
                                    <i class="menu-icon bx bx-store"></i>
                                    <span class="menu-title"><?= Yii::t('app', 'Construction Market') ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/rup/pbj">
                                    <i class="menu-icon bx bx-building"></i>
                                    <span class="menu-title"><?= Yii::t('rup', 'Construction Work') ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/report/report">
                                    <i class="menu-icon bx bx-file"></i>
                                    <span class="menu-title"><?= Yii::t('app', 'Report K3') ?></span>
                                </a>
                            </li>
                        <?php endif ?>
                        <?php if (!Yii::$app->user->can('Team') && Yii::$app->user->can('Tenaga Kerja')) : ?>
                            <li class="nav-item nav-category">Menu</li>
                            <li class="nav-item">
                                <a class="nav-link" href="/construction/labor">
                                    <i class="menu-icon bx bx-id-card"></i>
                                    <span class="menu-title"><?= Yii::t('app', 'Labor') ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/construction/construction-vacancies">
                                    <i class="menu-icon bx bx-building-house"></i>
                                    <span class="menu-title"><?= Yii::t('app', 'Construction Vacancies') ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/construction/work-training">
                                    <i class="menu-icon bx bx-hard-hat"></i>
                                    <span class="menu-title"><?= Yii::t('app', 'Work Training') ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/rup/pbj">
                                    <i class="menu-icon bx bx-building"></i>
                                    <span class="menu-title"><?= Yii::t('rup', 'Construction Work') ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/construction/registrant-vacancies/index-labor">
                                    <i class="menu-icon bx bx-building"></i>
                                    <span class="menu-title"><?= Yii::t('app', 'Currently Registering Vacancies') ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/construction/registrant-training/index-labor">
                                    <i class="menu-icon bx bx-building"></i>
                                    <span class="menu-title"><?= Yii::t('app', 'Currently Registering for Training') ?></span>
                                </a>
                            </li>
                        <?php endif ?>
                        <?php if (Yii::$app->user->can('Super Administrator')) : ?>
                            <li class="nav-item nav-category">Pengaturan Aplikasi</li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="collapse" href="#ui-setting" aria-expanded="false"
                                    aria-controls="ui-setting">
                                    <i class="menu-icon bx bx-cog"></i>
                                    <span class="menu-title"><?= Yii::t('app', 'Setting') ?></span>
                                    <i class="menu-arrow"></i>
                                </a>
                                <div class="collapse" id="ui-setting">
                                    <ul class="nav flex-column sub-menu">
                                        <li class="nav-item">
                                            <a class="nav-link" href="/app_setting">
                                                <?= Yii::t('app', 'Global Application Setting') ?>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/user/admin">
                                                <?= Yii::t('user', 'User Setting') ?>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/data-sync">
                                                <?= Yii::t('app', 'Data Sync') ?>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/backups">
                                                <?= Yii::t('app', 'Back Up') ?>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/hitcounter/hit-counter">
                                                <?= Yii::t('app', 'Statistik Pengunjung') ?>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/api">
                                                <?= Yii::t('app', 'Api Access Tokens') ?>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="collapse" href="#ui-milestone" aria-expanded="false"
                                    aria-controls="ui-milestone">
                                    <i class="menu-icon bx bx-hourglass"></i>
                                    <span class="menu-title"><?= Yii::t('app', 'Milestone') ?></span>
                                    <i class="menu-arrow"></i>
                                </a>
                                <div class="collapse" id="ui-milestone">
                                    <ul class="nav flex-column sub-menu">
                                        <li class="nav-item">
                                            <a class="nav-link" href="/references/year">
                                                <?= References::t('year_lang', 'Fiscal Year Setting') ?>
                                            </a>
                                        </li>
                                        <!-- <li class="nav-item">
                    <a class="nav-link" href="/user-satker">
                      <= Yii::t('app', 'User and OPD') ?>
                    </a>
                  </li> -->
                                    </ul>
                                </div>
                            </li>
                            <!-- <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#ui-sot" aria-expanded="false" aria-controls="ui-sot">
                <i class="menu-icon fa fa-gears"></i>
                <span class="menu-title"><= Yii::t('app', 'SOTK') ?></span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="ui-sot">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link" href="/satker">
                      <= Yii::t('app', 'Satker') ?>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="/manage_sot">
                      <= Yii::t('app', 'SOTK') ?>
                    </a>
                  </li>
                </ul>
              </div>
            </li> -->
                        <?php endif ?>
                    <?php } ?>
                </ul>
            </nav>
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-sm-12">
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
                </div>
                <!-- <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">&copy; Copyright <script>
                document.write(new Date().getFullYear() + ' ');
              </script> version <= Yii::$app->params['version'] ?>. All rights reserved.</span>
          </div>
        </footer> -->
            </div>
        </div>
    </div>

</body>
<?php $this->endBody() ?>

<?php
$this->registerJs("
    function gantiTemaTabel(tema) {
        const tabel = document.querySelector('.grid-view table');
        if (tema === 'dark') {
            tabel.classList.remove('table-light');
            tabel.classList.add('table-dark');
        } else {
            tabel.classList.remove('table-dark');
            tabel.classList.add('table-light');
        }
    }

    function toggleMode() {
        let html = document.documentElement;
        let modeIcon = document.getElementById('mode-icon');
        let currentMode = localStorage.getItem('theme') || 'light';
        if (currentMode === 'light') {
            html.setAttribute('data-bs-theme', 'dark');
            modeIcon.classList.remove('bx-sun');
            modeIcon.classList.add('bx-moon');
            localStorage.setItem('theme', 'dark');
        } else {
            html.setAttribute('data-bs-theme', 'light');
            modeIcon.classList.remove('bx-moon');
            modeIcon.classList.add('bx-sun');
            localStorage.setItem('theme', 'light');
        }
        gantiTemaTabel(html.getAttribute('data-bs-theme')); // Update table theme
    }

    // Apply saved mode on page load
    document.addEventListener('DOMContentLoaded', (event) => {
        let savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
        let modeIcon = document.getElementById('mode-icon');
        if (savedTheme === 'dark') {
            modeIcon.classList.remove('bx-sun');
            modeIcon.classList.add('bx-moon');
        } else {
            modeIcon.classList.remove('bx-moon');
            modeIcon.classList.add('bx-sun');
        }
        gantiTemaTabel(savedTheme); // Update table theme on load
    });
", \yii\web\View::POS_END);
?>


</html>
<?php $this->endPage() ?>

<style>
    .navbar-brand {
        display: flex;
        align-items: center;
    }

    .brand-logo img {
        margin-right: 1rem;
    }

    .brand-text {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        text-align: left;
    }

    .brand-name {
        font-size: 1rem;
        color: var(--bs-primary);
        font-weight: bold;
        margin: 0;
        text-align: left;
    }

    .brand-location {
        font-size: 0.9rem;
        color: var(--bs-primary);
        margin: 0;
        text-align: left;
    }
</style>
<script>
    $(document).ready(function() {
        $("#sidebar-toggle-btn").click(function() {
            $(".col-lg-2").toggleClass("hidden-sidebar");
        });
    });

    $(document).ready(function() {
        var currentUrl = window.location.pathname;
        $('.menu-item').removeClass('active');

        $('.menu-item').find('a[href="' + currentUrl + '"]').closest('li').addClass('active');
    });

    $(document).ready(function() {
        var currentUrl = window.location.pathname;
        $('.menu-item').removeClass('active');

        $('.menu-item').each(function() {
            var href = $(this).find('a').attr('href');
            if (currentUrl.startsWith(href) && (href.endsWith('/') || currentUrl === href || currentUrl
                    .includes(href + '/'))) {
                $(this).addClass('active');
            }
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        var userbox = document.getElementById('userbox');
        var dropdownMenu = userbox.querySelector('.dropdown-menu');
        var toggleLink = userbox.querySelector('a[data-bs-toggle="dropdown"]');

        toggleLink.addEventListener('click', function(event) {
            event.preventDefault();
            if (dropdownMenu.style.display === 'block') {
                dropdownMenu.style.display = 'none';
            } else {
                dropdownMenu.style.display = 'block';
            }
        });

        document.addEventListener('click', function(event) {
            if (!userbox.contains(event.target)) {
                dropdownMenu.style.display = 'none';
            }
        });
    });
</script>
