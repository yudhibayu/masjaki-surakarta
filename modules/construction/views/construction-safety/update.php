<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\construction\models\ConstructionSafety */

$this->title = Yii::t('app', 'Update') .  ' Data : ' . $model->date;
$this->params['breadcrumbs'][] = ['label' => 'Construction Works', 'url' => ['./construction-work']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Construction Safeties'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => Yii::$app->hasher->encode($model->id)]];
$this->params['breadcrumbs'][] = 'Update';
?>
<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="construction-safety-update">

            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>