<?php

use newerton\fancybox3\FancyBox;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
?>
<?php
echo FancyBox::widget([
    'target' => '[data-fancybox="mcocco"]',
]);
?>
<?= GridView::widget(
    [
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'file_name',
                'format' => 'raw',
                'value' => function ($model) {
                    if (!empty($model->file_name)) {
                        $ext = pathinfo($model->file_name, PATHINFO_EXTENSION);

                        if (isset($ext)) {
                            if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif' || $ext == 'webp') {
                                $b = Html::img(
                                    "/technicalspec/cco/file-item?id=" . $model->file_name,
                                    $options = [
                                        'style' => ['width' => '200px'],
                                        'class' => 'img_gallery img_style'
                                    ]
                                );
                            } else {
                                $b = $model->file_name;
                            }
                        }
                    }
                    return Html::a(
                        $b,
                        Url::to(['/technicalspec/cco/file-item', 'id' => Yii::$app->hasher->encode($model->file_name)], true),
                        [
                            'data' => [
                                'fancybox' => 'true',
                                'type' => 'iframe',
                                'src' => Url::toRoute(['/technicalspec/cco/file-item', 'id' => $model->file_name], true),
                                'title' => Yii::t('app', 'File Name'),
                                'alt' => Yii::t('app', 'File Name'),
                            ]
                        ]
                    );
                },
            ],
            'description:ntext',
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
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {

                        return Html::a(
                            '<span class="fas fa-edit"></span>',
                            [
                                Url::toRoute(['/technicalspec/cco/update-file', 'id' => Yii::$app->hasher->encode($model->id)])
                            ]
                        );
                    },
                    'delete' => function ($action, $model, $key) {

                        return Html::a(
                            '<span class="fas fa-trash"></span>',
                            [
                                Url::toRoute(['/technicalspec/cco/delete-file', 'id' => Yii::$app->hasher->encode($model->id)])
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