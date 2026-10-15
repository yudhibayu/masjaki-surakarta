<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\referencesMasjaki\models\TypeOfBusinessLicense */

$this->title = Yii::t('app', 'Type of Business License');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Type of Business License'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?> - <?= Yii::t('app', 'Create') ?> Data</h3>
    </div>
    <div class="card-body">
        <div class="type-of-business-license-create">

            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>
