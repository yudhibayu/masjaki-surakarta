<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\referencesMasjaki\models\FormOfBusiness */

$this->title = Yii::t('app', 'Update') .  ' Data : ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Form of Businesses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => Yii::$app->hasher->encode($model->id)]];
$this->params['breadcrumbs'][] = 'Update';
?>
<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?> - Update Data</h3>
    </div>
    <div class="card-body">
        <div class="form-of-business-update">
            
            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>
