<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\construction\models\ConstructionVacancies */

$this->title = Yii::t('app', 'Update') .  ' Data : ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Construction Vacancies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => Yii::$app->hasher->encode($model->id)]];
$this->params['breadcrumbs'][] = 'Update';
?>
<section class="card">
    <div class="card-header">
        <h3><i class="menu-icon tf-icons bx bx-building-house"></i> <?= Html::encode($this->title) ?></h3>
        <p>Manajemen Lowongan Kerja</p>
    </div>
    <div class="card-body">
        <div class="construction-vacancies-update">

            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>