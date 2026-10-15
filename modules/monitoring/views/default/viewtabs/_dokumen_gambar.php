<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="row">
    <div class="col-md-12 table-responsive">
        <h4><?= Yii::t('monitoring', 'Monitoring Progress Pictures') ?></h4>
        <?=
        GridView::widget([
            'dataProvider' => $picturesDataProvider,
            // 'tableOptions' => ['class' => 'table table-striped table-bordered', 'style'=>'font-size:11px'],
            'filterModel' => $picturesModel,
            'showFooter' => true,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'name:ntext',
                // 'monitoring_task_id',
                // 'monitoring_task_name:ntext',
                // 'file_name',
                // 'file_url',
                [
                    'attribute' => 'monitoring_progress_name',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a(
                            Html::encode($model->monitoring_progress_name),
                            Url::toRoute(['progress/view', 'id' => Yii::$app->hasher->encode($model->monitoring_progress_id)]),
                            [
                                'data' => [
                                    'pjax' => 0,
                                ],
                            ]
                        );
                    }
                ],
                [
                    'attribute' => 'monitoring_task_locations_name',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a(
                            Html::encode($model->monitoring_task_locations_name),
                            Url::toRoute(['locations/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_locations_id)]),
                            [
                                'data' => [
                                    'pjax' => 0,
                                ],
                            ]
                        );
                    },
                    'visible' => false,
                ],
                [
                    'attribute' => 'file_name',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if (pathinfo($model->file_name, PATHINFO_EXTENSION) == 'mp4') {
                            return '<video width="320" height="240" controls>
                                                <source src="' . $model->picture_url . '" type="video/mp4">
                                                Your browser does not support the video tag.
                                                ' . Html::a(
                                'Download',
                                $model->picture_url,
                                [
                                    'data' => [
                                        'pjax' => 0,
                                        'fancybox' => "monitoring",
                                    ]
                                ]
                            ) . '
                                            </video>';
                        }
                        return Html::a(
                            Html::img(($model->picture_url), ['width' => '200px']),
                            $model->picture_url,
                            [
                                'data' => [
                                    'pjax' => 0,
                                    'fancybox' => "monitoring",
                                ],
                            ]
                        );
                    },
                ],
                // 'description:ntext',
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
                                    Url::to(['progress-file/view', 'id' => Yii::$app->hasher->encode($model->id)])
                                ],
                                [
                                    'class' => 'btn btn-xs btn-primary',
                                    'style' => 'margin: 2px;width: 35px;',
                                ]
                            );
                        },
                    ],
                    // 'footer' => Html::a(Yii::t('app','Add'),Url::to(['progress-file/create','id'=>Yii::$app->hasher->encode($model->id)]),['class' => 'btn btn-primary', 'data' => ['pjax' => 0]]),
                ],
            ],
        ]);
        ?>
    </div>
</div>