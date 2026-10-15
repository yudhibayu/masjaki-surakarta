<?php

use newerton\fancybox3\FancyBox;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\modules\businessEntity\models\PhotoBusinessPermit */

$this->title = $model->photo;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'File Business Permit'), 'url' => ['index']];
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
        <div class="photo-business-permit-view">

            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), ['business-permit/view', 'id' => Yii::$app->hasher->encode($model->id_business_permit)], ['class' => 'btn btn-outline-secondary']) ?>
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
                        'options' => ['class' => 'table table-striped table-borderless', 'style' => 'font-size:13px'],
                        'attributes' => [
                            [
                                'attribute' => 'photo',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    $photoPath = ['photo-business-permit/fetch-file', 'id' => Yii::$app->hasher->encode($model->id)];
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
                            // [
                            //     'attribute' => 'photo',
                            //     'format' => 'raw',
                            //     'value' => function ($model) {
                            //         if (
                            //             (strtolower($model->file_extension) == 'jpg') ||
                            //             (strtolower($model->file_extension) == 'jpeg') ||
                            //             (strtolower($model->file_extension) == 'png') ||
                            //             (strtolower($model->file_extension) == 'gif') ||
                            //             (strtolower($model->file_extension) == 'webp')
                            //         ) {
                            //             // return Html::img(Url::to(['fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]), ['width' => '200px', 'alt' => $model->name]);
                            //             return Html::a(
                            //                 Html::img(Url::to(['fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]), ['width' => '200px', 'alt' => $model->photo]),
                            //                 Url::to(['fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]),
                            //                 [
                            //                     'data' => [
                            //                         'pjax' => 0,
                            //                         'fancybox' => "monitoring",
                            //                     ]
                            //                 ]
                            //             );
                            //         } else if (
                            //             (strtolower($model->file_extension) == 'mp4') ||
                            //             (strtolower($model->file_extension) == 'mkv')
                            //         ) {
                            //             return '<video width="200px" controls>
                            //             <source src="' . Url::to(['fetch-file', 'id' => Yii::$app->hasher->encode($model->id)], true) . '" type="video/mp4">
                            //             Your browser does not support the video tag.
                            //             ' . Html::a(
                            //                 'Download',
                            //                 Url::to(['fetch-file', 'id' => Yii::$app->hasher->encode($model->id)], true),
                            //                 [
                            //                     'data' => [
                            //                         'pjax' => 0,
                            //                         'fancybox' => "monitoring",
                            //                     ]
                            //                 ]
                            //             ) . '
                            //             </video>';
                            //         } else {
                            //             return Html::a(Yii::t('app', 'Download') . ' <span class="fas fa-download"></span>', Url::to(['fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]), ['class' => 'btn btn-primary']);
                            //         }
                            //     },
                            // ],
                            'description',
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
                <?= Html::a(Yii::t('app', 'Back to List'), ['business-permit/view', 'id' => Yii::$app->hasher->encode($model->id_business_permit)], ['class' => 'btn btn-outline-secondary']) ?>
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