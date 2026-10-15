<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\businessEntity\models\BusinessEntity */

$this->title = Yii::t('app', 'Business Entity');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Business Entity'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h3><i class="menu-icon tf-icons bx bx-buildings"></i> <?= Html::encode($this->title) ?> -
            <?= Yii::t('app', 'Create') ?> Data</h3>
        <p>Manajemen Badan Usaha Konstruksi</p>
    </div>
    <div class="card-body">
        <div class="business-entity-create">

            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev
            ]) ?>

        </div>
    </div>
</section>