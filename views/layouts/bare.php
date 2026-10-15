<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\widgets\Alert;
use app\assets\AppAsset;
// AppAsset::register($this);
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
</header>

<section class="content">
    <div class="container">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</section>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
