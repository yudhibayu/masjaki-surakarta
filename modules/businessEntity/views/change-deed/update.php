<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\businessEntity\models\ChangeDeed */

$this->title = Yii::t('app', 'Update') .  ' Data : ' . $model_change_deed->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Deed of Change'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model_change_deed->id, 'url' => ['view', 'id' => Yii::$app->hasher->encode($model_change_deed->id)]];
$this->params['breadcrumbs'][] = 'Update';
?>
<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="change-deed-update">

            <?= $this->render('_form', [
                'model_change_deed'         => $model_change_deed,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>