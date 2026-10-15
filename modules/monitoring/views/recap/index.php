<?php

use app\modules\monitoring\models\MonitoringTask;
use kartik\export\ExportMenu;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\monitoring\models\MonitoringTaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('monitoring', 'Recap Monitoring');
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="monitoring-task-index">
            <?php
            $gridColumns = [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'header' => 'No.',
                ],
                [
                    'attribute' => 'name',
                    'format' => 'ntext',
                    'label' => 'Nama Paket',
                ],
                [
                    'attribute' => 'object_type_name',
                    'label' => 'Jenis Objek',
                ],
                [
                    'attribute' => 'unit_name',
                    'label' => 'OPD',
                ],
                [
                    'attribute' => 'team_name',
                    'label' => 'Kelompok',
                ],
                [
                    'attribute' => 'date_month',
                    'label' => 'S/D Bulan',
                ],
                [
                    'attribute' => 'order_no',
                    'label' => 'Minggu Ke',
                ],
                [
                    'attribute' => 'final_price',
                    'format' => ['decimal', 2],
                    'label' => 'Nilai Kontrak (Rp.)',
                ],
                [
                    'attribute' => 'contractor_name',
                    'label' => 'Pelaksana',
                ],
                [
                    // 'attribute' => 'monitoringTask.fullRegistrationBPJS',
                    'value' => function ($model) {
                        $task = MonitoringTask::findOne(['id' => $model['monitoring_task_id']]);
                        // return $task->getFullRegistrationBPJS();
                        $bpjstk_number = $task->bpjstk_number ? Html::encode($task->bpjstk_number) : '-';
                        $bpjstk_contrib_amount = $task->bpjstk_contrib_amount ? Yii::$app->formatter->asCurrency($task->bpjstk_contrib_amount, 'IDR') : '-';
                        $bpjstk_number_workers_protected = $task->bpjstk_number_workers_protected ? Yii::$app->formatter->asDecimal($task->bpjstk_number_workers_protected, 0) : '-';
                        $list = Html::ul([
                            "Nomor Registrasi BPJS TK : " . Html::encode($bpjstk_number),
                            "Besar Iuran BPJS TK : " . $bpjstk_contrib_amount,
                            "Personel Terdaftar BPJS TK : " . $bpjstk_number_workers_protected,
                        ]);
                        return $list;
                    },
                    'format' => 'raw',
                    'label' => 'Registrasi BPJS TK',
                ],
                [
                    'attribute' => 'targetandrealisasi',
                    'label' => 'Target & Realisasi (Fisik & Keuangan)',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $task = MonitoringTask::findOne(['id' => $model['monitoring_task_id']]);

                        $list = Html::ul([
                            "Target Fisik: " . Yii::$app->formatter->asDecimal($task->latest_plan_physical_percent, 2),
                            "Realisasi Fisik : " . Yii::$app->formatter->asDecimal($task->latest_monitored_physical_percent, 2),

                            "Target Keuangan: " . Yii::$app->formatter->asCurrency($task->latest_plan_financial_budget, 'IDR'),
                            "Realisasi Keuangan : " . Yii::$app->formatter->asCurrency($task->latest_monitored_financial_budget, 'IDR'),
                        ]);
                        return $list;
                    },
                ],
                [
                    'attribute' => 'deviasi',
                    'format' => ['decimal', 2],
                    'label' => 'Deviasi Fisik',
                ],
                [
                    'attribute' => 'end_date',
                    'format' => 'date',
                    'label' => 'Rencana Selesai',
                ],
                [
                    // 'attribute' => 'description',
                    'value' => function ($model) {
                        $task = MonitoringTask::findOne(['id' => $model['monitoring_task_id']]);
                        if (isset($task->latestMonitoring->description)) {
                            return $task->latestMonitoring->description;
                        }
                        return '';
                    },
                    'format' => 'ntext',
                    'label' => 'Keterangan',
                ],
            ];
            ?>
            <?php Pjax::begin(['id' => 'monitoring-task-yea-pjax']); ?>

            <?php echo $this->render('_search', ['model' => $searchModel, 'dropdown_data' => $dropdown_data]);
            ?>
            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <hr>
                <?= ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumns,
                    'dropdownOptions' => [
                        'label' => 'Export',
                        'class' => 'btn btn-outline-secondary btn-default'
                    ],
                    'exportConfig' => [
                        ExportMenu::FORMAT_TEXT => false,
                        ExportMenu::FORMAT_CSV => false,
                        ExportMenu::FORMAT_HTML => false,
                        ExportMenu::FORMAT_PDF => false,
                        ExportMenu::FORMAT_EXCEL => false
                    ],
                ]); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                    'pager' => [
                        'firstPageLabel' => Yii::t('app', 'First'),
                        'lastPageLabel' => Yii::t('app', 'Last'),
                    ],
                    'responsive' => true,
                    'responsiveWrap' => false,
                    // 'filterModel' => $searchModel,
                    // 'showFooter' => true,
                    'columns' => $gridColumns,
                ]); ?>
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