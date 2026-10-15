<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\rup\models\Rup */

$this->title = Yii::t('rup', 'Update Construction Plan: {name}', [
    'name' => $model->namapaket,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('rup', 'Rups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idrup, 'url' => ['view', 'id' => Yii::$app->hasher->encode($model->id)]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="rup-update">

            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>