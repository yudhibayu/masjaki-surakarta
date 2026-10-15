<?php

use newerton\fancybox3\FancyBox;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\modules\businessEntity\models\DeedOfIncorporation */

$this->title = $model->deed_number;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Deed of Incorporations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<?php
echo FancyBox::widget([
    'target' => '[data-fancybox="image-fancybox"]',
]);
?>
<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="deed-of-incorporation-view">

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
                            'deed_number',
                            'date_of_deed',
                            'notary_public',
                            [
                                'attribute' => 'deed_file',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    $photoPath = ['/business-entity/deed-of-incorporation/fetch-file', 'id' => Yii::$app->hasher->encode($model->id)];
                                    if ((strtolower($model->file_extension) == 'pdf')) {
                                        $pdfUrl = Url::to($photoPath);
                                        return Html::a(
                                            '<i class="bx bxs-file-pdf"></i> Preview PDF',
                                            $pdfUrl,
                                            [
                                                'class' => 'badge badge-warning',
                                                'data' => [
                                                    'pjax' => 0,
                                                ],
                                                'target' => '_blank'
                                            ]
                                        ) . ' ' .
                                            Html::a(
                                                '<i class="bx bx-download"></i> Download PDF',
                                                $pdfUrl,
                                                [
                                                    'class' => 'badge badge-success',
                                                    'download' => true,
                                                    'data' => [
                                                        'pjax' => 0,
                                                    ],
                                                ]
                                            );
                                    }
                                },
                            ],
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
</section>