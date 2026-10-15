<?php

use app\modules\businessEntity\models\Administrator;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\businessEntity\models\AdministratorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Administrators');
// $this->params['breadcrumbs'][] = $this->title;
$id = Yii::$app->request->get('id');
?>

<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="administrator-index">
            <p>
                <?= Html::a('<i class="menu-icon tf-icons bx bx-plus fw-bold"></i> ' .  Yii::t('app', 'Create') . Yii::t('app', 'Administrators'), ['administrator/create', 'id' => $id], ['class' => 'btn btn-success fw-bold']) ?>
            </p>
            <br>

            <?php Pjax::begin(['id' => 'administrator-yea-pjax']); ?>

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <div class="table-responsive">
                    <?= GridView::widget(
                        [
                            'dataProvider' => $administratorDataProvider,
                            'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover', 'style' => 'font-size:11px'],
                            'filterModel' => $administratorModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                'name',
                                'nik_passport_number',
                                'npwp',
                                'employment_bpjs_number',
                                'number_bpjs_health',
                                [
                                    'attribute' => 'citizenship',
                                    'value' => function ($model) {
                                        return $model->wniWna;
                                    },
                                ], [
                                    'attribute' => 'status',
                                    'value' => 'statusActive',
                                ],
                                //'status',
                                //'position',
                                //'served_since',
                                //'served_arrived',
                                //'type_of_management',
                                //'citizenship',
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
                                                '<span class="bx bx-show"></span>',
                                                [
                                                    Url::toRoute(['administrator/view', 'id' => Yii::$app->hasher->encode($model->id)])
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
                                                    Url::toRoute(['administrator/update', 'id' => Yii::$app->hasher->encode($model->id)])
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
                                                    Url::toRoute(['administrator/delete', 'id' => Yii::$app->hasher->encode($model->id), 'parent' => Yii::$app->hasher->encode($model->id_business_entity)])
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