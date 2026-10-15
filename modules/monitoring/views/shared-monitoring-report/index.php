<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\monitoring\models\SharedMonitoringReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('monitoring', 'Shared Monitoring Reports');
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="shared-monitoring-report-index">

            <?php Pjax::begin(['id' => 'shared-monitoring-report-yea-pjax']); ?>

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>
            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), ['/monitoring'], ['class' => 'btn btn-warning']) ?>
            </div>
            <br>
            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <?= GridView::widget(
                    [
                        'dataProvider' => $dataProvider,
                        'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            [
                                'attribute' => 'expired_at',
                                'filter' => false,
                                'label' => 'Tautan Laporan',
                                'format' => 'html',
                                'value' => function ($model) {
                                    if (strtotime($model->expired_at) > time()) {
                                        return Html::a(Yii::t('app', 'Link'), Url::to(['data', 'code' => Yii::$app->hasher->encode($model->id)], true), ['class' => 'btn btn-success btn-xs']);
                                    }
                                    return Html::a(Yii::t('app', 'Expired'), '', ['class' => 'btn btn-danger btn-xs']);
                                },
                            ],
                            [
                                'attribute' => 'is_spec',
                                'label' => 'Laporan Spesifikasi',
                                'value' => function ($model) {
                                    if ($model->is_spec) {
                                        return Yii::t('app', 'Yes');
                                    }
                                    return Yii::t('app', 'No');
                                },
                                'filter' => [
                                    0 => Yii::t('app', 'No'),
                                    1 => Yii::t('app', 'Yes'),
                                ],
                            ],
                            [
                                'attribute' => 'monitoringTask.name',
                                'label' => 'Nama Paket',
                                'format' => 'html',
                                'value' => function ($model) {
                                    return Html::a($model->monitoringTask->name, ['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($model->monitoringTask->id)]);
                                },
                            ],
                            [
                                'attribute' => 'type',
                                'label' => 'Lingkup Laporan',
                                'filter' => false,
                                'value' => function ($model) {
                                    if ($model->type == 'month') {
                                        if ($model->month < 1) {
                                            return 'Hanya Data Paket';
                                        }
                                        if ($model->month > 12) {
                                            return 'Semua Bulan';
                                        }
                                        return 'Bulan ' . $model->month;
                                    } else if ($model->type == 'week') {
                                        return 'Minggu ke ' . $model->week;
                                    } else if ($model->type == 'day') {
                                        return 'Tanggal ' . Yii::$app->formatter->asDate($model->day);
                                    }
                                }
                            ],
                            'created_at:datetime',
                            'expired_at:datetime',
                            // 'updated_at:datetime',
                            [
                                'attribute' => 'created_by',
                                'value' => 'maker',
                                'filter' => false
                            ],
                            /*[
                                'attribute' => 'updated_by', 
                                'value' => 'updater',
                                'filter' => false
                            ],*/

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => Yii::t('app', 'Action'),
                                'headerOptions' => ['width' => '100'],
                                'template' => '{view} {delete}',
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
                                    'update' => function ($url, $model, $key) {

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
                                    'delete' => function ($action, $model, $key) {
                                        if (strtotime($model->expired_at) > time()) {
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
                                                    'class' => 'btn btn-xs btn-danger',
                                                    'style' => 'margin: 2px;width: 35px;',
                                                ]
                                            );
                                        }
                                        return '';
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