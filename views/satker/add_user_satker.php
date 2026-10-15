<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Teamlist */

$this->title = Yii::t('app', 'Add User To Satker');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Satker'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="teamlist-create">

            <?= $this->render('_map_user_satker', [
                'users'     => $users,
                'satkers'     => $satkers,
                'model'     => $model,
                'url_prev'  => $url_prev,
            ]) ?>

        </div>
    </div>
</section>
