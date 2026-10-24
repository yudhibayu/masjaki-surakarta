<?php

use app\modules\monitoring\models\TertibPengawasan;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rekap Pengawasan Tertib';
$this->params['breadcrumbs'][] = $this->title;
$filters = ['' => 'Semua status publikasi'] + ['0' => 'Belum dipublikasikan', '1' => 'Dipublikasikan'];
$years = ['' => 'Semua tahun'] + $years;
?>
<div class="tertib-index">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h1 class="h3 mb-1"><?= Html::encode($this->title) ?></h1>
            <p class="text-muted mb-0">Kelola kegiatan, checklist, temuan, tindak lanjut, dan dokumen pengawasan.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <?php if (Yii::$app->user->can('Administrator') || Yii::$app->user->can('OPD') || Yii::$app->user->can('Team')): ?>
                <?= Html::a('Tambah Paket Pekerjaan', ['/monitoring/default/create'], ['class' => 'btn btn-outline-primary']) ?>
            <?php endif; ?>
            <?= Html::a('Tambah Pengawasan', ['create'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?= Html::beginForm(['index'], 'get', ['class' => 'row g-2 align-items-end mb-3']) ?>
        <div class="col-12 col-sm-3">
            <?= Html::label('Tahun anggaran', 'tertib-filter-year', ['class' => 'form-label']) ?>
            <?= Html::dropDownList('tahun', $params['tahun'], $years, ['id' => 'tertib-filter-year', 'class' => 'form-select']) ?>
        </div>
        <div class="col-12 col-sm-3">
            <?= Html::label('Publikasi', 'tertib-filter-publication', ['class' => 'form-label']) ?>
            <?= Html::dropDownList('publikasi', $params['publikasi'], $filters, ['id' => 'tertib-filter-publication', 'class' => 'form-select']) ?>
        </div>
        <div class="col-12 col-sm-3">
            <?= Html::label('Status pelaksanaan', 'tertib-filter-status', ['class' => 'form-label']) ?>
            <?= Html::dropDownList('status', $params['status'], ['' => 'Semua status'] + TertibPengawasan::statusOptions(), ['id' => 'tertib-filter-status', 'class' => 'form-select']) ?>
        </div>
        <div class="col-12 col-sm-3">
            <?= Html::label('Paket atau penyedia', 'tertib-filter-q', ['class' => 'form-label']) ?>
            <?= Html::textInput('q', $params['q'], ['id' => 'tertib-filter-q', 'class' => 'form-control', 'maxlength' => 120]) ?>
        </div>
        <div class="col-12 d-flex gap-2">
            <?= Html::submitButton('Terapkan filter', ['class' => 'btn btn-outline-primary']) ?>
            <?= Html::a('Reset', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
        </div>
    <?= Html::endForm() ?>

    <div class="card">
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'emptyText' => 'Belum ada kegiatan pengawasan.',
                'tableOptions' => ['class' => 'table table-striped table-bordered align-middle'],
                'columns' => [
                    [
                        'label' => 'Tahun',
                        'value' => function ($model) {
                            return $model->monitoringTask ? $model->monitoringTask->fiscal_year : '—';
                        },
                    ],
                    [
                        'label' => 'Paket pekerjaan',
                        'format' => 'html',
                        'value' => function ($model) {
                            $name = $model->monitoringTask ? $model->monitoringTask->name : 'Paket tidak tersedia';
                            return Html::a(Html::encode($name), ['view', 'id' => $model->id]);
                        },
                    ],
                    'tanggal_pengawasan:date',
                    [
                        'label' => 'Metode',
                        'value' => function ($model) {
                            return TertibPengawasan::metodeOptions()[$model->metode_pengawasan] ?? $model->metode_pengawasan;
                        },
                    ],
                    [
                        'label' => 'Status',
                        'value' => function ($model) {
                            return TertibPengawasan::statusOptions()[$model->status_pelaksanaan] ?? $model->status_pelaksanaan;
                        },
                    ],
                    [
                        'label' => 'Publikasi',
                        'format' => 'html',
                        'value' => function ($model) {
                            $label = $model->is_public ? 'Dipublikasikan' : 'Internal';
                            $class = $model->is_public ? 'text-bg-success' : 'text-bg-secondary';
                            return Html::tag('span', $label, ['class' => 'badge ' . $class]);
                        },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update}',
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
