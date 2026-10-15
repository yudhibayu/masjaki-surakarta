<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\api\models\ApiAccessToken */

$this->title = Yii::t('app', 'Create Api Access Token');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Api Access Tokens'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="api-access-token-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model'         => $model,
        'dropdown_data' => $dropdown_data,
        'url_prev'      => $url_prev,
    ]) ?>

</div>
