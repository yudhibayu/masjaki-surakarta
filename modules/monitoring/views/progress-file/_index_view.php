<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="row">
    <div class="col-md-12">
        <p class="text-center">
            <?= Html::a(Yii::t('app', 'Add'), ['progress-file/create', 'id' => Yii::$app->hasher->encode($monitoring_progress_id)], ['class' => 'btn btn-success', 'data-pjax' => 0]) ?>
        </p>
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            // 'tableOptions' => ['class' => 'table table-striped table-bordered', 'style'=>'font-size:11px'],
            'showFooter' => true,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'name:ntext',
                [
                    'attribute' => 'description',
                ],
                [
                    'attribute' => 'uploadimage',
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
                                // 'target' => '_blank',
                                'data' => [
                                    'fancybox' => "monitoring",
                                ],
                            ]
                        );
                    }
                ],
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
                ],
            ],
        ]);
        ?>
    </div>
</div>