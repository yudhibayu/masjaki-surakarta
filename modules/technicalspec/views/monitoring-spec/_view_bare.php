<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
?>
<hr>
<div class="row">
    <!-- bagian DED -->
    <div class="col-md-6">
        <?php $ded = $model->dedData; ?>
        <h3>Data DED<br><small><?= Html::encode($ded->name) ?></small></h3>
        <?= DetailView::widget([
            'model' => $ded,
            'options' => ['class' => 'table table-striped table-bordered', 'width' => '100%'],
            'attributes' => [
                'name',
                'monitoring_task_name:ntext',
                // 'ref_component_category_id',
                'ref_component_category_name',
                // 'ref_component_id',
                'ref_component_name',
                // 'ref_satuan_name',
                [
                    'attribute' => 'refSatuan.name',
                    'label' => 'Satuan'
                ],
                [
                    'attribute' => 'cco',
                    'value' => function ($model) {
                        if ($model->cco == 0) {
                            return 'MCO';
                        }
                        return 'CCO ' . $model->cco;
                    },
                ],
                'specification:ntext',
                [
                    'attribute' => 'quantity',
                    'value' => $ded->kuantitasSatuan,
                ],
                'notes:ntext',
                [
                    'label' => Yii::t('app', 'Action'),
                    'format' => 'raw',
                    'value' => Html::a(Yii::t('app', 'View'), ['/technicalspec/default/view', 'id' => Yii::$app->hasher->encode($ded->id)], ['class' => 'btn btn-primary', 'data-pjax' => 0,])
                ],
            ],
        ]) ?>
        <div class="row">
            <h4>Berkas : </h4>
            <?php foreach ($ded->files as $file) : ?>
                <div class="col-md-6 text-center">
                    <div class="card" style="width: 13rem;">
                        <div class="card-body">
                            <?php
                            if (
                                (strtolower($file->file_extension) == 'jpg') ||
                                (strtolower($file->file_extension) == 'jpeg') ||
                                (strtolower($file->file_extension) == 'png') ||
                                (strtolower($file->file_extension) == 'gif') ||
                                (strtolower($file->file_extension) == 'webp')
                            ) {
                                echo Html::a(
                                    Html::img(Url::to(['ded-files/fetch-file', 'id' => Yii::$app->hasher->encode($file->id)], true), ['width' => '200px', 'alt' => $file->name, 'class' => 'card-img-top',]),
                                    Url::to(['ded-files/fetch-file', 'id' => Yii::$app->hasher->encode($file->id)], true),
                                    [
                                        'data' => [
                                            'pjax' => 0,
                                            'fancybox' => "monitoring",
                                        ],
                                    ]
                                );
                            } else if (
                                (strtolower($file->file_extension) == 'mp4') ||
                                (strtolower($file->file_extension) == 'mkv')
                            ) {
                                echo '<video width="150px" controls>
                                <source src="' . Url::to(['ded-files/fetch-file', 'id' => Yii::$app->hasher->encode($file->id)]) . '" type="video/mp4">
                                Your browser does not support the video tag.
                        ' . Html::a(
                                    'Download',
                                    Url::to(['ded-files/fetch-file', 'id' => Yii::$app->hasher->encode($file->id)]),
                                    [
                                        'data' => [
                                            'pjax' => 0,
                                            'fancybox' => "monitoring",
                                        ]
                                    ]
                                ) . '
                        </video>';
                            } else {
                                echo Html::a(Yii::t('app', 'Download') . ' <span class="fas fa-download"></span>', Url::to(['ded-files/fetch-file', 'id' => Yii::$app->hasher->encode($file->id)]), ['class' => 'btn btn-primary card-img-top', 'data' => ['pjax' => 0],]);
                            }
                            echo '<hr>';
                            echo Html::tag('h5', Html::encode($file->name), [
                                'class' => 'card-title'
                            ]);
                            $description = $file->description;
                            if (strlen($file->description) > 40) {
                                $description = substr($file->description, 0, 37) . '...';
                            }
                            echo Html::tag('p', $description, [
                                'class' => 'card-text'
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- bagian Monitoring Spec -->
    <div class="col-md-6">
        <h3>Monitoring Spesifikasi<br><small><?= Html::encode($model->name) ?></small></h3>
        <br>
        <?= DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'table table-striped table-bordered', 'width' => '100%'],
            'attributes' => [
                'name',
                // 'ded_data_id',
                'ded_data_name',
                // 'monitoring_progress_id',
                // 'monitoring_progress_name',
                // 'monitoring_task_id',
                // 'monitoring_task_name',
                'description:ntext',
                // 'created_at:datetime',
                // 'updated_at:datetime',
                // [
                //     'attribute' => 'created_by',
                //     'value' => $model->maker
                // ],
                // [
                //     'attribute' => 'updated_by',
                //     'value' => $model->updater
                // ],
                [
                    'label' => Yii::t('app', 'Action'),
                    'format' => 'raw',
                    'value' => Html::a(Yii::t('app', 'View'), ['/technicalspec/monitoring-spec/view', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-primary', 'data-pjax' => 0,])
                ],
            ],
        ]) ?>
        <div class="row">
            <h4>Berkas : </h4>
            <?php foreach ($model->files as $file) : ?>
                <div class="col-md-6 text-center">
                    <div class="card" style="width: 13rem;">
                        <div class="card-body">
                            <?php
                            if (
                                (strtolower($file->file_extension) == 'jpg') ||
                                (strtolower($file->file_extension) == 'jpeg') ||
                                (strtolower($file->file_extension) == 'png') ||
                                (strtolower($file->file_extension) == 'gif') ||
                                (strtolower($file->file_extension) == 'webp')
                            ) {
                                echo Html::a(
                                    Html::img(Url::to(['monitoring-spec-files/fetch-file', 'id' => Yii::$app->hasher->encode($file->id)], true), ['width' => '200px', 'alt' => $file->name, 'class' => 'card-img-top',]),
                                    Url::to(['monitoring-spec-files/fetch-file', 'id' => Yii::$app->hasher->encode($file->id)], true),
                                    [
                                        'data' => [
                                            'pjax' => 0,
                                            'fancybox' => "monitoring",
                                        ],
                                    ]
                                );
                            } else if (
                                (strtolower($file->file_extension) == 'mp4') ||
                                (strtolower($file->file_extension) == 'mkv')
                            ) {
                                echo '<video width="150px" controls>
                                <source src="' . Url::to(['monitoring-spec-files/fetch-file', 'id' => Yii::$app->hasher->encode($file->id)]) . '" type="video/mp4">
                                Your browser does not support the video tag.
                        ' . Html::a(
                                    'Download',
                                    Url::to(['monitoring-spec-files/fetch-file', 'id' => Yii::$app->hasher->encode($file->id)]),
                                    [
                                        'data' => [
                                            'pjax' => 0,
                                            'fancybox' => "monitoring",
                                        ]
                                    ]
                                ) . '
                        </video>';
                            } else {
                                echo Html::a(Yii::t('app', 'Download') . ' <span class="fas fa-download"></span>', Url::to(['monitoring-spec-files/fetch-file', 'id' => Yii::$app->hasher->encode($file->id)]), ['class' => 'btn btn-primary card-img-top', 'data' => ['pjax' => 0],]);
                            }
                            echo '<hr>';
                            echo Html::tag('h5', Html::encode($file->name), [
                                'class' => 'card-title'
                            ]);
                            $description = $file->description;
                            if (strlen($file->description) > 40) {
                                $description = substr($file->description, 0, 37) . '...';
                            }
                            echo Html::tag('p', $description, [
                                'class' => 'card-text'
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>