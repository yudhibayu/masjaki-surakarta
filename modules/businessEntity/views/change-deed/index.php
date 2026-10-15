<?php

use newerton\fancybox3\FancyBox;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\businessEntity\models\ChangeDeedSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Deed of Change');
// $this->params['breadcrumbs'][] = $this->title;
$id = Yii::$app->request->get('id');
?>
<?php
echo FancyBox::widget([
    'target' => '[data-fancybox="image-fancybox"]',
]);
?>

<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?> - List Data</h3>
    </div>
    <div class="card-body">
        <div class="change-deed-index">
            <p>
                <?= Html::a('<i class="menu-icon tf-icons bx bx-plus fw-bold"></i> ' . Yii::t('app', 'Create') . Yii::t('app', 'Deed of Change'), ['change-deed/create', 'id' => $id], ['class' => 'btn btn-success fw-bold']) ?>
            </p>
            <br>
            <?php Pjax::begin(['id' => 'change-deed-yea-pjax']); ?>

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <?= GridView::widget(
                    [
                        'dataProvider' => $changeDeedDataProvider,
                        'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover', 'style' => 'font-size:11px'],
                        'filterModel' => $changeDeedModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            'deed_number',
                            'date_of_deed',
                            'notary_public',

                            [
                                'attribute' => 'deed_file',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    $photoPath = ['/business-entity/change-deed/fetch-file', 'id' => Yii::$app->hasher->encode($model->id)];
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
                            // 'craeted_at',
                            //'updated_at:datetime',
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
                                'headerOptions' => ['width' => '150'],
                                'template' => '{view} {update} {delete}',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {

                                        return Html::a(
                                            '<span class="bx bx-show"></span>',
                                            [
                                                Url::toRoute(['change-deed/view', 'id' => Yii::$app->hasher->encode($model->id)])
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
                                                Url::toRoute(['change-deed/update', 'id' => Yii::$app->hasher->encode($model->id)])
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
                                                Url::toRoute(['change-deed/delete', 'id' => Yii::$app->hasher->encode($model->id), 'parent' => Yii::$app->hasher->encode($model->id_business_entity)])
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