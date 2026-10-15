<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UserSatker */

$this->title = Yii::t('app', 'Update User and OPD: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User and OPDs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => Yii::$app->hasher->encode($model->id)]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<section class="card">
    <div class="card-body">
        <div class="user-satker-update">

            <h3><?= Html::encode($this->title) ?></h3>
            <br>

            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>