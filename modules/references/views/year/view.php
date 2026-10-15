<?php

use app\modules\references\References;
use yii\bootstrap5\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\references\Reftahun */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => References::t('year_lang', 'Year Setting'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<section class="card">
    <div class="card-body">
        <div class="reftahun-view">

            <h1><?= Html::encode($this->title) ?></h1>

            <p>
                <?= Html::a(Yii::t('app', 'Back to List'), ['/references/year'], ['class' => 'btn btn-warning']) ?>
                <?= Html::a(References::t('year_lang', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(References::t('year_lang', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => References::t('year_lang', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </p>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    // 'id',
                    'name',
                    'active',
                    'active_login',
                    'ref_sot_name',
                ],
            ]) ?>

        </div>
    </div>
</section>