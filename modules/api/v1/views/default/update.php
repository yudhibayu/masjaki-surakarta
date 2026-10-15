<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\api\models\ApiAccessToken */

$this->title = Yii::t('app', 'Update Api Access Token: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Api Access Tokens'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => Yii::$app->hasher->encode($model->id)]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="api-access-token-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model'         => $model,
        'dropdown_data' => $dropdown_data,
        'url_prev'      => $url_prev,
    ]) ?>

</div>
