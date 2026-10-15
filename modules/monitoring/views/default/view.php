<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use newerton\fancybox3\FancyBox;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use miloschuman\highcharts\HighchartsAsset;

/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\models\MonitoringTask */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('monitoring', 'Monitorings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
HighchartsAsset::register($this)->withScripts(['modules/stock', 'modules/exporting']);
?>
<?php
echo FancyBox::widget([
    'target' => '[data-fancybox="monitoring"]',
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
    });

EOT_JS_CODE

);
?>
<?php Pjax::begin(); ?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="monitoring-task-view">
            <div class="row">
                <div class="col-md-4 text-start">
                    <?= Html::a(Yii::t('app', 'Back to List'), [$url_prev], ['class' => 'btn btn-sm btn-warning']) ?>
                </div>
                <div class="col-md-4 text-start text-md-center">
                    <?php if (Yii::$app->user->can('Administrator') || Yii::$app->user->can('OPD')) : ?>
                        <?= Html::button(Yii::t('monitoring', 'Generate BAST'), [
                            'class' => 'btn btn-sm btn-success',
                            'data' => [
                                'bs-toggle' => 'modal',
                                'bs-target' => '#bastModal' . Yii::$app->hasher->encode($model->id),
                            ],
                        ]) ?>
                    <?php endif; ?>
                    <?php if (Yii::$app->user->can('Administrator') || Yii::$app->user->can('OPD') || Yii::$app->user->can('Supervisor') || Yii::$app->user->can('Planner')) : ?>
                        <?= Html::button(Yii::t('monitoring', 'Generate Monitoring Result'),  [
                            'class' => 'btn btn-sm btn-secondary',
                            'data' => [
                                'bs-toggle' => 'modal',
                                'bs-target' => '#reportModal' . Yii::$app->hasher->encode($model->id),
                            ],
                        ]) ?>
                    <?php endif; ?>
                </div>
                <div class="col-md-4 text-start text-md-end">
                    <?php if (Yii::$app->user->can('Administrator') || Yii::$app->user->can('OPD') || Yii::$app->user->can('Supervisor')) : ?>
                        <?= Html::a(Yii::t('app', 'Add Monitoring'), ['progress/create', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-sm btn-success']) ?>
                    <?php endif; ?>
                    <?php if (Yii::$app->user->can('Administrator') || Yii::$app->user->can('OPD')) : ?>
                        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-sm btn-warning']) ?>
                        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => Yii::$app->hasher->encode($model->id)], [
                            'class' => 'btn btn-sm btn-danger',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]) ?>
                    <?php endif; ?>
                </div>
            </div>
            <br>
            <ul class="nav nav-fill nav-tabs">

                <!-- Keterangan Dibawah ini untuk WRITE -->
                <!-- Semua, Kecuali Mutasi Kontrak Hanya Admin & OPD -->
                <li class="nav-item">
                    <a class="nav-link setnavtab <?= $monitoring_view_tab['nav'][0] ?> " data-bs-toggle="pill" href="#detail-paket" data-tab="0">Detail Paket</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link setnavtab <?= $monitoring_view_tab['nav'][1] ?> " data-bs-toggle="pill" href="#detail-kurva-s" data-tab="1">Grafik Progress</a>
                </li>
                <!-- Semua, Kecuali Mutasi Kontrak Hanya Admin & OPD -->

                <!-- Hanya Admin & OPD -->
                <?php if (Yii::$app->user->can('Administrator') || Yii::$app->user->can('OPD')) : ?>
                    <li class="nav-item">
                        <a class="nav-link setnavtab <?= $monitoring_view_tab['nav'][2] ?> " data-bs-toggle="pill" href="#entity-terkait" data-tab="2">Data Entitas</a>
                    </li>
                    <li class="nav-item">
                        <a id="peta-lokasi-tab" class="nav-link setnavtab <?= $monitoring_view_tab['nav'][3] ?> " data-bs-toggle="pill" href="#peta-lokasi" data-tab="3">Peta & Lokasi</a>
                    </li>
                <?php endif; ?>
                <!-- Hanya Admin & OPD -->

                <!-- Hanya Admin, Perencana & OPD -->
                <?php if (Yii::$app->user->can('Planner') || Yii::$app->user->can('Administrator') || Yii::$app->user->can('OPD')) : ?>
                    <li class="nav-item">
                        <a class="nav-link setnavtab <?= $monitoring_view_tab['nav'][4] ?> " data-bs-toggle="pill" href="#ded-paket" data-tab="4">DED</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link setnavtab <?= $monitoring_view_tab['nav'][5] ?> " data-bs-toggle="pill" href="#planner-note" data-tab="5">Catatan Perencana</a>
                    </li>
                <?php endif; ?>
                <!-- Hanya Admin, Perencana & OPD -->

                <!-- Hanya Admin, Pengawas & OPD -->
                <?php if (Yii::$app->user->can('Supervisor') || Yii::$app->user->can('Administrator') || Yii::$app->user->can('OPD')) : ?>
                    <li class="nav-item">
                        <a class="nav-link setnavtab <?= $monitoring_view_tab['nav'][6] ?> " data-bs-toggle="pill" href="#monitoring-paket" data-tab="6"><?= Yii::t('monitoring', 'Monitoring Progresses') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link setnavtab <?= $monitoring_view_tab['nav'][7] ?> " data-bs-toggle="pill" href="#dokumen-gambar" data-tab="7"><?= Yii::t('monitoring', 'Monitoring Progress Pictures') ?></a>
                    </li>
                <?php endif; ?>
                <!-- Hanya Admin, Pengawas & OPD -->
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Semua, Kecuali Mutasi Kontrak Hanya Admin & OPD -->
                <div class="tab-pane <?= $monitoring_view_tab['tab'][0] ?> " id="detail-paket">
                    <?=
                    $this->render('viewtabs/_detail_paket', [
                        'model' => $model,
                        'taggingDataProvider' => $taggingDataProvider,
                        'taggingModel' => $taggingModel,
                        'ccoDataProvider' => $ccoDataProvider,
                        'ccoModel' => $ccoModel,
                    ]);
                    ?>
                </div>
                <div class="tab-pane <?= $monitoring_view_tab['tab'][1] ?> " id="detail-kurva-s">
                    <?=
                    $this->render('viewtabs/_detail_kurva_s', [
                        'weeks' => $weeks,
                        'plans' => $plans,
                        'realisations' => $realisations,
                    ]);
                    ?>
                </div>
                <!-- Semua, Kecuali Mutasi Kontrak Hanya Admin & OPD -->

                <!-- Hanya Admin & OPD -->
                <?php if (Yii::$app->user->can('Administrator') || Yii::$app->user->can('OPD')) : ?>
                    <div class="tab-pane <?= $monitoring_view_tab['tab'][2] ?> " id="entity-terkait">
                        <?=
                        $this->render('viewtabs/_entity_terkait', [
                            'plannerDataProvider' => $plannerDataProvider,
                            'plannerModel' => $plannerModel,
                            'supervisorDataProvider' => $supervisorDataProvider,
                            'supervisorModel' => $supervisorModel,
                            'eksekutorDataProvider' => $eksekutorDataProvider,
                            'eksekutorModel' => $eksekutorModel,
                            'model' => $model,
                        ]);
                        ?>
                    </div>
                    <div class="tab-pane <?= $monitoring_view_tab['tab'][3] ?> " id="peta-lokasi">
                        <?=
                        $this->render('viewtabs/_peta_lokasi', [
                            'locationsDataProvider' => $locationsDataProvider,
                            'locationsModel' => $locationsModel,
                            'model' => $model,
                        ]);
                        ?>
                    </div>
                <?php endif; ?>
                <!-- Hanya Admin & OPD -->

                <!-- Hanya Admin, Perencana & OPD -->
                <?php if (Yii::$app->user->can('Planner') || Yii::$app->user->can('Administrator') || Yii::$app->user->can('OPD')) : ?>
                    <div class="tab-pane <?= $monitoring_view_tab['tab'][4] ?> " id="ded-paket">
                        <?=
                        $this->render('viewtabs/_ded_paket', [
                            'dedDataProvider' => $dedDataProvider,
                            'dedModel' => $dedModel,
                            'ccoDropDown' => $ccoDropDown,
                            'model' => $model,
                        ]);
                        ?>
                    </div>
                    <div class="tab-pane <?= $monitoring_view_tab['tab'][5] ?> " id="planner-note">
                        <?=
                        $this->render('viewtabs/_planner_note', [
                            'plannerNoteDataProvider' => $plannerNoteDataProvider,
                            'plannerNoteModel' => $plannerNoteModel,
                            'model' => $model,
                        ]);
                        ?>
                    </div>
                <?php endif; ?>
                <!-- Hanya Admin, Perencana & OPD -->

                <!-- Hanya Admin, Pengawas & OPD -->
                <?php if (Yii::$app->user->can('Supervisor') || Yii::$app->user->can('Administrator') || Yii::$app->user->can('OPD')) : ?>
                    <div class="tab-pane <?= $monitoring_view_tab['tab'][6] ?> " id="monitoring-paket">
                        <?=
                        $this->render('viewtabs/_monitoring_paket', [
                            'progressDataProvider' => $progressDataProvider,
                            'progressModel' => $progressModel,
                            'model' => $model,
                        ]);
                        ?>
                    </div>
                    <div class="tab-pane <?= $monitoring_view_tab['tab'][7] ?> " id="dokumen-gambar">
                        <?=
                        $this->render('viewtabs/_dokumen_gambar', [
                            'picturesDataProvider' => $picturesDataProvider,
                            'picturesModel' => $picturesModel,
                            'model' => $model,
                        ]);
                        ?>
                    </div>
                <?php endif; ?>
                <!-- Hanya Admin, Pengawas & OPD -->

            </div>
        </div>
    </div>
</section>
<?php Pjax::end() ?>
<?=
$this->render('_print_modal', ['model' => $model]);
?>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?= Yii::t('app', 'Add File') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php $formf = ActiveForm::begin(['action' => ['/monitoring/default/add-file', 'id' =>  Yii::$app->hasher->encode($model->id)], 'method' => 'post']); ?>
                <div class='row'>
                    <div class='col-md-12'>
                        <?=
                        $formf->field($modelfile, 'file_name')->widget(FileInput::class, [
                            'language' => 'id',
                            'options' => [
                                'id' => 'halo',
                                'multiple' => false,
                                'accept' => 'image/jpeg, image/jpg, image/png, application/pdf, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            ],
                            'pluginOptions' => [
                                'resizeImages' => true,
                                'previewFileType' => 'any',
                                'showPreview' => true,
                                'showCaption' => true,
                                'showRemove' => true,
                                'showUpload' => false,
                                'resizePreference' => 'height',
                                'resizeImage' => true,
                                'resizeIfSizeMoreThan' => 250,
                                'maxFileSize' => 2600,
                                'maxFileCount' => 1,
                                'allowedFileExtensions' => ['jpg', 'jpeg', 'png', 'pdf', 'xlsx', 'docx'],
                                'allowedPreviewExtensions' => ['jpg', 'jpeg', 'png', 'pdf', 'xlsx', 'docx'],
                            ]
                        ])->hint('Ekstensi file (jpg, jpeg, png, pdf, xlsx, docx). Ukuran maksimal file 2.54MB');
                        ?>
                    </div>
                    <div class='col-md-12'>
                        <?= $formf->field($modelfile, 'description')->textarea(['rows' => 6]) ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= Yii::t('app', 'Close') ?></button>
                    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>