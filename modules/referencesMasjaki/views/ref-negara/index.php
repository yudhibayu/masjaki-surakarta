<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\referencesMasjaki\models\RefNegaraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Countries');
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="ref-negara-index">
            <p>
                <?= Html::a('<i class="menu-icon tf-icons bx bx-plus fw-bold"></i> ' .  Yii::t('app', 'Create') . Yii::t('app', 'Country'), ['create'], ['class' => 'btn btn-success fw-bold']) ?>
            </p>
            <br>

            <?php Pjax::begin(['id' => 'ref-negara-yea-pjax']); ?>

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <div class="table-responsive">
                    <?= GridView::widget(
                        [
                            'dataProvider' => $dataProvider,
                            'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                'name',
                                // 'uc_name',
                                // 'iso',
                                // 'iso3',
                                // 'numcode',
                                //'phonecode',
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
                                //'created_at:datetime',
                                //'updated_at:datetime',

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
                                                    Url::toRoute(['view', 'id' => Yii::$app->hasher->encode($model->id)])
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
                                                    Url::toRoute(['update', 'id' => Yii::$app->hasher->encode($model->id)])
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
                                                    Url::toRoute(['delete', 'id' => Yii::$app->hasher->encode($model->id)])
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