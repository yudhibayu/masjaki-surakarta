<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\widgets\Alert;
use yii\bootstrap5\Nav;
use app\assets\AppAsset;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Breadcrumbs;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<header class="wrap">
    
    <?php
    $moduleUser = \Yii::$app->getModule('user'); // find user module setting
    $moduleYear = \Yii::$app->getModule('references'); // find user module setting
    $regcheck = $moduleUser->enableRegistration; // if true, user registration button and access are enabled
    $logincheck = Yii::$app->params['enableLogin']; // if true, user registration button and access are enabled
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-dark bg-dark navbar-expand'
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'nav-pills ms-auto'],
        'items' => [
            ['label' => Yii::t('app','Home'), 'url' => ['/site/index']],

            ['label' => Yii::t('app','Setting'), 'visible' => Yii::$app->user->can('Super Administrator'),
	            'items' => [
	                ['label' => Yii::t('app','Global Application Setting'), 'url' => ['/app_setting'], 'visible' => Yii::$app->user->can('Administrator')],
	                ['label' => Yii::t('user','User Setting'), 'url' => ['/user/admin'], 'visible' => Yii::$app->user->can('Super Administrator')],
	                ['label' => $moduleYear->t('year_lang','Fiscal Year Setting'), 'url' => ['/references/year'], 'visible' => Yii::$app->user->can('Super Administrator')],
	            ]
            ],
    
        	Yii::$app->user->isGuest ?
            ['label' => Yii::t('user','Sign In'), 'url' => ['/user/security/login'], 'visible' => $logincheck] :
            ['label' => Yii::t('app','Utility'),
	            'items' => [
	                ['label' => Yii::t('user','Profile'), 'url' => ['/user/settings/profile'], 'visible' => !Yii::$app->user->isGuest],
	            	['label' => Yii::t('user','Sign Out ({0})',Yii::$app->user->identity->username), 'url' => ['/user/security/logout'],
	                'linkOptions' => ['data-method' => 'post']
	            	]
	            ]
            ],
            ['label' => Yii::t('user','Register'), 'url' => ['/user/registration/register'], 'visible' => Yii::$app->user->isGuest && $regcheck]
        ],
        'activateParents'=>true,
    ]);

    NavBar::end();
    ?>
</header>

<section class="content">
    <div class="container">

        <?php 
        echo Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            //'options' => ['class' => 'text-decoration-none']
            //'itemTemplate' =>  "<li class=\"breadcrumb-item text-decoration-none\">{link}</li>\n",
        ]);
         if (isset($this->params['breadcrumbs'])){
             echo "\n <hr>";
         }
        ?>

        <?= Alert::widget() ?>
        <?= $content ?>
 
    </div>
</section>


<footer class="footer">
    <div class="container">
        <a class="pull-left"><?= Yii::$app->params['copyright'] ?> <?= date('Y') ?>, <?= Yii::t('app','Version: '.Yii::$app->version) ?></a>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
