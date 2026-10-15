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
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Construction Work'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="container mt-4 mb-4">
    <div class="card shadow-sm border-0 overflow-hidden" style="border-radius: 12px;">
        <div class="card-header pt-5 px-5 py-3" style="border-radius: 12px 12px 0 0;">
            <h3 class="mb-3"><i class="menu-icon tf-icons bx bx-buildings"></i> <?= Yii::t('app', 'List of Construction Work') ?></h3>
            <?php echo Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'class' => 'breadcrumb mb-0',
                'itemTemplate' => "<li class=\"breadcrumb-item\">{link}</li>\n",
                'activeItemTemplate' => "<li class=\"breadcrumb-item active\" aria-current=\"page\">{link}</li>\n",
            ]);
            ?>
        </div>
        <div class="row">
            <div class="col-lg-8 col-md-12 detail-data p-5 pb-0">
                <div class="mb-3">
                    <div class="card-headerr fw-bold">
                        <h4><i class='bx bx-info-circle'></i> <?= Yii::t('app', 'Project Information')?></h4>
                    </div>
                    <div class="pbj-view p-3 p-md-5">
                        <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                            <?= DetailView::widget([
                                'model' => $model,
                                'options' => ['class' => 'table table-striped table-borderless table-hover', 'style' => 'font-size:13px'],
                                'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => '-'],
                                'attributes' => [
                                    'name:ntext',
                                    ['attribute' => 'source_of_funds', 'value' => function ($model) {
                                        return $model->sourceOfFunds ? $model->sourceOfFunds->name : null;
                                    },],
                                    ['attribute' => 'hps', 'format' => ['decimal', 2]],
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="card-headerr">
                        <h4><i class='bx bx-task'></i> <?= Yii::t('app', 'Project Completion')?></h4>
                    </div>
                    <div class="pbj-view p-3 p-md-5">
                        <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                            <?= DetailView::widget([
                                'model' => $model,
                                'options' => ['class' => 'table table-striped table-borderless table-hover', 'style' => 'font-size:13px'],
                                'attributes' => [
                                    'work_start_date:date',
                                    'work_finished_date:date',
                                    'namasatker:ntext',
                                    'kodesatker',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="card-headerr">
                        <h4><i class='bx bx-map-pin'></i> <?= Yii::t('app', 'Project Location')?></h4>
                    </div>
                    <div class="pbj-view p-3 p-md-5">
                        <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                            <?= DetailView::widget([
                                'model' => $model,
                                'options' => ['class' => 'table table-striped table-borderless table-hover', 'style' => 'font-size:13px'],
                                'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => '-'],
                                'attributes' => [
                                    'provinsi',
                                    'kabupaten/kota',
                                    'kecamatan',
                                    'kelurahan',
                                    'alamat',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="card-headerr">
                        <h4><i class='bx bx-trophy'></i> <?= Yii::t('app', 'Winner')?></h4>
                    </div>
                    <div class="pbj-view p-3 p-md-5">
                        <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                            <?= DetailView::widget([
                                'model' => $model,
                                'options' => ['class' => 'table table-striped table-borderless table-hover', 'style' => 'font-size:13px'],
                                'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => '-'],
                                'attributes' => [
                                    'vendor_name:ntext',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 rekomen-data">
                <div class="card-rekomen p-3 m-3 mt-5 rounded-lg overflow-hidden">
                    <div class="card-header">
                        <h4 class=""><?= Yii::t('app', 'Other Works') ?></h4>
                    </div>
                    <div class="pbj-view">
                        <?php foreach ($modelpbj as $pbj) { ?>
                            <p><i class="icon-rekomen tf-icons bx bx-store"></i> <a class="rekomen-link" href="/pbj/view?id=<?= Yii::$app->hasher->encode($pbj['kodelelang']) ?>"><?= $pbj['name'] ?></a></p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .pbj-view p {
        display: flex;
        align-items: center;
        font-size: 14px;
        line-height: 1.4;
        margin-bottom: 18px;
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

    @media (max-width: 767px) {
        .detail-data, .rekomen-data {
            padding: 0 1.5rem;
        }
    }

    @media (max-width: 576px) {
        .card-rekomen .card-body {
            padding: 0 0.5rem;
        }
    }
</style>
