<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\technicalspec\models\McoCco */

$this->title = Yii::t('technicalspec', 'MCO CCO');
$this->params['breadcrumbs'][] = ['label' => Yii::t('technicalspec', 'MCO CCOs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="mco-cco-create">

            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>