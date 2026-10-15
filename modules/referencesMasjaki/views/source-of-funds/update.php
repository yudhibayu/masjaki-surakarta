<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\referencesMasjaki\models\SourceOfFunds */

$this->title = Yii::t('app', 'Update') .  ' Data : ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Source of funds', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => Yii::$app->hasher->encode($model->id)]];
$this->params['breadcrumbs'][] = 'Update';
?>
<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="source-of-funds-update">
            
            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>
