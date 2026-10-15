<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Eksekutorlist */

$this->title = Yii::t('app', 'Eksekutor');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Eksekutors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="eksekutorlist-create">

            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>
