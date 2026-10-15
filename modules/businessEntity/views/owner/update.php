<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\businessEntity\models\Owner */

$this->title = Yii::t('app', 'Update') .  ' Data : ' . $model_owner->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Owners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model_owner->name, 'url' => ['view', 'id' => Yii::$app->hasher->encode($model_owner->id)]];
$this->params['breadcrumbs'][] = 'Update';
?>
<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="owner-update">

            <?= $this->render('_form', [
                'model_owner'         => $model_owner,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>