<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\modules\rup\models\RefKegiatan */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rup', 'Kegiatans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="ref-kegiatan-view">

            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), [$url_prev], ['class' => 'btn btn-warning']) ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => Yii::$app->hasher->encode($model->id)], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
            <br>
            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                    'attributes' => [
                        'name:ntext',
                        'kd_satker',
                        'kd_program',
                        'kd_program_str',
                        'kd_kegiatan',
                        'kd_kegiatan_str',
                        [
                            'attribute' => 'pagu',
                            'format' => 'currency',
                        ],
                        'fiscal_year',
                        'kd_kegiatan_lokal:ntext',
                        'is_deleted',
                        'created_at:datetime',
                        'updated_at:datetime',
                        [
                            'attribute' => 'created_by',
                            'value' => $model->maker
                        ],
                        [
                            'attribute' => 'updated_by',
                            'value' => $model->updater
                        ],
                    ],
                ]) ?>
            </div>
            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), [$url_prev], ['class' => 'btn btn-warning']) ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => Yii::$app->hasher->encode($model->id)], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</section>