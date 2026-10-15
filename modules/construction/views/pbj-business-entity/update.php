<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\construction\models\PbjBusinessEntity */

$this->title = Yii::t('app', 'Update Badan Usaha PBJ: {name}', [
    'name' => $model->name_business_entity,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Badan Usaha PBJs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => Yii::$app->hasher->encode($model->id)]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="pbj-business-entity-update">

            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>