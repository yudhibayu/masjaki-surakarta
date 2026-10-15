<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UserSatker */

$this->title = Yii::t('app', 'User and OPD');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User and OPDs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-body">
        <div class="user-satker-create">

            <h3><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>