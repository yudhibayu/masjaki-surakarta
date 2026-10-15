<?php

use app\modules\monitoring\models\MonitoringTask;
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
            <div class="row">
                <div class="col-md-4 text-start">
                    <p>
                        <?php if (Yii::$app->user->can('Administrator') || Yii::$app->user->can('OPD')) : ?>
                            <?= Html::a(Yii::t('monitoring', 'Create Monitoring'), ['create'], ['class' => 'btn btn-success']) ?>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="col-md-4 text-center">
                    <p></p>
                </div>
                <div class="col-md-4 text-end">
                    <div>
                        <p>
                            <?= Html::a(Yii::t('monitoring', 'Shared Monitoring Reports'), ['shared-monitoring-report/index'], ['class' => 'btn btn-secondary']) ?>
                        </p>
                    </div>
                </div>

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

                            'unit_name',
                            [
                                'attribute' => 'name',
                                'format' => 'ntext',
                                'label' => 'Nama Paket',
                            ],
                            // 'idrup',
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
                            [
                                'attribute' => 'latest_plan_physical_percent',
                                'value' => function ($model) {
                                    return $model->latest_plan_physical_percent / 100;
                                },
                                'format' => ['percent', 2],
                                // 'format' => ['decimal', 2],
                                'filter' => false,
                            ],
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
                                'template' => '{view} {edit} {addmonitoring} {print}',
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
                                        if (Yii::$app->user->can('Administrator') || Yii::$app->user->can('OPD')) {
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
                                        }
                                        return '';
                                    },
                                    'addmonitoring' => function ($url, $model, $key) {
                                        if (Yii::$app->user->can('Administrator') || Yii::$app->user->can('OPD') || Yii::$app->user->can('Supervisor')) {
                                            return Html::a(
                                                '<span class="fas fa-plus"></span>',
                                                [
                                                    Url::toRoute(['progress/create', 'id' => Yii::$app->hasher->encode($model->id)])
                                                ],
                                                [
                                                    'class' => 'btn btn-xs btn-success',
                                                    'target' => '_blank',
                                                    'data' => [
                                                        'pjax' => 0,
                                                    ],
                                                    'style' => 'margin: 2px;width: 35px;',
                                                ]
                                            );
                                        }
                                        return '';
                                    },
                                    'print' => function ($url, $model, $key) {
                                        $button = Html::button(
                                            '<span class="fas fa-print"></span>',
                                            [
                                                'id' => 'dropdownMenuButton' . Yii::$app->hasher->encode($model->id),
                                                'class' => 'btn btn-xs btn-secondary dropdown-toggle',
                                                'type' => 'button',
                                                'data' => [
                                                    'bs-toggle' => 'dropdown',
                                                    'bs-target' => '#dropdownMenu' . Yii::$app->hasher->encode($model->id),
                                                ],
                                                'aria-expanded' => "false",
                                                'style' => 'margin: 2px;width: 35px;',
                                            ]
                                        );
                                        $btnbast = Html::a(
                                            Yii::t('monitoring', 'Generate BAST'),
                                            '#',
                                            [
                                                'data' => [
                                                    'bs-toggle' => 'modal',
                                                    'bs-target' => '#bastModal' . Yii::$app->hasher->encode($model->id),
                                                ],
                                                'class' => 'dropdown-item',
                                            ]
                                        );
                                        $btnreport = Html::a(
                                            Yii::t('monitoring', 'Generate Monitoring Result'),
                                            '#',
                                            [
                                                'data' => [
                                                    'bs-toggle' => 'modal',
                                                    'bs-target' => '#reportModal' . Yii::$app->hasher->encode($model->id),
                                                ],
                                                'class' => 'dropdown-item',
                                            ]
                                        );
                                        $dropdowns = $button .
                                            '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . Yii::$app->hasher->encode($model->id) . '" id="dropdownMenu' . Yii::$app->hasher->encode($model->id) . '">' .
                                            '<li>' . $btnbast . '</li>' .
                                            '<li>' . $btnreport . '</li>' .
                                            '</ul>';
                                        $modals = $this->render('_print_modal', ['model' => $model]);
                                        if (Yii::$app->user->can('Administrator') || Yii::$app->user->can('OPD')) {
                                            return $dropdowns . $modals;
                                        }
                                        return '';
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