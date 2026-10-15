<?php

use app\modules\technicalspec\models\McoCco;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\monitoring\models\MonitoringTaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('monitoring', 'Monitorings');
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="monitoring-task-index">

            <?php Pjax::begin(['id' => 'monitoring-task-yea-pjax']); ?>

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>
            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                    'pager' => [
                        'firstPageLabel' => Yii::t('app', 'First'),
                        'lastPageLabel' => Yii::t('app', 'Last'),
                    ],
                    'responsive' => true,
                    'responsiveWrap' => false,
                    'filterModel' => $searchModel,
                    // 'showFooter' => true,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'name:ntext',
                        'idrup',
                        // 'unit_id',
                        'unit_name',
                        [
                            'attribute' => 'mcoccos',
                            'label' => 'MCO/CCO',
                            'value' => function($model){
                                if($model->mcoCcos){
                                    $ccos = $model->mcoCcos;
                                    $cco = $ccos[sizeof($ccos) - 1];
                                    return $cco->name;
                                }
                                return 'MCO';
                            }
                        ],
                        // 'start_date:date',
                        // 'end_date:date',
                        //'fiscal_year',
                        // [
                        //     'attribute' => 'latest_plan_financial_budget',
                        //     'format' => ['decimal',2],
                        // ],
                        // [
                        //     'attribute' => 'latest_plan_physical_percent',
                        //     'format' => ['decimal',2],
                        // ],
                        // [
                        //     'attribute' => 'budget',
                        //     'format' => ['decimal',2],
                        // ],
                        // [
                        //     'attribute' => 'latest_monitored_financial_budget',
                        //     'format' => ['decimal', 2],
                        // ],
                        // [
                        //     'attribute' => 'latest_monitored_physical_percent',
                        //     'format' => ['decimal', 2],
                        // ],
                        //'created_at:datetime',
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
                            'headerOptions' => ['width' => '100'],
                            'template' => '{view}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return Html::a(
                                        '<span class="fas fa-eye"></span>',
                                        [
                                            Url::toRoute(['view', 'id' => Yii::$app->hasher->encode($model->id)])
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
                                            Url::toRoute(['update', 'id' => Yii::$app->hasher->encode($model->id)])
                                        ],
                                        [
                                            'class' => 'btn btn-xs btn-warning',
                                            'style' => 'margin: 2px;width: 35px;',
                                        ]
                                    );
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
            <p>
                <i>Keterangan : </i><br>
                <i><span class="fas fa-eye btn btn-xs btn-primary"></span> <?= Yii::t('app', 'See') ?></i><br>
                <i><span class="fas fa-edit btn btn-xs btn-warning"></span> <?= Yii::t('app', 'Update') ?></i><br>
                <i><span class="fas fa-plus btn btn-xs btn-success"></span> Tambah Monitoring</i><br>
                <i><span class="fas fa-print btn btn-xs btn-secondary"></span> Print Berita Acara / Laporan Monitoring</i><br>
            </p>
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