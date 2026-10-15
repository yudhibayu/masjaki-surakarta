<?php

use kartik\editable\Editable;
use kartik\grid\GridView;
use newerton\fancybox3\FancyBox;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\modules\technicalspec\models\MonitoringSpec */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('monitoring', 'Monitorings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->monitoring_task_name, 'url' => ['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]];
$this->params['breadcrumbs'][] = ['label' => $model->monitoring_progress_name, 'url' => ['/monitoring/progress/view', 'id' => Yii::$app->hasher->encode($model->monitoring_progress_id)]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<?php
echo FancyBox::widget([
    'target' => '[data-fancybox="mon"]',
]);
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="monitoring-spec-view">

            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), ['/monitoring/progress/view', 'id' => Yii::$app->hasher->encode($model->monitoring_progress_id)], ['class' => 'btn btn-warning']) ?>
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

            <div class="row">
                <!-- bagian DED -->
                <div class="col-xl-4">
                    <?php $ded = $model->dedData; ?>
                    <h2>Data DED<br><small><?= Html::encode($ded->name) ?></small></h2>
                    <?= DetailView::widget([
                        'model' => $ded,
                        'options' => ['class' => 'table table-striped table-bordered', 'width' => '100%'],
                        'attributes' => [
                            'name',
                            'monitoring_task_name:ntext',
                            // 'ref_component_category_id',
                            'ref_component_category_name',
                            // 'ref_component_id',
                            'ref_component_name',
                            // 'ref_satuan_name',
                            [
                                'attribute' => 'refSatuan.name',
                                'label' => 'Satuan'
                            ],
                            [
                                'attribute' => 'cco',
                                'value' => function ($model) {
                                    if ($model->cco == 0) {
                                        return 'MCO';
                                    }
                                    return 'CCO ' . $model->cco;
                                },
                            ],
                            'specification:ntext',
                            [
                                'attribute' => 'quantity',
                                'value' => $ded->kuantitasSatuan,
                            ],
                            'notes:ntext',
                            [
                                'label' => Yii::t('app', 'Action'),
                                'format' => 'raw',
                                'value' => Html::a(Yii::t('app', 'View'), ['/technicalspec/default/view', 'id' => Yii::$app->hasher->encode($ded->id)], ['class' => 'btn btn-primary', 'data-pjax' => 0,])
                            ],
                        ],
                    ]) ?>
                    <div class="row">
                        <h4>Berkas : </h4>
                        <?php foreach ($ded->files as $file) : ?>
                            <div class="col-md-6 text-center">
                                <div class="card" style="width: 10rem;">
                                    <div class="card-body">
                                        <?php
                                        if (
                                            (strtolower($file->file_extension) == 'jpg') ||
                                            (strtolower($file->file_extension) == 'jpeg') ||
                                            (strtolower($file->file_extension) == 'png') ||
                                            (strtolower($file->file_extension) == 'gif') ||
                                            (strtolower($file->file_extension) == 'webp')
                                        ) {
                                            echo Html::a(
                                                Html::img(Url::to(['ded-files/fetch-file', 'id' => Yii::$app->hasher->encode($file->id)], true), ['width' => '200px', 'alt' => $file->name, 'class' => 'card-img-top',]),
                                                Url::to(['ded-files/fetch-file', 'id' => Yii::$app->hasher->encode($file->id)], true),
                                                [
                                                    'data' => [
                                                        'pjax' => 0,
                                                        'fancybox' => "monitoring",
                                                    ],
                                                ]
                                            );
                                        } else if (
                                            (strtolower($file->file_extension) == 'mp4') ||
                                            (strtolower($file->file_extension) == 'mkv')
                                        ) {
                                            echo '<video width="150px" controls>
                                <source src="' . Url::to(['ded-files/fetch-file', 'id' => Yii::$app->hasher->encode($file->id)]) . '" type="video/mp4">
                                Your browser does not support the video tag.
                        ' . Html::a(
                                                'Download',
                                                Url::to(['ded-files/fetch-file', 'id' => Yii::$app->hasher->encode($file->id)]),
                                                [
                                                    'data' => [
                                                        'pjax' => 0,
                                                        'fancybox' => "monitoring",
                                                    ]
                                                ]
                                            ) . '
                        </video>';
                                        } else {
                                            echo Html::a(Yii::t('app', 'Download') . ' <span class="fas fa-download"></span>', Url::to(['ded-files/fetch-file', 'id' => Yii::$app->hasher->encode($file->id)]), ['class' => 'btn btn-primary card-img-top', 'data' => ['pjax' => 0],]);
                                        }
                                        echo '<hr>';
                                        echo Html::tag('h5', Html::encode($file->name), [
                                            'class' => 'card-title'
                                        ]);
                                        $description = $file->description;
                                        if (strlen($file->description) > 40) {
                                            $description = substr($file->description, 0, 37) . '...';
                                        }
                                        echo Html::tag('p', $description, [
                                            'class' => 'card-text'
                                        ]);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-xl-8">
                    <h2>Monitoring Spesifikasi<br><small><?= Html::encode($model->name) ?></small></h2>
                    <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                            'attributes' => [
                                'name',
                                // 'ded_data_id',
                                'ded_data_name',
                                // 'monitoring_progress_id',
                                'monitoring_progress_name',
                                // 'monitoring_task_id',
                                'monitoring_task_name',
                                'description:ntext',
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
                    <br>
                    <?php Pjax::begin() ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="text-right">
                                <?= Html::a(Yii::t('app', 'Add Files'), ['add-files', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-primary']) ?>
                            </div>
                            <i>Klik tulisan di kolom Deskripsi untuk mengubah deskripsi file spesifikasi.</i>
                            <?= GridView::widget(
                                [
                                    'dataProvider' => $specfileProvider,
                                    'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                                    'filterModel' => $specfileModel,
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],

                                        'name',
                                        // 'monitoring_spec_id',
                                        // 'description:ntext',
                                        [
                                            'class' => 'kartik\grid\EditableColumn',
                                            // 'label' => 'Nominal Penggunaan',
                                            'attribute' => 'description',
                                            // 'headerOptions' => ['style' => 'color: #c53131'],
                                            'format' => 'ntext',
                                            // 'value' => 'finance_amount',
                                            'refreshGrid' => false,
                                            'editableOptions' => function ($model, $key, $index) {
                                                return [
                                                    'header' => Yii::t('app', 'Description'),
                                                    // 'size' => 'md',
                                                    'inputType' => Editable::INPUT_TEXTAREA,
                                                    'asPopover' => false,
                                                ];
                                            }
                                        ],
                                        // 'file_name',
                                        // 'file_path:ntext',
                                        [
                                            'attribute' => 'file_upload',
                                            'format' => 'raw',
                                            'value' => function ($model) {
                                                if (
                                                    (strtolower($model->file_extension) == 'jpg') ||
                                                    (strtolower($model->file_extension) == 'jpeg') ||
                                                    (strtolower($model->file_extension) == 'png') ||
                                                    (strtolower($model->file_extension) == 'gif') ||
                                                    (strtolower($model->file_extension) == 'webp')
                                                ) {
                                                    return Html::a(
                                                        Html::img(Url::to(['/technicalspec/monitoring-spec-files/fetch-file', 'id' => Yii::$app->hasher->encode($model->id)], true), ['width' => '200px', 'alt' => $model->name]),
                                                        Url::to(['/technicalspec/monitoring-spec-files/fetch-file', 'id' => Yii::$app->hasher->encode($model->id)], true),
                                                        [
                                                            'data' => [
                                                                'pjax' => 0,
                                                                'fancybox' => "mon",
                                                            ]
                                                        ]
                                                    );
                                                } else if (
                                                    (strtolower($model->file_extension) == 'mp4') ||
                                                    (strtolower($model->file_extension) == 'mkv')
                                                ) {
                                                    return '<video width="200px" controls>
                                            <source src="' . Url::to(['monitoring-spec-files/fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]) . '" type="video/mp4">
                                            Your browser does not support the video tag.
                                            ' . Html::a(
                                                        'Download',
                                                        Url::to(['monitoring-spec-files/fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]),
                                                        [
                                                            'data' => [
                                                                'pjax' => 0,
                                                                'fancybox' => "monitoring",
                                                            ]
                                                        ]
                                                    ) . '
                                            </video>';
                                                } else {
                                                    return Html::a(Yii::t('app', 'Download') . ' <span class="fas fa-download"></span>', Url::to(['/technicalspec/monitoring-spec-files/fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]), ['class' => 'btn btn-primary', 'data' => ['pjax' => 0], 'target' => '_blank']);
                                                }
                                            },
                                        ],
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
                                            'headerOptions' => ['width' => '80'],
                                            'template' => '{view} {delete}',
                                            'buttons' => [
                                                'view' => function ($url, $model, $key) {

                                                    return Html::a(
                                                        '<span class="fas fa-eye"></span>',
                                                        [
                                                            Url::toRoute(['monitoring-spec-files/view', 'id' => Yii::$app->hasher->encode($model->id)])
                                                        ]
                                                    );
                                                },
                                                'update' => function ($url, $model, $key) {

                                                    return Html::a(
                                                        '<span class="fas fa-edit"></span>',
                                                        [
                                                            Url::toRoute(['monitoring-spec-files/update', 'id' => Yii::$app->hasher->encode($model->id)])
                                                        ]
                                                    );
                                                },
                                                'delete' => function ($action, $model, $key) {

                                                    return Html::a(
                                                        '<span class="fas fa-trash"></span>',
                                                        [
                                                            Url::toRoute(['monitoring-spec-files/delete', 'id' => Yii::$app->hasher->encode($model->id)])
                                                        ],
                                                        [
                                                            //'class' => 'btn btn-danger',
                                                            'data' => [
                                                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                                'method' => 'post',
                                                                'params' => [], // <- extra level
                                                            ],
                                                        ]
                                                    );
                                                },
                                            ],
                                        ],
                                    ],
                                ],
                            ); ?>
                        </div>
                    </div>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</section>