<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use yii\widgets\Breadcrumbs;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model app\modules\rup\models\Pbj */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Construction Vacancies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="container mt-4 mb-4">
    <div class="card shadow-sm border-0 overflow-hidden" style="border-radius: 12px;">
        <div class="card-header pt-5 px-5 py-3" style="border-radius: 12px 12px 0 0;">
            <h3 class="mb-3"><i class="menu-icon tf-icons bx bx-buildings"></i> <?= Yii::t('app', 'List of Construction Vacancies') ?> </h3>
            <?php echo Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'class' => 'breadcrumb mb-0',
                'itemTemplate' => "<li class=\"breadcrumb-item\">{link}</li>\n",
                'activeItemTemplate' => "<li class=\"breadcrumb-item active\" aria-current=\"page\">{link}</li>\n",
            ]); ?>
        </div>
        <div class="row">
            <!-- Informasi Utama -->
            <div class="col-12 mt-3 px-5">
                <div class="card-header">
                    <h4><i class='bx bx-info-circle'></i> <?= Yii::t('app', 'Main Information')?></h4>
                </div>
                <div class="pbj-view p-3">
                    <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                            'attributes' => [
                                'name',
                                'description',
                                [
                                    'attribute' => 'construction_work',
                                    'value' => function ($model) {
                                        return $model->constructionWork ? $model->constructionWork->name : null;
                                    },
                                ],
                                [
                                    'attribute' => 'skills',
                                    'value' => function ($model) {
                                        return $model->skillsRelation ? $model->skillsRelation->name : null;
                                    },
                                ],
                                [
                                    'attribute' => 'education',
                                    'value' => function ($model) {
                                        return $model->educationRelation ? $model->educationRelation->name : null;
                                    },
                                ],
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>

            <!-- Kualifikasi -->
            <div class="col-12 mt-3 px-5">
                <div class="card-header">
                    <h4><i class='bx bx-task'></i> <?= Yii::t('app', 'Qualification')?></h4>
                </div>
                <div class="pbj-view p-3">
                    <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                            'attributes' => [
                                [
                                    'attribute' => 'age_range',
                                    'value' => function ($model) {
                                        return $model->age_range . ' tahun';
                                    },
                                ],
                                [
                                    'attribute' => 'age_range_upto',
                                    'value' => function ($model) {
                                        return $model->age_range_upto . ' tahun';
                                    },
                                ],
                                [
                                    'attribute' => 'work_experience',
                                    'value' => function ($model) {
                                        return $model->work_experience . ' tahun';
                                    },
                                ],
                                [
                                    'attribute' => 'quota',
                                    'value' => function ($model) {
                                        return $model->quota . ' orang';
                                    },
                                ],
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>

            <!-- Informasi Gaji -->
            <div class="col-12 mt-3 px-5">
                <div class="card-header">
                    <h4><i class='bx bx-dollar'></i> <?= Yii::t('app', 'Salary Information')?></h4>
                </div>
                <div class="pbj-view p-3">
                    <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                            'attributes' => [
                                [
                                    'attribute' => 'salary_range',
                                    'format' => 'currency',
                                ],
                                [
                                    'attribute' => 'salary_range_upto',
                                    'format' => 'currency',
                                ],
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>

            <!-- Tanggal & Waktu -->
            <div class="col-12 mt-3 px-5">
                <div class="card-header">
                    <h4><i class='bx bx-calendar'></i> <?= Yii::t('app', 'Date & Time') ?></h4>
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
        </div>
    </div>
</div>

<style>
    .card-header h4 {
        margin: 0;
    }
    .pbj-view p {
        display: flex;
        align-items: center;
        font-size: 14px;
        line-height: 1.4;
        margin-bottom: 18px;
    }
    .card {
        border-radius: 12px;
    }
    .card-rekomen {
        background-color: #F8F9FC;
        border-radius: 20px;
    }
    .icon-rekomen {
        font-size: 24px;
        color: var(--accent-color);
        margin-right: 8px;
    }
    .card-rekomen .card-body {
        background-color: #ffffff;
    }
    .card-rekomen .card {
        border: 1px solid #98211B;
    }
    .card-rekomen .card-header h3 {
        margin: 0;
    }
    .card-rekomen .pbj-view {
        padding: 1rem;
    }
    .card-rekomen .card {
        border-radius: 0.5rem;
    }
    .rekomen-link {
        color: black;
        transition: color 0.3s ease, text-decoration 0.3s ease;
    }
    .rekomen-link:hover {
        color: var(--accent-color);
        text-decoration: underline;
    }
</style>
