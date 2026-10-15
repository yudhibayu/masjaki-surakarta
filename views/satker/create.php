<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Satker */

$this->title = Yii::t('app', 'Satker');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Satkers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="satker-create">

            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>
