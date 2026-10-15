<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hitcounter\models\HitCounter */

$this->title = Yii::t('app', 'Hit Counter');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hit Counters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="hit-counter-create">

            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>
