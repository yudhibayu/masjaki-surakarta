<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\businessEntity\models\OwnerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Owners');
// $this->params['breadcrumbs'][] = $this->title;
$id = Yii::$app->request->get('id');
?>

<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="owner-index">
            <p>
                <?= Html::a('<i class="menu-icon tf-icons bx bx-plus fw-bold"></i> ' . Yii::t('app', 'Create') . Yii::t('app', 'Owners'), ['owner/create', 'id' => $id], ['class' => 'btn btn-success fw-bold']) ?>
            </p>
            <br>
            <?php Pjax::begin(['id' => 'owner-yea-pjax']); ?>

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <?= GridView::widget(
                    [
                        'dataProvider' => $ownerDataProvider,
                        'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover', 'style' => 'font-size:11px'],
                        'filterModel' => $ownerModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            'name',
                            'nik_passport_number',
                            'npwp',
                            'stock',
                            [
                                'attribute' => 'citizenship',
                                'value' => function ($model) {
                                    return $model->wniWna;
                                },
                            ],
                            //'ownership_type',
                            //'address',
                            //'province',
                            //'regency',
                            //'subdistrict',
                            //'ward',
                            //'created_at:datetime',
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
                                            '<span class="fas fa-eye"></span>',
                                            [
                                                Url::toRoute(['owner/view', 'id' => Yii::$app->hasher->encode($model->id)])
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
                                                Url::toRoute(['owner/update', 'id' => Yii::$app->hasher->encode($model->id)])
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
                                                Url::toRoute(['owner/delete', 'id' => Yii::$app->hasher->encode($model->id), 'parent' => Yii::$app->hasher->encode($model->id_business_entity)])
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