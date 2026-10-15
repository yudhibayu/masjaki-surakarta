<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\businessEntity\models\Administrator */

$this->title = Yii::t('app', 'Update') .  ' Data : ' . $model_administrator->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Administrators'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model_administrator->name, 'url' => ['view', 'id' => Yii::$app->hasher->encode($model_administrator->id)]];
$this->params['breadcrumbs'][] = 'Update';
?>
<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="administrator-update">

            <?= $this->render('_form', [
                'model_administrator'         => $model_administrator,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>