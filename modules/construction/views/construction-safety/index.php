<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use kartik\widgets\DatePicker;
use newerton\fancybox3\FancyBox;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\construction\models\ConstructionSafetySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Construction Safeties Report');
// $this->params['breadcrumbs'][] = ['label' => 'Construction Works', 'url' => ['./construction-work']];
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
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="construction-safety-index">

            <?php if (Yii::$app->user->can('Badan Usaha')): ?>
                <p>
                    <?= Html::a('<i class="menu-icon tf-icon bx bx-plus fw-bold"></i> ' . Yii::t('app', 'Create') . Yii::t('app', 'Construction Safeties Report'), ['/construction/construction-safety/create', 'id' => $id], ['class' => 'btn btn-success fw-bold']) ?>
                </p>
            <?php endif; ?>

            <?php Pjax::begin(['id' => 'construction-safety-yea-pjax']); ?>

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <div class="table-responsive">
                    <?= GridView::widget(
                        [
                            'dataProvider' => $constructionSafetyDataProvider,
                            'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover'],
                            'filterModel' => $constructionSafetyModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                // 'id_construction_work',
                                ['attribute' => 'date', 'format' => 'date', 'filter' => DatePicker::widget(['language' => 'id', 'model' => $constructionSafetyModel, 'attribute' => 'date', 'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd']]),],
                                [
                                    'attribute' => 'report_file',
                                    'format' => 'raw',
                                    'filter' => false,
                                    'value' => function ($model) {
                                        $photoPath = ['/construction/construction-safety/fetch-file', 'id' => Yii::$app->hasher->encode($model->id)];
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
                                        } elseif ((strtolower($model->file_extension) == 'png' || 'jpg' || 'jpeg')) {
                                            $photoUrl = Url::to($photoPath);
                                            return Html::a(
                                                Html::img($photoUrl, ['width' => '100px']),
                                                $photoUrl,
                                                [
                                                    'class' => 'badge',
                                                    'data' => [
                                                        'pjax' => 0,
                                                        'fancybox' => "image-fancybox",
                                                    ],
                                                ]
                                            );
                                        }
                                    },
                                ],
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
                                    'headerOptions' => ['width' => '150'],
                                    'template' => '{view} {update} {delete}',
                                    'buttons' => [
                                        'view' => function ($url, $model, $key) {

                                            return Html::a(
                                                '<span class="bx bx-show"></span>',
                                                [
                                                    Url::toRoute(['/construction/construction-safety/view', 'id' => Yii::$app->hasher->encode($model->id)])
                                                ],
                                                [
                                                    'class' => 'btn btn-xs btn-primary',
                                                    'style' => 'margin: 2px;width: 35px;',
                                                ]
                                            );
                                        },
                                        'update' => function ($url, $model, $key) {
                                            if (Yii::$app->user->can('Badan Usaha')) {
                                                return Html::a(
                                                    '<span class="bx bx-edit"></span>',
                                                    [
                                                        Url::toRoute(['/construction/construction-safety/update', 'id' => Yii::$app->hasher->encode($model->id)])
                                                    ],
                                                    [
                                                        'class' => 'btn btn-xs btn-warning',
                                                        'style' => 'margin: 2px;width: 35px;',
                                                    ]
                                                );
                                            }
                                        },
                                        'delete' => function ($action, $model, $key) {
                                            if (Yii::$app->user->can('Badan Usaha')) {
                                                return Html::a(
                                                    '<span class="bx bx-trash"></span>',
                                                    [
                                                        Url::toRoute(['/construction/construction-safety/delete', 'id' => Yii::$app->hasher->encode($model->id), 'parent' => Yii::$app->hasher->encode($model->id_construction_work)])
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
                                            }
                                        },
                                    ],
                                ],
                            ],
                        ]
                    ); ?>
                </div>
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