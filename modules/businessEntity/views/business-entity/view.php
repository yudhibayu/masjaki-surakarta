<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\widgets\DetailView;
use newerton\fancybox3\FancyBox;

/* @var $this yii\web\View */
/* @var $model app\modules\businessEntity\models\BusinessEntity */

$this->title = $model->company_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Business Entity'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<?php
echo FancyBox::widget([
    'target' => '[data-fancybox="image-fancybox"]',
]);
?>
<?= $this->registerJs(
    <<< EOT_JS_CODE
    
    $(document).ready(function() {
        $('.setnavtab').on('click', function() {
            let tab = $(this).data('tab');
            $.ajax({
                url: "set-tab",
                type: "POST",
                data: "tab=" + tab,
                success: function(data) {
                    console.log(data);
                }
            });
        });

        $('.setnavtabakta').on('click', function() {
            let tab = $(this).data('tab');
            $.ajax({
                url: "set-tab-akta",
                type: "POST",
                data: "tab=" + tab,
                success: function(data) {
                    console.log(data);
                }
            });
        });

         $('.setnavtabmanajerial').on('click', function() {
            let tab = $(this).data('tab');
            $.ajax({
                url: "set-tab-manajerial",
                type: "POST",
                data: "tab=" + tab,
                success: function(data) {
                    console.log(data);
                }
            });
        });
    });

EOT_JS_CODE

);
?>

<style>
    @media (max-width: 576px) {
        .tabs-wrapper {
            overflow-x: auto;
            overflow-y: hidden;
            white-space: nowrap;
        }

        .nav-tabs {
            display: inline-flex;
            padding: 0;
            margin: 0;
            flex-wrap: nowrap;
        }

        .nav-tabs .nav-item {
            flex: 0 0 auto;
        }

        .nav-tabs .nav-link {
            display: inline-block;
            padding: 0.5rem 1rem;
            white-space: nowrap;
        }
    }

    .nav-tabs .nav-link {
        border: none;
        color: #000;
        font-weight: bold;
        border-radius: 5px;
    }

    .nav-tabs .nav-link.active {
        color: #fff;
        background-color: var(--bs-primary);
        border-radius: 5px;
    }
</style>
<section class="card">
    <div class="row">
        <div class="col-sm-12 mb-3">
            <div class="tabs-wrapper">
                <ul class="nav nav-tabs nav-justified custom-nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link setnavtab <?= $view_tab['nav'][0] ?> " id="identitas-tab"
                            data-bs-toggle="tab" data-bs-target="#identitas" type="button" role="tab"
                            aria-controls="identitas" aria-selected="true" data-tab="0">
                            <i class='bx bx-user'></i> Identitas
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link setnavtab <?= $view_tab['nav'][1] ?> " id="home-tab"
                            data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home"
                            aria-selected="false" data-tab="1">
                            <i class='bx bx-briefcase'></i> Izin Usaha
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link setnavtab <?= $view_tab['nav'][2] ?> " id="akta-tab"
                            data-bs-toggle="tab" data-bs-target="#akta" type="button" role="tab" aria-controls="akta"
                            aria-selected="false" data-tab="2">
                            <i class='bx bx-book'></i> Akta
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link setnavtab <?= $view_tab['nav'][3] ?> " id="manager-tab"
                            data-bs-toggle="tab" data-bs-target="#manager" type="button" role="tab"
                            aria-controls="manager" aria-selected="false" data-tab="3">
                            <i class='bx bx-cog'></i> Manajerial
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link setnavtab <?= $view_tab['nav'][4] ?> " id="pengalaman-tab"
                            data-bs-toggle="tab" data-bs-target="#pengalaman" type="button" role="tab"
                            aria-controls="pengalaman" aria-selected="false" data-tab="4">
                            <i class='bx bx-trophy'></i> Pengalaman Pekerjaan
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane <?= $view_tab['tab'][0] ?>" id="identitas" role="tabpanel" aria-labelledby="identitas-tab">
            <div class="card-header">
                <h3><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="card-body">
                <div class="business-entity-view">
                    <div class="text-right">
                        <?= Html::a(Yii::t('app', 'Back to List'), [$url_prev], ['class' => 'btn btn-outline-secondary']) ?>
                        <?php if ($model->account_verification !== $model::BLACKLIST) : ?>
                            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?>
                        <?php endif; ?>
                        <?php if (Yii::$app->user->can('Administrator')): ?>
                            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => Yii::$app->hasher->encode($model->id)], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        <?php endif; ?>
                    </div>
                    <br>
                    <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                        <div class="table-responsive">
                            <?= DetailView::widget([
                                'model' => $model,
                                'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                                'attributes' => [
                                    'company_name',
                                    'company_address',
                                    'username',
                                    'email:email',
                                    'npwp',
                                    [
                                        'attribute' => 'form_of_business',
                                        'value' => function ($model) {
                                            return $model->formOfBusiness ? $model->formOfBusiness->name : null;
                                        },
                                    ],
                                    [
                                        'attribute' => 'photo',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $photoPath = ['/business-entity/business-entity/fetch-file', 'id' => Yii::$app->hasher->encode($model->id)];
                                            if (($model->file_extension != NULL || $model->file_extension != '') && (strtolower($model->file_extension) == 'png' || 'jpg' || 'jpeg')) {
                                                $photoUrl = Url::to($photoPath);
                                                return Html::a(
                                                    Html::img($photoUrl, ['width' => '100px']),
                                                    $photoUrl,
                                                    [
                                                        'class' => 'badge',
                                                        'data' => [
                                                            'pjax' => 0,
                                                            'fancybox' => "image-fancybox",
                                                        ],
                                                    ]
                                                );
                                            } else {
                                                return " ";
                                            }
                                        },
                                    ],
                                    [
                                        'attribute' => 'phone_number',
                                        'value' => function ($model) {
                                            return '+62 ' . ltrim($model->phone_number, '0');
                                        },
                                    ],
                                    'pkp_confirmation_number',
                                    'website',
                                    'fax',
                                    'postal_code',
                                    'country',
                                    'province',
                                    'regency',
                                    'subdistrict',
                                    'ward',
                                    'created_at:datetime',
                                    'updated_at:datetime',
                                    [
                                        'attribute' => 'created_by',
                                        'value' => $model->maker
                                    ],
                                    [
                                        'attribute' => 'updated_by',
                                        'value' => $model->updater
                                    ],
                                ],
                            ]) ?>
                        </div>
                    </div>
                    <div class="text-right">
                        <?= Html::a(Yii::t('app', 'Back to List'), [$url_prev], ['class' => 'btn btn-outline-secondary']) ?>

                        <?php if ($model->account_verification !== $model::BLACKLIST) : ?>
                            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?>
                        <?php endif; ?>
                        <?php if (Yii::$app->user->can('Administrator')): ?>
                            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => Yii::$app->hasher->encode($model->id)], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php if (Yii::$app->user->can('Administrator')): ?>

                <section class="card">
                    <div class="card-header">
                        <h3>Pekerjaan Konstruksi</h3>
                    </div>
                    <div class="card-body">
                        <div class="pbj-business-entity-index">

                            <?php Pjax::begin(['id' => 'pbj-business-entity-yea-pjax']); ?>

                            <?php // echo $this->render('_search', ['model' => $searchModel]); 
                            ?>

                            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                                <div class="table-responsive">
                                    <?= GridView::widget(
                                        [
                                            'dataProvider' => $pbjBusinessEntityDataProvider,
                                            'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                                            'filterModel' => $pbjBusinessEntityModel,
                                            'columns' => [
                                                ['class' => 'yii\grid\SerialColumn'],
                                                [
                                                    'class' => 'yii\grid\ActionColumn',
                                                    'header' => Yii::t('app', 'Action'),
                                                    'headerOptions' => ['width' => '100'],
                                                    'template' => '{view}',
                                                    'buttons' => [
                                                        'view' => function ($url, $model, $key) {
                                                            return Html::a(
                                                                '<span class="bx bx-show"></span>',
                                                                [
                                                                    Url::toRoute(['/rup/pbj/view', 'id' => Yii::$app->hasher->encode($model->kode_pbj)])
                                                                ],
                                                                [
                                                                    'data' => ['pjax' => 0],
                                                                    'class' => 'btn btn-xs btn-primary',
                                                                    'style' => 'margin: 2px;width: 35px;',
                                                                ]
                                                            );
                                                        },
                                                    ],
                                                ],
                                                'nama_paket',
                                            ],
                                        ]
                                    ); ?>
                                </div>
                            </div>
                            <?php Pjax::end(); ?>

                        </div>
                    </div>
                </section>
            <?php endif; ?>
        </div>

        <div class="tab-pane <?= $view_tab['tab'][1] ?>" id="home" role="tabpanel" aria-labelledby="home-tab">
            <?= $this->render('../business-permit/index', [
                'businessPermitModel' => $businessPermitModel,
                'businessPermitDataProvider' => $businessPermitDataProvider,
            ]) ?>
        </div>

        <div class="tab-pane <?= $view_tab['tab'][2] ?>" id="akta" role="tabpanel" aria-labelledby="akta-tab">
            <div class="akta2-tab">
                <ul class="nav nav-tabs border-0 rounded-3 shadow-sm mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link rounded-3 setnavtabakta <?= $view_tab_akta['nav'][0] ?>" id="akta1-tab"
                            data-bs-toggle="tab" href="#akta1" role="tab" aria-controls="akta1" aria-selected="true"
                            data-tab="0">
                            <i class='bx bx-file me-2'></i> Akta Pendirian
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded-3 setnavtabakta <?= $view_tab_akta['nav'][1] ?>" id="akta2-tab"
                            data-bs-toggle="tab" href="#akta2" role="tab" aria-controls="akta2" aria-selected="true"
                            data-tab="1">
                            <i class='bx bx-edit me-2'></i> Akta Perubahan
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane <?= $view_tab_akta['tab'][0] ?>" id="akta1" role="tabpanel"
                        aria-labelledby="akta1-tab">
                        <?= $this->render('../deed-of-incorporation/index', [
                            'deedOfIncorporationModel' => $deedOfIncorporationModel,
                            'deedOfIncorporationDataProvider' => $deedOfIncorporationDataProvider,
                        ]) ?>
                    </div>
                    <div class="tab-pane <?= $view_tab_akta['tab'][1] ?>" id="akta2" role="tabpanel"
                        aria-labelledby="akta2-tab">
                        <?= $this->render('../change-deed/index', [
                            'changeDeedModel' => $changeDeedModel,
                            'changeDeedDataProvider' => $changeDeedDataProvider,
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane <?= $view_tab['tab'][3] ?>" id="manager" role="tabpanel" aria-labelledby="manager-tab">
            <div class="manager-tab">
                <ul class="nav nav-tabs border-0 rounded-3 shadow-sm mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link rounded-3 setnavtabmanajerial <?= $view_tab_manajerial['nav'][0] ?>"
                            id="owner-tab" data-bs-toggle="tab" href="#owner" role="tab" aria-controls="owner"
                            aria-selected="true" data-tab="0">
                            <i class='bx bx-user me-2'></i> Pemilik
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded-3 setnavtabmanajerial <?= $view_tab_manajerial['nav'][1] ?>"
                            id="admin-tab" data-bs-toggle="tab" href="#admin" role="tab" aria-controls="admin"
                            aria-selected="true" data-tab="1">
                            <i class='bx bx-cog me-2'></i> Pengurus
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane <?= $view_tab_manajerial['tab'][0] ?>" id="owner" role="tabpanel"
                        aria-labelledby="owner-tab">
                        <?= $this->render('../owner/index', [
                            'ownerModel' => $ownerModel,
                            'ownerDataProvider' => $ownerDataProvider,
                        ]) ?>
                    </div>
                    <div class="tab-pane <?= $view_tab_manajerial['tab'][1] ?>" id="admin" role="tabpanel"
                        aria-labelledby="admin-tab">
                        <?= $this->render('../administrator/index', [
                            'administratorModel' => $administratorModel,
                            'administratorDataProvider' => $administratorDataProvider,
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane <?= $view_tab['tab'][4] ?>" id="pengalaman" role="tabpanel" aria-labelledby="pengalaman-tab">
            <?= $this->render('../experience-business-entity/index', [
                'searchModel' => $experienceModel,
                'dataProvider' => $experienceDataProvider,
            ]) ?>
        </div>
    </div>
</section>