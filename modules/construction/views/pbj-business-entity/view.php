<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\modules\construction\models\PbjBusinessEntity */

$this->title = $model->name_business_entity;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Badan Usaha PBJs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="pbj-business-entity-view">

            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), ['/rup/pbj/view', 'id' => Yii::$app->hasher->encode($model->kode_pbj)], ['class' => 'btn btn-outline-secondary']) ?>
                <!-- <= Html::a(Yii::t('app', 'Update'), ['/construction/pbj-business-entity/update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?> -->
                <?= Html::a(Yii::t('app', 'Delete'), ['/construction/pbj-business-entity/delete', 'id' => Yii::$app->hasher->encode($model->id)], [
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
                        // 'kode_rup',
                        // 'kode_pbj',
                        // 'id_business_entity',
                        'name_business_entity',
                        // 'user_id',

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
                <?= Html::a(Yii::t('app', 'Back to List'), ['/rup/pbj/view', 'id' => Yii::$app->hasher->encode($model->kode_pbj)], ['class' => 'btn btn-outline-secondary']) ?>
                <!-- <= Html::a(Yii::t('app', 'Update'), ['/construction/pbj-business-entity/update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?> -->
                <?= Html::a(Yii::t('app', 'Delete'), ['/construction/pbj-business-entity/delete', 'id' => Yii::$app->hasher->encode($model->id)], [
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