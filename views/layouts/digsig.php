<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use app\assets\DigsigAsset;
use app\assets\AppAsset;

AppAsset::register($this);
DigsigAsset::register($this);
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
<div class="headershow">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4 text-center"><span class="judul">Digital Signage</span></div>
            <div class="col-md-4"><img src="/images/logo-ska.png" class="logo-ska"></img></div>
        </div>

</div>

    <div class="container">
        <?= $content ?>
    </div>


<div class="footershow">
    <div class="row">
            <div class="col-md-4 text-center"><span class="judulfooter pull-left"><?= Yii::$app->params['copyright'] ?> <?= date('Y') ?></span></div>
            <div class="col-md-8"><span class="blinking">!Sedang Update Data!</span></div>
</div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
