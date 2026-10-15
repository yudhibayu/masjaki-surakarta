<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use app\modules\construction\models\RegistrantTraining;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\construction\models\RegistrantTrainingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Registrant Training');
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="registrant-training-index">
            <?php if ($model->publication == 1) : ?>
                <p>
                    <?= Html::a('<i class="menu-icon tf-icons bx bx-plus fw-bold"></i> ' . Yii::t('app', 'Create') . Yii::t('app', 'Pendaftar Pelatihan'), ['registrant-training/create', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-success fw-bold']) ?>
                </p>
            <?php endif; ?>

            <?php Pjax::begin(['id' => 'registrant-training-yea-pjax']); ?>

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <?= GridView::widget(
                    [
                        'dataProvider' => $dataProvider,
                        'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            // 'id_training',
                            // 'name_training',
                            // 'id_labor',
                            'nik',
                            'name_labor',
                            'subdistrict',
                            'ward',
                            [
                                'attribute' => 'status',
                                'value' => 'statusSelected',
                            ],
                            // 'created_at:datetime',
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
                                'headerOptions' => ['width' => '150'],
                                'template' => '{view} {select}{notselect}',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {

                                        return Html::a(
                                            '<span class="fas fa-eye"></span>',
                                            [
                                                Url::toRoute(['/construction/labor/view', 'id' => Yii::$app->hasher->encode($model->id_labor)])
                                            ],
                                            [
                                                'class' => 'btn btn-xs btn-primary',
                                                'style' => 'margin: 2px;width: 35px;',
                                            ]
                                        );
                                    },
                                    'select' => function ($url, $model, $key) {
                                        if (Yii::$app->user->can('Administrator') && $model->training->publication == 1 && $model->status == 0) {
                                            if (
                                                RegistrantTraining::find()
                                                ->where(['status' => $model::SELECTED, 'id_training' => $model->id_training])
                                                ->count() < $model->training->kuota
                                            ) {
                                                return Html::a(
                                                    '<span class="fas fa-arrow-right"></span>',
                                                    [
                                                        Url::toRoute(['/construction/registrant-training/select', 'id' => Yii::$app->hasher->encode($model->id)])
                                                    ],
                                                    [
                                                        'class' => 'btn btn-xs btn-warning',
                                                        'style' => 'margin: 2px;width: 35px;',
                                                    ]
                                                );
                                            }
                                        } elseif (Yii::$app->user->can('Administrator') && $model->training->publication == 1 && $model->status == 1) {
                                            return Html::a(
                                                '<span class="fas fa-arrow-left"></span>',
                                                [
                                                    Url::toRoute(['/construction/registrant-training/unselect', 'id' => Yii::$app->hasher->encode($model->id)])
                                                ],
                                                [
                                                    'class' => 'btn btn-xs btn-danger',
                                                    'style' => 'margin: 2px;width: 35px;',
                                                ]
                                            );
                                        }


                                        // if ($model->training->publication == 1 && $model->status == 0) {
                                        //     return Html::a(
                                        //         '<span class="fas fa-arrow-right"></span>',
                                        //         [
                                        //             Url::toRoute(['/construction/registrant-training/select', 'id' => Yii::$app->hasher->encode($model->id)])
                                        //         ],
                                        //         [
                                        //             'class' => 'btn btn-xs btn-warning',
                                        //             'style' => 'margin: 2px;width: 35px;',
                                        //         ]
                                        //     );
                                        // }
                                    },

                                    'notselect' => function ($url, $model, $key) {
                                        if (Yii::$app->user->can('Administrator') && $model->training->publication == 1 && $model->status == 0) {
                                            if (
                                                RegistrantTraining::find()
                                                ->where(['status' => $model::SELECTED, 'id_training' => $model->id_training])
                                                ->count() < $model->training->kuota
                                            ) {
                                                return Html::a(
                                                    '<span class="fas fa-minus"></span>',
                                                    [
                                                        Url::toRoute(['/construction/registrant-training/notselect', 'id' => Yii::$app->hasher->encode($model->id)])
                                                    ],
                                                    [
                                                        'class' => 'btn btn-xs btn-danger',
                                                        'style' => 'margin: 2px;width: 35px;',
                                                    ]
                                                );
                                            }
                                        } elseif (Yii::$app->user->can('Administrator') && $model->training->publication == 1 && $model->status == 2) {
                                            if (
                                                RegistrantTraining::find()
                                                ->where(['status' => $model::SELECTED, 'id_training' => $model->id_training])
                                                ->count() < $model->training->kuota
                                            ) {
                                                return Html::a(
                                                    '<span class="fas fa-undo"></span>',
                                                    [
                                                        Url::toRoute(['/construction/registrant-training/undonotselect', 'id' => Yii::$app->hasher->encode($model->id)])
                                                    ],
                                                    [
                                                        'class' => 'btn btn-xs btn-info',
                                                        'style' => 'margin: 2px;width: 35px;',
                                                    ]
                                                );
                                            }
                                        }
                                    },
                                    // 'update' => function ($url,$model,$key) {

                                    //     return Html::a('<span class="fas fa-edit"></span>', 
                                    //     [
                                    //         Url::toRoute(['update', 'id' => Yii::$app->hasher->encode($model->id)]) 
                                    //     ],
                                    //     [
                                    //         'class' => 'btn btn-xs btn-warning',
                                    //         'style' => 'margin: 2px;width: 35px;',
                                    //     ]
                                    // );
                                    // },
                                    // 'delete' => function ($action,$model,$key) {

                                    //     return Html::a('<span class="fas fa-trash"></span>', 
                                    //         [
                                    //             Url::toRoute(['delete', 'id' => Yii::$app->hasher->encode($model->id)])
                                    //         ], 
                                    //         [
                                    //             'data' => [
                                    //                 'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                    //                 'method' => 'post',
                                    //                 'params' => [], // <- extra level,
                                    //             ],
                                    //             'class' => 'btn btn-xs btn-danger',
                                    //             'style' => 'margin: 2px;width: 35px;',
                                    //         ]
                                    //     );
                                    // },
                                ],
                            ],
                        ],
                    ]
                ); ?>
            </div>
            <?php Pjax::end(); ?>

        </div>
    </div>

</section>

<?= $this->registerJs(
    <<< EOT_JS_CODE

(function($){
    $(document).on('ajaxError', function(event, xhr) {
      if (xhr.status === 401 || xhr.status === 403) {
        window.location.reload();
      }
    });
})(jQuery);

EOT_JS_CODE

);
?>