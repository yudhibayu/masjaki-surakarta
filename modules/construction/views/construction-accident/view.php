<?php

use newerton\fancybox3\FancyBox;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $model app\modules\construction\models\ConstructionAccident */

if($model->date != null){
    $date = Yii::$app->formatter->asDate($model->date, 'php:j F Y');
} else {
    $date = '';
}
$this->title = $date;
$this->params['breadcrumbs'][] = ['label' => 'Construction Works', 'url' => ['./construction-work']];
$this->params['breadcrumbs'][] = ['label' => 'Construction Accidents', 'url' => ['index']];
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
        <h3>Laporan Kecelakaan - <?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="construction-accident-view">

            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), ['/rup/pbj/view', 'id' => Yii::$app->hasher->encode($model->id_construction_work)], ['class' => 'btn btn-outline-secondary']) ?>
                <?php if (Yii::$app->user->can('Badan Usaha')): ?>
                    <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?>
                    <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => Yii::$app->hasher->encode($model->id)], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) ?>
                <?php endif; ?>
            </div>
            <br>
            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <div class="table-responsive">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                        'attributes' => [
                            // 'id_construction_work',
                            'accident:boolean',
                            [
                                'attribute' => 'date',
                                'format' => ['date', 'php:j F Y'],
                                'label' => 'Tanggal',
                            ],
                            // 'place',
                            // [
                            //     'attribute' => 'file_before_repair',
                            //     'format' => 'raw',
                            //     'value' => function ($model) {
                            //         $photoPath = ['/construction/construction-accident/fetch-file-before', 'id' => Yii::$app->hasher->encode($model->id)];
                            //         if ((strtolower($model->file_extension_before) == 'pdf')) {
                            //             $pdfUrl = Url::to($photoPath);
                            //             return Html::a(
                            //                 '<i class="bx bxs-file-pdf"></i> Preview PDF',
                            //                 $pdfUrl,
                            //                 [
                            //                     'class' => 'badge badge-warning',
                            //                     'data' => [
                            //                         'pjax' => 0,
                            //                     ],
                            //                 ]
                            //             ) . ' ' .
                            //                 Html::a(
                            //                     '<i class="bx bx-download"></i> Download PDF',
                            //                     $pdfUrl,
                            //                     [
                            //                         'class' => 'badge badge-success',
                            //                         'download' => true,
                            //                         'data' => [
                            //                             'pjax' => 0,
                            //                         ],
                            //                     ]
                            //                 );
                            //         } elseif ((strtolower($model->file_extension_before) == 'png' || 'jpg' || 'jpeg')) {
                            //             $photoUrl = Url::to($photoPath);
                            //             return Html::a(
                            //                 Html::img($photoUrl, ['width' => '100px']),
                            //                 $photoUrl,
                            //                 [
                            //                     'class' => 'badge',
                            //                     'data' => [
                            //                         'pjax' => 0,
                            //                         'fancybox' => "image-fancybox",
                            //                     ],
                            //                 ]
                            //             );
                            //         }
                            //     },
                            // ],
                            // [
                            //     'attribute' => 'file_after_repair',
                            //     'format' => 'raw',
                            //     'value' => function ($model) {
                            //         $photoPath = ['/construction/construction-accident/fetch-file-after', 'id' => Yii::$app->hasher->encode($model->id)];
                            //         if ((strtolower($model->file_extension_after) == 'pdf')) {
                            //             $pdfUrl = Url::to($photoPath);
                            //             return Html::a(
                            //                 '<i class="bx bxs-file-pdf"></i> Preview PDF',
                            //                 $pdfUrl,
                            //                 [
                            //                     'class' => 'badge badge-warning',
                            //                     'data' => [
                            //                         'pjax' => 0,
                            //                     ],
                            //                 ]
                            //             ) . ' ' .
                            //                 Html::a(
                            //                     '<i class="bx bx-download"></i> Download PDF',
                            //                     $pdfUrl,
                            //                     [
                            //                         'class' => 'badge badge-success',
                            //                         'download' => true,
                            //                         'data' => [
                            //                             'pjax' => 0,
                            //                         ],
                            //                     ]
                            //                 );
                            //         } elseif ((strtolower($model->file_extension_after) == 'png' || 'jpg' || 'jpeg')) {
                            //             $photoUrl = Url::to($photoPath);
                            //             return Html::a(
                            //                 Html::img($photoUrl, ['width' => '100px']),
                            //                 $photoUrl,
                            //                 [
                            //                     'class' => 'badge',
                            //                     'data' => [
                            //                         'pjax' => 0,
                            //                         'fancybox' => "image-fancybox",
                            //                     ],
                            //                 ]
                            //             );
                            //         }
                            //     },
                            // ],
                            // 'job_executor',
                            'accident_category',
                            [
                                'attribute' => 'amount_victims_minor_injuries',
                                'value' => function ($model) {
                                    if ($model->amount_victims_minor_injuries) {
                                        return $model->amount_victims_minor_injuries . ' orang';
                                    } else {
                                        return '';
                                    }
                                },
                            ],
                            [
                                'attribute' => 'amount_victims_moderate_injuries',
                                'value' => function ($model) {
                                    if ($model->amount_victims_moderate_injuries) {
                                        return $model->amount_victims_moderate_injuries . ' orang';
                                    } else {
                                        return '';
                                    }
                                },
                            ],
                            [
                                'attribute' => 'amount_victims_serious_injuries',
                                'value' => function ($model) {
                                    if ($model->amount_victims_serious_injuries) {
                                        return $model->amount_victims_serious_injuries . ' orang';
                                    } else {
                                        return '';
                                    }
                                },
                            ],
                            [
                                'attribute' => 'amount_victims_die',
                                'value' => function ($model) {
                                    if ($model->amount_victims_die) {
                                        return $model->amount_victims_die . ' orang';
                                    } else {
                                        return '';
                                    }
                                },
                            ],
                            'description',
                            'bpjs:boolean',
                            'no_bpjs',
                            'total_pekerja',
                            [
                                'attribute' => 'iuran_pekerja',
                                'format' => 'currency',
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

            <br>
          
            <h4><b><?= Yii::t('app', 'File Upload') ?></b></h4>
                <div class="text-right">
                    <?= Html::a(Yii::t('app', 'Add File'), ['construction-accident-file/create', 'pnid' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>

            <br>
            <?php Pjax::begin(['id' => 'construction-accident-file-yea-pjax']); ?>
            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <?= GridView::widget(
                    [
                        'dataProvider' => $accidentFileDataProvider,
                        'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                        'filterModel' => $accidentFileModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            'name',
                            [
                                'attribute' => 'file_name',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    if (
                                        // (strtolower($model->file_extension) == 'jpg') ||
                                        // (strtolower($model->file_extension) == 'jpeg') ||
                                        // (strtolower($model->file_extension) == 'png') ||
                                        // (strtolower($model->file_extension) == 'gif') ||
                                        // (strtolower($model->file_extension) == 'webp') ||
                                        (strtolower($model->file_extension) == 'pdf')
                                    ) {
                                        // return Html::img(Url::to(['fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]), ['width' => '200px', 'alt' => $model->name]);
                                        return $model->file_name . '&nbsp&nbsp&nbsp&nbsp&nbsp' . Html::a(
                                            Yii::t('app', 'Download') . ' <span class="fas fa-download"></span>',
                                            Url::to(['construction-accident-file/fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]),
                                            [
                                                'class' => 'btn btn-xs btn-success',
                                                'data' => [
                                                    'pjax' => 0,
                                                    // 'fancybox' => "accident",
                                                ],
                                                'target' => '_blank'
                                            ]
                                        );
                                    }
                                    // else {
                                    //     return Html::a(Yii::t('app', 'Download') . ' <span class="fas fa-download"></span>', Url::to(['fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]), ['class' => 'btn btn-primary', 'target' => '_blank']);
                                    // }
                                },
                            ],
                            //'file_path:ntext',
                            //'file_extension',
                            //'file_size',
                            //'created_at:datetime',
                            //'updated_at:datetime',
                            /*[
                                'attribute' => 'created_by', 
                                'value' => 'maker',
                                'filter' => false
                            ],*/
                            /*[
                                'attribute' => 'updated_by', 
                                'value' => 'updater',
                                'filter' => false
                            ],*/

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => Yii::t('app', 'Action'),
                                'headerOptions' => ['width' => '100'],
                                'template' => '{view} {delete}',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {

                                        return Html::a(
                                            '<span class="fas fa-eye"></span>',
                                            [
                                                Url::toRoute(['construction-accident-file/view', 'id' => Yii::$app->hasher->encode($model->id)])
                                            ],
                                            [
                                                'class' => 'btn btn-xs btn-primary',
                                                'style' => 'margin: 2px;width: 35px;',
                                            ]
                                        );
                                    },
                                    'delete' => function ($action, $model, $key) {

                                        return Html::a(
                                            '<span class="fas fa-trash"></span>',
                                            [
                                                Url::toRoute(['construction-accident-file/delete', 'id' => Yii::$app->hasher->encode($model->id)])
                                            ],
                                            [
                                                //'class' => 'btn btn-danger',
                                                'data' => [
                                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                    'method' => 'post',
                                                    'params' => [], // <- extra level
                                                ],
                                                'class' => 'btn btn-xs btn-danger',
                                                'style' => 'margin: 2px;width: 35px;',
                                            ]
                                        );
                                    },
                                ],
                            ],
                        ],
                    ],
                ); ?>
            </div>
            <?php Pjax::end(); ?>
            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), ['/rup/pbj/view', 'id' => Yii::$app->hasher->encode($model->id_construction_work)], ['class' => 'btn btn-outline-secondary']) ?>
                <?php if (Yii::$app->user->can('Badan Usaha')): ?>
                    <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?>
                    <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => Yii::$app->hasher->encode($model->id)], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>