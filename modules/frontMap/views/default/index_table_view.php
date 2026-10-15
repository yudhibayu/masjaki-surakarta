<?php

use app\assets\FrontTableAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use miloschuman\highcharts\Highcharts;
use newerton\fancybox3\FancyBox;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

FrontTableAsset::register($this);
/* @var $this yii\web\View */
/* @var $searchModel app\modules\monitoring\models\MonitoringTaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('monitoring', 'Monitorings');
$this->params['breadcrumbs'][] = $this->title;
echo FancyBox::widget([
    'target' => '[data-fancybox="monitoring"]',
]);
?>
<div id="highchartdump" class="hidden" hidden>
    <?= Highcharts::widget() ?>
</div>
<div class="row">
    <div class="col-md-12">
        <span style="display:block; height: 80px;"></span>
    </div>
</div>
<div class="container">
    <section class="card">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="card-body">
            <div class="monitoring-task-index">

                <?php Pjax::begin(['id' => 'monitoring-task-yea-pjax']); ?>

                <?php echo $this->render('_search_table_view', ['model' => $searchModel, 'dropdown_data' => $dropdown_data]);
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

                            [
                                'attribute' => 'fiscal_year',
                                'filter' => $dropdown_data['fiscal_year'],
                            ],
                            'unit_name',
                            'name:ntext',
                            // 'idrup',
                            [
                                'attribute' => 'typeobject',
                                'value' => 'objectType.name',
                                'label' => 'Tipe Objek',
                            ],
                            [
                                'attribute' => 'kecamatan',
                                'value' => 'kecamatan.name',
                                'label' => 'Kecamatan',
                            ],
                            // 'kelurahan.name',
                            // 'unit_id',
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
                            //     'attribute' => 'latest_monitored_financial_budget_percent',
                            //     'value' => function ($model) {
                            //         return $model->latest_monitored_financial_budget_percent / 100;
                            //     },
                            //     'format' => ['percent', 2],
                            //     // 'format' => ['decimal', 2],
                            //     'filter' => false,
                            // ],
                            [
                                'attribute' => 'latest_monitored_physical_percent',
                                'value' => function ($model) {
                                    return $model->latest_monitored_physical_percent / 100;
                                },
                                'format' => ['percent', 2],
                                // 'format' => ['decimal', 2],
                                'filter' => false,
                            ],
                            //'created_at:datetime',
                            //'updated_at:datetime'

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => Yii::t('app', 'Action'),
                                'headerOptions' => ['width' => '100'],
                                'template' => '{view}',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {
                                        return Html::a(
                                            '<span class="bx bx-file"></span> Lihat',
                                            '#',
                                            [
                                                'class' => 'btn btn-xs btn-primary show-task-popup',
                                                // 'style' => 'margin: 2px;width: 35px;',
                                                'onclick' => 'showTask("' . Yii::$app->hasher->encode($model->id) . '")',
                                                'data-bs-toggle' => 'modal',
                                                'data-bs-target' => '#taskModal',
                                                'data-taskid' => Yii::$app->hasher->encode($model->id),
                                            ]
                                        );
                                    },
                                ],
                            ],
                        ],
                    ]); ?>
                </div>
                <?php Pjax::end(); ?>

            </div>
        </div>
    </section>
</div>
<!-- Modal -->
<div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body" id="target-modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>
<div class="hidden" hidden="true">
    <?php $form = ActiveForm::begin(['id' => 'task-form', 'action' => '/front-map/default/task-data']); ?>
    <?php ActiveForm::end(); ?>
</div>

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