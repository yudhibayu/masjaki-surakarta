<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\businessEntity\models\ChangeDeed */

$this->title = Yii::t('app', 'Deed of Change');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Deed of Change'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?> - <?= Yii::t('app', 'Create') ?> Data</h3>
    </div>
    <div class="card-body">
        <div class="change-deed-create">

            <?= $this->render('_form', [
                'model_change_deed' => $model_change_deed,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>