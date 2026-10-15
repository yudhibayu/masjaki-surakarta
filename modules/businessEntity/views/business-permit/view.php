<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\modules\businessEntity\models\BusinessPermit */

$this->title = $model->typeOfBusinessLicense ? $model->typeOfBusinessLicense->name : null;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Business Permits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="business-permit-view">

            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'),  ['business-entity/view', 'id' => Yii::$app->hasher->encode($model->id_business_entity)], ['class' => 'btn btn-outline-secondary']) ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => Yii::$app->hasher->encode($model->id)], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
            <br>
            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <div class="table-responsive">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                        'attributes' => [
                            [
                                'attribute' => 'business_permit_type',
                                'value' => function ($model) {
                                    return $model->typeOfBusinessLicense ? $model->typeOfBusinessLicense->name : null;
                                },
                            ],
                            'reference_number',
                            'valid_until',
                            'business_qualification',
                            'grantor_institution',
                            // 'business_permit_file',
                            'created_at:datetime',
                            'updated_at:datetime',
                            // 'deleted_at',
                            [
                                'attribute' => 'created_by',
                                'value' => $model->maker
                            ],
                            [
                                'attribute' => 'updated_by',
                                'value' => $model->updater
                            ],
                            // 'deleted_by',
                        ],
                    ]) ?>
                </div>
            </div>
            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'),  ['business-entity/view', 'id' => Yii::$app->hasher->encode($model->id_business_entity)], ['class' => 'btn btn-outline-secondary']) ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => Yii::$app->hasher->encode($model->id)], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
    <?= $this->render('../photo-business-permit/index', [
        // 'model' => $model,
        'photoBusinessPermitModel' => $photoBusinessPermitModel,
        'photoBusinessPermitDataProvider' => $photoBusinessPermitDataProvider,
    ]) ?>
    <?= $this->render('../business-classification/index', [
        // 'model' => $model,
        'businessClassificationModel' => $businessClassificationModel,
        'businessClassificationDataProvider' => $businessClassificationDataProvider,
    ]) ?>
</section>