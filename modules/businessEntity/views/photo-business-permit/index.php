<?php

use newerton\fancybox3\FancyBox;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\businessEntity\models\PhotoBusinessPermitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'File Business Permit');
$this->params['breadcrumbs'][] = $this->title;
$id = Yii::$app->request->get('id');

?>
<?php
echo FancyBox::widget([
    'target' => '[data-fancybox="image-fancybox"]',
]);
?>

<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="photo-business-permit-index">
            <p>
                <?= Html::a('<i class="menu-icon tf-icons bx bx-plus fw-bold"></i> ' . Yii::t('app', 'Create') . Yii::t('app', 'File Business Permit'), ['photo-business-permit/create', 'id' => $id], ['class' => 'btn btn-success fw-bold']) ?>
            </p>
            <br>
            <?php Pjax::begin(['id' => 'photo-business-permit-yea-pjax']); ?>

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <?= GridView::widget(
                    [
                        'dataProvider' => $photoBusinessPermitDataProvider,
                        'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover', 'style' => 'font-size:11px'],
                        'filterModel' => $photoBusinessPermitModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            // [
                            //     'attribute' => 'photo',
                            //     'format' => 'raw',
                            //     'value' => function ($model) {
                            //         if (
                            //             (strtolower($model->file_extension) == 'jpg') ||
                            //             (strtolower($model->file_extension) == 'jpeg') ||
                            //             (strtolower($model->file_extension) == 'png') ||
                            //             (strtolower($model->file_extension) == 'gif') ||
                            //             (strtolower($model->file_extension) == 'webp')
                            //         ) {
                            //             // return Html::img(Url::to(['photo-business-permit/fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]), ['width' => '200px', 'alt' => $model->name]);
                            //             return Html::a(
                            //                 Html::img(Url::to(['photo-business-permit/fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]), ['width' => '200px', 'alt' => $model->photo]),
                            //                 Url::to(['photo-business-permit/fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]),
                            //                 [
                            //                     'data' => [
                            //                         'pjax' => 0,
                            //                         // 'fancybox' => "image-fancybox",
                            //                     ]
                            //                 ]
                            //             );
                            //         } else {
                            //             return Html::a(Yii::t('app', 'Download') . ' <span class="fas fa-download"></span>', Url::to(['photo-business-permit/fetch-file', 'id' => Yii::$app->hasher->encode($model->id)]), ['class' => 'btn btn-primary', 'data' => [
                            //                 'pjax' => 0,
                            //                 // 'fancybox' => "image-fancybox",
                            //             ]]);
                            //         }
                            //     },
                            // ],

                            [
                                'attribute' => 'photo',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    $photoPath = ['photo-business-permit/fetch-file', 'id' => Yii::$app->hasher->encode($model->id)];
                                    if ((strtolower($model->file_extension) == 'pdf')) {
                                        $pdfUrl = Url::to($photoPath);
                                        return Html::a(
                                            '<i class="bx bxs-file-pdf"></i> Preview PDF',
                                            $pdfUrl,
                                            [
                                                'class' => 'badge badge-warning',
                                                'data' => [
                                                    'pjax' => 0,
                                                ],
                                                'target' => '_blank'
                                            ]
                                        ) . ' ' .
                                            Html::a(
                                                '<i class="bx bx-download"></i> Download PDF',
                                                $pdfUrl,
                                                [
                                                    'class' => 'badge badge-success',
                                                    'download' => true,
                                                    'data' => [
                                                        'pjax' => 0,
                                                    ],
                                                ]
                                            );
                                    }
                                },
                            ],
                            'description',
                            // 'created_at:datetime',
                            // 'updated_at:datetime',
                            //'deleted_at',
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
                            //'deleted_by',

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => Yii::t('app', 'Action'),
                                'headerOptions' => ['width' => '100'],
                                'template' => '{view} {delete} {update}',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {

                                        return Html::a(
                                            '<span class="bx bx-show"></span>',
                                            [
                                                Url::toRoute(['photo-business-permit/view', 'id' => Yii::$app->hasher->encode($model->id)])
                                            ],
                                            [
                                                'class' => 'btn btn-xs btn-primary',
                                                'style' => 'margin: 2px;width: 35px;',
                                            ]
                                        );
                                    },
                                    'update' => function ($url, $model, $key) {

                                        return Html::a(
                                            '<span class="bx bx-edit"></span>',
                                            [
                                                Url::toRoute(['photo-business-permit/update', 'id' => Yii::$app->hasher->encode($model->id)])
                                            ],
                                            [
                                                'class' => 'btn btn-xs btn-warning',
                                                'style' => 'margin: 2px;width: 35px;',
                                            ]
                                        );
                                    },
                                    'delete' => function ($action, $model, $key) {

                                        return Html::a(
                                            '<span class="bx bx-trash"></span>',
                                            [
                                                Url::toRoute(['photo-business-permit/delete', 'id' => Yii::$app->hasher->encode($model->id), 'parent' => Yii::$app->hasher->encode($model->id_business_permit)])
                                            ],
                                            [
                                                'data' => [
                                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                    'method' => 'post',
                                                    'params' => [], // <- extra level,
                                                ],
                                                'class' => 'btn btn-xs btn-danger',
                                                'style' => 'margin: 2px;width: 35px;',
                                            ]
                                        );
                                    },
                                    // 'download' => function ($url, $model, $key) {

                                    //     return Html::a(
                                    //         '<span class="bx bx-download"></span>',
                                    //         [
                                    //             Url::toRoute(['photo-business-permit/download', 'id' => Yii::$app->hasher->encode($model->id)])
                                    //         ],
                                    //         [
                                    //             'data' => [
                                    //                 'pjax' => 0
                                    //             ],
                                    //             'class' => 'btn btn-xs btn-warning',
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