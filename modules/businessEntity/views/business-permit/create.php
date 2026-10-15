<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\businessEntity\models\BusinessPermit */

$this->title = Yii::t('app', 'Business Permits');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Business Permits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?> - <?= Yii::t('app', 'Create') ?> Data</h3>
    </div>
    <div class="card-body">
        <div class="business-permit-create">

            <?= $this->render('_form', [
                'model_business_permit' => $model_business_permit,
                'dropdown_data'         => $dropdown_data,
                'url_prev'              => $url_prev,
            ]) ?>

        </div>
    </div>
</section>