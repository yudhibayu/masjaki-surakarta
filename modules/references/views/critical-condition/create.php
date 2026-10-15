<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\references\models\RefCriticalCondition */

$this->title = Yii::t('app', 'Critical Condition');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Critical Conditions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="ref-critical-condition-create">

            <h3><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>
