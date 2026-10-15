<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\construction\models\RegistrantVacancies */

$this->title = 'Update Pendaftar Lowongan: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pendaftar Lowongans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => Yii::$app->hasher->encode($model->id)]];
$this->params['breadcrumbs'][] = 'Update';
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="registrant-vacancies-update">
            
            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>
