<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\modules\businessEntity\models\ExperienceBusinessEntity */

$this->title = $model->work_package_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pengalaman Pekerjaans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="experience-business-entity-view">

            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), ['business-entity/view', 'id' => Yii::$app->hasher->encode($model->id_business_entity)], ['class' => 'btn btn-outline-secondary']) ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?>
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
                        'work_package_name:ntext',
                        'field_work',
                        'assignor_name_work:ntext',
                        'assignor_address_work:ntext',
                        'contract_number',

                        [
                            'attribute' => 'contract_value',
                            'format' => 'currency',
                        ],
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
                        // 'id_business_entity',
                    ],
                ]) ?>
            </div>
            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), ['business-entity/view', 'id' => Yii::$app->hasher->encode($model->id_business_entity)], ['class' => 'btn btn-outline-secondary']) ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?>
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