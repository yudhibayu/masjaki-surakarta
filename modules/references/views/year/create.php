<?php

use app\modules\references\References;
use yii\bootstrap5\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\references\References */

$this->title = References::t('year_lang', 'Create Year');
$this->params['breadcrumbs'][] = ['label' => References::t('year_lang', 'Year Setting'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-body">
        <div class="reftahun-create">

            <h3><?= Html::encode($this->title) ?></h3>

            <?= $this->render('_form', [
                'model' => $model,
                'dropdown_data' => $dropdown_data,
            ]) ?>

        </div>
    </div>
</section>