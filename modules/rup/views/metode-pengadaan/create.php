<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\rup\models\Rupmetodepengadaan */

$this->title = Yii::t('rup', 'RUP Metode Pengadaan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rup', 'RUP Metode Pengadaans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="rupmetodepengadaan-create">

            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>
