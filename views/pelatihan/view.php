<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use yii\widgets\Breadcrumbs;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model app\modules\rup\models\Pbj */

$this->title = $model->training_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'List of Work Training'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="container mt-4 mb-4">
    <div class="card shadow-sm border-0 overflow-hidden" style="border-radius: 12px;">
        <div class="card-header pt-5 px-5 py-3" style="border-radius: 12px 12px 0 0;">
            <h3 class="mb-3"><i class="menu-icon tf-icons bx bx-buildings"></i><?= Yii::t('app', 'List of Work Training') ?></h3>
            <?php echo Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'class' => 'breadcrumb mb-0',
                'itemTemplate' => "<li class=\"breadcrumb-item\">{link}</li>\n",
                'activeItemTemplate' => "<li class=\"breadcrumb-item active\" aria-current=\"page\">{link}</li>\n",
            ]); ?>
        </div>

        <!-- Informasi Utama -->
        <div class="row px-4">
            <div class="col-12 mt-3">
                <div class="card-header">
                    <h4><i class='bx bx-info-circle'></i> <?= Yii::t('app', 'Main Information')?></h4>
                </div>
                <div class="pbj-view p-3">
                    <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                            'attributes' => [
                                'training_name',
                                'person_responsible_name',
                                [
                                    'attribute' => 'kuota',
                                    'value' => function ($model) {
                                        return $model->kuota . ' orang';
                                    },
                                ],
                                [
                                    'attribute' => 'skills',
                                    'value' => function ($model) {
                                        return $model->skillsRelation ? $model->skillsRelation->name : null;
                                    },
                                ],
                                'description',
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kualifikasi -->
        <!-- <div class="row mt-3">
            <div class="col-12">
                <div class="card-header">
                    <h4><i class='bx bx-task'></i> Kualifikasi</h4>
                </div>
                <div class="pbj-view p-3">
                    <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                            'attributes' => [
                                'training_name',
                                'person_responsible_name',
                                [
                                    'attribute' => 'kuota',
                                    'value' => function ($model) {
                                        return $model->kuota . ' orang';
                                    },
                                ],
                                [
                                    'attribute' => 'skills',
                                    'value' => function ($model) {
                                        return $model->skillsRelation ? $model->skillsRelation->name : null;
                                    },
                                ],
                                'year_name',
                                'description',
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- Tanggal & Waktu -->
        <!-- <div class="row mt-3">
            <div class="col-12">
                <div class="card-header">
                    <h4><i class='bx bx-calendar'></i> Tanggal & Waktu</h4>
                </div>
                <div class="pbj-view p-3">
                    <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                            'attributes' => [
                                'start_date',
                                'date_of_completion',
                                [
                                    'attribute' => 'start_time',
                                    'format' => 'time',
                                ],
                                [
                                    'attribute' => 'end_time',
                                    'format' => 'time',
                                ],
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- Lokasi -->
        <div class="row px-4 mt-3">
            <div class="col-12">
                <div class="card-header">
                    <h4><i class='bx bx-calendar'></i> Lokasi Pelatihan</h4>
                </div>
                <div class="pbj-view p-3">
                    <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                            'attributes' => [
                                'country',
                                'province',
                                'regency',
                                'subdistrict',
                                'ward',
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>