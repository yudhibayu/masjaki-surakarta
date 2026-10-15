<?php

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Backups');
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h1><?= Yii::t('app', 'Backups') ?></h1>
    </div>
    <div class="card-body">
        <div class="backups-default-index">
            <p>
                <?= Html::a(Yii::t('app', 'Create and Download Backup'), ['backup'], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Remove Unused backup'), ['remove-backup'], ['class' => 'btn btn-danger']) ?>
            </p>
            <div class="backup row">
                <?php
                // var_dump($backups);
                $provider = new ArrayDataProvider([
                    'allModels' => $backups,
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                    'sort' => [
                        'attributes' => ['id', 'name', 'datetime'],
                    ],
                ]);
                ?>
                <div class="col-md-12">
                    <?= GridView::widget([
                        'dataProvider' => $provider,
                        'columns' => [
                            [
                                'attribute' => 'id',
                                'label' => Yii::t('app', 'ID')
                            ],
                            [
                                'attribute' => 'name',
                                'label' => Yii::t('app', 'Name')
                            ],
                            [
                                'attribute' => 'datetime',
                                'format' => 'datetime',
                                'label' => Yii::t('app', 'Datetime')
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => Yii::t('app', 'Action'),
                                'headerOptions' => ['width' => '100'],
                                'template' => '{download} {delete}',
                                'buttons' => [
                                    'download' => function ($action, $model, $key) {

                                        return Html::a(
                                            '<span class="fas fa-download"></span>',
                                            [
                                                Url::toRoute(['download', 'id' => $model['name']])
                                            ],
                                            [
                                                //'class' => 'btn btn-danger',
                                                'data' => [
                                                    'confirm' => Yii::t('app', 'Are you sure you want to download this item?'),
                                                    'method' => 'post',
                                                    'params' => [], // <- extra level
                                                ],
                                                'class' => 'btn btn-xs btn-primary',
                                                'style' => 'margin: 2px;width: 35px;',
                                            ]
                                        );
                                    },
                                    'delete' => function ($action, $model, $key) {

                                        return Html::a(
                                            '<span class="fas fa-trash"></span>',
                                            [
                                                Url::toRoute(['delete', 'id' => $model['name']])
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
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>