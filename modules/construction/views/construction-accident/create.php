<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\construction\models\ConstructionAccident */

$this->title = Yii::t('app', 'Construction Accidents');
$this->params['breadcrumbs'][] = ['label' => 'Construction Works', 'url' => ['./construction-work']];
$this->params['breadcrumbs'][] = ['label' => 'Construction Accidents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="construction-accident-create">

            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>