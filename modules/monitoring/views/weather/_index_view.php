<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\monitoring\models\MonitoringWeatherSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="row">
    <div class="col-md-12">
        <p class="text-center">
            <?= Html::a(Yii::t('app', 'Add'), ['weather/create', 'id' => Yii::$app->hasher->encode($monitoring_progress_id)], ['class' => 'btn btn-success', 'data-pjax' => 0]) ?>
        </p>
        <?= GridView::widget(
            [
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'name',
                    'refCuaca.name',
                    'time_from:datetime',
                    'time_to:datetime',
                    'note:ntext',
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
                        'template' => '{view} {update} {delete}',
                        'buttons' => [
                            'view' => function ($url, $model, $key) {

                                return Html::a(
                                    '<span class="fas fa-eye"></span>',
                                    [
                                        Url::toRoute(['weather/view', 'id' => Yii::$app->hasher->encode($model->id)])
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
                                        Url::toRoute(['weather/update', 'id' => Yii::$app->hasher->encode($model->id)])
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
                                        Url::toRoute(['weather/delete', 'id' => Yii::$app->hasher->encode($model->id)])
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
</div>