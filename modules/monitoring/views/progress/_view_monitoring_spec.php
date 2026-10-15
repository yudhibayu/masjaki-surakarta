<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
?>
<hr>
<div class="row">
    <div class="col-md-12">
        <h4><?= Html::encode($model->name) ?></h4>
    </div>
    <div class="col-md-6">
        <?= DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
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
                    'format' => 'html',
                    'value' => Html::a(Yii::t('app', 'View'), ['/technicalspec/monitoring-spec/view', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-primary'])
                ],
            ],
        ]) ?>
    </div>
    <?php foreach ($model->files as $file) : ?>
        <div class="col-md-3 text-center">
            <div class="card" style="width: 18rem;">
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
                            Html::img(Url::to(['/technicalspec/monitoring-spec-files/fetch-file', 'id' => Yii::$app->hasher->encode($file->id)], true), ['width' => '200px', 'alt' => $file->name, 'class' => 'card-img-top',]),
                            Url::to(['/technicalspec/monitoring-spec-files/fetch-file', 'id' => Yii::$app->hasher->encode($file->id)], true),
                            [
                                'data' => [
                                    'pjax' => 0,
                                    'fancybox' => "monitoring",
                                ],
                            ]
                        );
                    } else {
                        echo Html::a(Yii::t('app', 'Download') . ' <span class="fas fa-download"></span>', Url::to(['/technicalspec/monitoring-spec-files/fetch-file', 'id' => Yii::$app->hasher->encode($file->id)]), ['class' => 'btn btn-primary card-img-top', 'data' => ['pjax' => 0], 'target' => '_blank']);
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