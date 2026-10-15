<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\manage_sot\models\RefSot */

$this->title = Yii::t('manage_sot', 'Create Sot');
$this->params['breadcrumbs'][] = ['label' => Yii::t('manage_sot', 'Sots'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-body">
        <div class="ref-sot-create">

            <h3><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>