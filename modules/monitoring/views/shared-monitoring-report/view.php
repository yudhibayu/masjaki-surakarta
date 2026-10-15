<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\models\SharedMonitoringReport */

$this->title = $model->monitoringTask->name . ' ( Hingga ' . Yii::$app->formatter->asDatetime($model->expired_at) . ')';
$this->params['breadcrumbs'][] = ['label' => Yii::t('monitoring', 'Shared Monitoring Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="shared-monitoring-report-view">
            <?php if (strtotime($model->expired_at) > time()) : ?>
                <br>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <label for="linkdata">
                            <button type="button" class="btn btn-primary" onclick="navigator.clipboard.writeText('<?= Url::to(['data', 'code' => Yii::$app->hasher->encode($model->id)], true) ?>');alert('Tautan disalin')">
                                Klik disini untuk menyalin tautan
                            </button>
                        </label>
                        <a href="<?= Url::to(['data', 'code' => Yii::$app->hasher->encode($model->id)], true) ?>" id="linkdata">
                            <code>
                                <h4>
                                    <?= Url::to(['data', 'code' => Yii::$app->hasher->encode($model->id)], true) ?>
                                </h4>
                            </code>
                        </a>
                    </div>
                </div>
            <?php else : ?>
                <br>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <code>
                            <h4>
                                Tautan sudah tidak berlaku
                            </h4>
                        </code>
                    </div>
                </div>
            <?php endif; ?>
            <br>
            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                    'attributes' => [
                        'monitoringTask.name',
                        [
                            'attribute' => 'type',
                            'label' => 'Lingkup Laporan',
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
                        [
                            'attribute' => 'is_spec',
                            'label' => 'Laporan Spesifikasi',
                            'value' => function ($model) {
                                if ($model->is_spec) {
                                    return Yii::t('app', 'Yes');
                                }
                                return Yii::t('app', 'No');
                            },
                        ],
                        'created_at:datetime',
                        'expired_at:datetime',
                        // 'updated_at:datetime',
                        [
                            'attribute' => 'created_by',
                            'value' => $model->maker
                        ],
                        // [
                        //     'attribute' => 'updated_by',
                        //     'value' => $model->updater
                        // ],
                    ],
                ]) ?>
            </div>
            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), [$url_prev], ['class' => 'btn btn-warning']) ?>
                <?php if (strtotime($model->expired_at) > time()) : ?>
                    <?= Html::a(Yii::t('app', 'Cancel'), ['delete', 'id' => Yii::$app->hasher->encode($model->id)], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>