<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\businessEntity\models\DeedOfIncorporation */

$this->title = Yii::t('app', 'Deed of Incorporations');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Deed of Incorporations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?> - <?= Yii::t('app', 'Create') ?> Data</h3>
    </div>
    <div class="card-body">
        <div class="deed-of-incorporation-create">

            <?= $this->render('_form', [
                'model_deed_of_incorporation'         => $model_deed_of_incorporation,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>