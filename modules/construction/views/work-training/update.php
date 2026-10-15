<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\construction\models\WorkTraining */

$this->title = Yii::t('app', 'Update') .  ' Data : ' . $model->training_name;
$this->params['breadcrumbs'][] = ['label' => 'Pelatihan Kerjas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->training_name, 'url' => ['view', 'id' => Yii::$app->hasher->encode($model->id)]];
$this->params['breadcrumbs'][] = 'Update';
?>
<section class="card">
    <div class="card-header">
        <h3><i class="menu-icon tf-icons bx bx-hard-hat"></i> <?= Html::encode($this->title) ?></h3>
        <p>Manajemen Pelatihan Kerja</p>
    </div>
    <div class="card-body">
        <div class="work-training-update">

            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>