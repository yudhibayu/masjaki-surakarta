<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\construction\models\Labor */

$this->title = Yii::t('app', 'Labor');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Labor'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h3><i class="menu-icon tf-icons bx bx-id-card"></i> <?= Html::encode($this->title) ?> - <?= Yii::t('app', 'Create') ?> Data</h3>
        <p>Manajemen Tenaga Kerja Konstruksi</p>
    </div>
    <div class="card-body">
        <div class="labor-create">

            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>