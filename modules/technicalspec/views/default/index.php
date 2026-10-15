<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\technicalspec\models\DedDataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('technicalspec', 'DEDs');
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="ded-data-index">

            <p>
                <?php // echo Html::a(Yii::t('technicalspec', 'Create DED'), ['create'], ['class' => 'btn btn-success']) 
                ?>
                <?php // echo Html::a(Yii::t('technicalspec', 'Files (Shortcut, Delete Later)'), ['ded-files/index'], ['class' => 'btn btn-primary']) 
                ?>
            </p>

            <?php Pjax::begin(['id' => 'ded-data-yea-pjax']); ?>

            <p class="d-grid gap-2">
                <a class="btn btn-secondary" data-bs-toggle="collapse" href="#searchCollapse" role="button" aria-expanded="false" aria-controls="searchCollapse">
                    <?= '<i class="glyphicon glyphicon-search"></i> ' . Yii::t('app', 'Data Filter') ?>
                </a>
            </p>
            <div class="collapse" id="searchCollapse">
                <div class="card card-body">
                    <?php echo $this->render('_search', [
                        'model' => $searchModel,
                        'dropdown_data' => $dropdown_data,
                    ]);
                    ?>
                </div>
            </div>
            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <?= GridView::widget(
                    [
                        'dataProvider' => $dataProvider,
                        'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],


                            [
                                'attribute' => 'cco',
                                'value' => function ($model) {
                                    if ($model->cco == 0) {
                                        return 'MCO';
                                    }
                                    return 'CCO ' . $model->cco;
                                },
                            ],
                            'name',
                            // 'monitoring_task_id',
                            'monitoring_task_name:ntext',
                            // 'ref_component_category_id',
                            'ref_component_category_name',
                            //'ref_component_id',
                            'ref_component_name',
                            //'specification:ntext',
                            //'quantity',
                            //'notes:ntext',
                            //'created_at:datetime',
                            /*[
                            'attribute' => 'created_by', 
                            'value' => 'maker',
                            'filter' => false
                            ],*/
                            //'updated_at:datetime',
                            /*[
                            'attribute' => 'updated_by', 
                            'value' => 'updater',
                            'filter' => false
                            ],*/

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => Yii::t('app', 'Action'),
                                'headerOptions' => ['width' => '80'],
                                'template' => '{view} {update} {delete}',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {

                                        return Html::a(
                                            '<span class="fas fa-eye"></span>',
                                            [
                                                Url::toRoute(['view', 'id' => Yii::$app->hasher->encode($model->id)])
                                            ]
                                        );
                                    },
                                    'update' => function ($url, $model, $key) {

                                        return Html::a(
                                            '<span class="fas fa-edit"></span>',
                                            [
                                                Url::toRoute(['update', 'id' => Yii::$app->hasher->encode($model->id)])
                                            ]
                                        );
                                    },
                                    'delete' => function ($action, $model, $key) {

                                        return Html::a(
                                            '<span class="fas fa-trash"></span>',
                                            [
                                                Url::toRoute(['delete', 'id' => Yii::$app->hasher->encode($model->id)])
                                            ],
                                            [
                                                //'class' => 'btn btn-danger',
                                                'data' => [
                                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                    'method' => 'post',
                                                    'params' => [], // <- extra level
                                                ],
                                            ]
                                        );
                                    },
                                ],
                            ],
                        ],
                    ],
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