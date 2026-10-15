<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
?>
<?= GridView::widget(
    [
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            // 'ded_data_id',
            'ded_data_name',
            'description:ntext',
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
                        return Html::a(
                            Html::img(Url::to(['fetch-file', 'id' => Yii::$app->hasher->encode($model->id)], true), ['width' => '200px', 'alt' => Html::encode($model->name)]),
                            Url::to(['fetch-file', 'id' => Yii::$app->hasher->encode($model->id)], true),
                            [
                                'data' => [
                                    'pjax' => 0,
                                    'fancybox' => "monitoring",
                                ]
                            ]
                        );
                    } else if (
                        (strtolower($model->file_extension) == 'mp4') ||
                        (strtolower($model->file_extension) == 'mkv')
                    ) {
                        return '<video width="200px" controls>
                        <source src="' . Url::to(['fetch-file', 'id' => Yii::$app->hasher->encode($model->id)], true) . '" type="video/mp4">
                        Your browser does not support the video tag.
                        ' . Html::a(
                            'Download',
                            Url::to(['fetch-file', 'id' => Yii::$app->hasher->encode($model->id)], true),
                            [
                                'data' => [
                                    'pjax' => 0,
                                    'fancybox' => "monitoring",
                                ]
                            ]
                        ) . '
                        </video>';
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
                'headerOptions' => ['width' => '80'],
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {

                        return Html::a(
                            '<span class="fas fa-eye"></span>',
                            [
                                Url::toRoute(['view', 'id' => Yii::$app->hasher->encode($model->id)])
                            ],
                            [
                                'data-pjax' => '0',
                                'class' => 'btn btn-xs btn-primary',
                                'style' => 'margin: 2px;width: 35px;',
                            ],
                        );
                    },
                    'update' => function ($url, $model, $key) {

                        return Html::a(
                            '<span class="fas fa-edit"></span>',
                            [
                                Url::toRoute(['update', 'id' => Yii::$app->hasher->encode($model->id)])
                            ]
                        );
                    },
                    'delete' => function ($action, $model, $key) {

                        return Html::a(
                            '<span class="fas fa-trash"></span>',
                            [
                                Url::toRoute(['delete', 'id' => Yii::$app->hasher->encode($model->id)])
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
            