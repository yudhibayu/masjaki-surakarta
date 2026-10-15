<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\businessEntity\models\BusinessEntity */

$this->title = Yii::t('app', 'Update') .  ' Data : ' . $model->company_name;
$this->params['breadcrumbs'][] = ['label' => 'Business Entities', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => Yii::$app->hasher->encode($model->id)]];
$this->params['breadcrumbs'][] = 'Update';
?>
<section class="card">
    <div class="card-header">
        <h3><i class="menu-icon tf-icons bx bx-buildings"></i> <?= Html::encode($this->title) ?></h3>
        <p>Manajemen Badan Usaha Konstruksi</p>
    </div>
    <div class="card-body">
        <div class="business-entity-update">

            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>