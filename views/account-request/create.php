<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\frontMap\models\TblAccountRequest */

$this->title = Yii::t('app', 'Account Request');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Account Requests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="tbl-account-request-create">

            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>