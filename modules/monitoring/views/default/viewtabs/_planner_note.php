<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="row">
    <div class="col-md-12 table-responsive">
        <h4><?= Yii::t('monitoring', 'Monitoring Planner Notes') ?></h4>
        <?=
        GridView::widget([
            'dataProvider' => $plannerNoteDataProvider,
            'filterModel' => $plannerNoteModel,
            'showFooter' => true,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'name:ntext',
                'note_date:date',
                'order_no',
                'description:ntext',
                [
                    'class' => 'kartik\grid\ExpandRowColumn',
                    'width' => '50px',
                    'value' => function ($model, $key, $index, $column) {
                        return GridView::ROW_COLLAPSED;
                    },
                    'detailUrl' => Url::to(['planner-note-file/index-view']),
                    // 'headerOptions' => ['class' => 'kartik-sheet-style'],
                    'expandOneOnly' => true,
                    'allowBatchToggle' => false,
                    'expandIcon' => '<span class="fas fa-caret-square-down"></span>',
                    'collapseIcon' => '<span class="fas fa-caret-square-up"></span>',
                    'header' => 'Data File',
                    'mergeHeader' => true
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => Yii::t('app', 'Action'),
                    'headerOptions' => ['width' => '80'],
                    'template' => '{view} {edit}',
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            return Html::a(
                                '<span class="fas fa-eye"></span>',
                                [
                                    Url::toRoute(['planner-note/view', 'id' => Yii::$app->hasher->encode($model->id)])
                                ],
                                [
                                    'class' => 'btn btn-xs btn-primary',
                                    'style' => 'margin: 2px;width: 35px;',
                                ]
                            );
                        },
                        'edit' => function ($url, $model, $key) {
                            return Html::a(
                                '<span class="fas fa-edit"></span>',
                                [
                                    Url::toRoute(['planner-note/update', 'id' => Yii::$app->hasher->encode($model->id)])
                                ],
                                [
                                    'class' => 'btn btn-xs btn-warning',
                                    'style' => 'margin: 2px;width: 35px;',
                                ]
                            );
                        },
                        'addpicture' => function ($url, $model, $key) {
                            return Html::a(
                                '<span class="fas fa-image"></span>',
                                [
                                    Url::toRoute(['pictures/planner-note/create', 'id' => Yii::$app->hasher->encode($model->id)])
                                ],
                                [
                                    'class' => 'btn btn-xs btn-success',
                                    'style' => 'margin: 2px;width: 35px;',
                                ]
                            );
                        },
                    ],
                    'footer' => Html::a(Yii::t('app', 'Add'), Url::to(['planner-note/create', 'id' => Yii::$app->hasher->encode($model->id)]), ['class' => 'btn btn-primary',]),
                ],
            ],
        ]);
        ?>
    </div>
</div>