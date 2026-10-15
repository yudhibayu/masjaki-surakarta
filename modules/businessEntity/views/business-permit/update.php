<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\businessEntity\models\BusinessPermit */

$this->title = Yii::t('app', 'Update') .  ' Data : ' . $model_business_permit->reference_number;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Business Permits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model_business_permit->reference_number, 'url' => ['view', 'id' => Yii::$app->hasher->encode($model_business_permit->id)]];
$this->params['breadcrumbs'][] = 'Update';
?>
<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="business-permit-update">

            <?= $this->render('_form', [
                'model_business_permit'         => $model_business_permit,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>