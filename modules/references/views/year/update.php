<?php

use app\modules\references\References;
use yii\bootstrap5\Html;

/* @var $this yii\web\View */
/* @var $model app\models\references\Reftahun */

$this->title = References::t('year_lang', 'Update Year: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => References::t('year_lang', 'Year Setting'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = References::t('year_lang', 'Update');
?>
<section class="card">
    <div class="card-body">
        <div class="reftahun-update">

            <h3><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form', [
                'model' => $model,
                'dropdown_data' => $dropdown_data,
            ]) ?>

        </div>
    </div>
</section>