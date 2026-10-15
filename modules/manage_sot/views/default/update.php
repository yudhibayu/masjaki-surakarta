<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\manage_sot\models\RefSot */

$this->title = Yii::t('manage_sot', 'Update Sot: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('manage_sot', 'Sot'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<section class="card">
    <div class="card-body">
        <div class="ref-sot-update">

            <h3><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>