<?php

use kartik\editable\Editable;
use kartik\grid\GridView;
use newerton\fancybox3\FancyBox;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\modules\technicalspec\models\DedData */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => $model->monitoringTask->name, 'url' => ['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<?php
echo FancyBox::widget([
    'target' => '[data-fancybox="monitoring"]',
]);
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="ded-data-view">

            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), ['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)], ['class' => 'btn btn-warning']) ?>
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
                        'name',
                        // 'monitoring_task_id',
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
                            'value' => $model->kuantitasSatuan,
                        ],
                        'notes:ntext',
                        'created_at:datetime',
                        [
                            'attribute' => 'created_by',
                            'value' => $model->maker
                        ],
                        'updated_at:datetime',
                        [
                            'attribute' => 'updated_by',
                            'value' => $model->updater
                        ],
                    ],
                ]) ?>
            </div>
            <br>
            <p class="text-right">
                <?= Html::a(Yii::t('app', 'Add File'), ['ded-files/create', 'dedid' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Add Files'), ['ded-files/bulk-create', 'dedid' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-primary']) ?>
            </p>
            <i>Klik tulisan di kolom Deskripsi untuk mengubah deskripsi berkas.</i>
            <div class="row">
                <?php Pjax::begin(['id' => 'ded-files-yea-pjax']); ?>

                <?php // echo $this->render('_search', ['model' => $searchModel]); 
                ?>

                <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                    <?= GridView::widget(
                        [
                            'dataProvider' => $fileProvider,
                            'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                            'filterModel' => $fileModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                'name',
                                // 'ded_data_id',
                                // 'ded_data_name',
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
                                            // return Html::img(Url::to(['ded-files/fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]), ['width' => '200px', 'alt' => $model->name]);
                                            return Html::a(
                                                Html::img(Url::to(['ded-files/fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]), ['width' => '200px', 'alt' => $model->name]),
                                                Url::to(['ded-files/fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]),
                                                [
                                                    'data' => [
                                                        'pjax' => 0,
                                                        'fancybox' => "monitoring",
                                                    ]
                                                ]
                                            );
                                        } else {
                                            return Html::a(Yii::t('app', 'Download') . ' <span class="fas fa-download"></span>', Url::to(['fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]), ['class' => 'btn btn-primary']);
                                        }
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
                                                    Url::toRoute(['ded-files/view', 'id' => Yii::$app->hasher->encode($model->id)])
                                                ],
                                                [
                                                    'class' => 'btn btn-xs btn-primary',
                                                    'style' => 'margin: 2px;width: 35px;',
                                                ]
                                            );
                                        },
                                        'update' => function ($url, $model, $key) {

                                            return Html::a(
                                                '<span class="fas fa-edit"></span>',
                                                [
                                                    Url::toRoute(['ded-files/update', 'id' => Yii::$app->hasher->encode($model->id)])
                                                ],
                                                [
                                                    'class' => 'btn btn-xs btn-warning',
                                                    'style' => 'margin: 2px;width: 35px;',
                                                ]
                                            );
                                        },
                                        'delete' => function ($action, $model, $key) {

                                            return Html::a(
                                                '<span class="fas fa-trash"></span>',
                                                [
                                                    Url::toRoute(['ded-files/delete', 'id' => Yii::$app->hasher->encode($model->id)])
                                                ],
                                                [
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
            </div>
        </div>
    </div>
</section>