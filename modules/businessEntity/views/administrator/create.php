<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\businessEntity\models\Administrator */

$this->title = Yii::t('app', 'Administrators');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Administrators'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="administrator-create">

            <?= $this->render('_form', [
                'model_administrator'         => $model_administrator,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>