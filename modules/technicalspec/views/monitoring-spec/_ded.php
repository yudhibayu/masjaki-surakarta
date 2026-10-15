<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\modules\manage_csa_b5\models\CsaB5 */

?>
<div class="csa-b5-view">
    <div class="row">
        <div class="col-md-12">
            <h4><b>.:. <?= Yii::t('app', 'DED') ?> .:.</b></h4>
        </div>
    </div>
    <?= DetailView::widget([
        'model' => $modelded,
        'attributes' => [
            [
                'attribute' => 'cco',
                'value' => function ($model) {
                    if ($model->cco == 0) {
                        return 'MCO';
                    }
                    return 'CCO ' . $model->cco;
                },
            ],
            'name',
            // 'monitoring_task_id',
            // 'monitoring_task_name:ntext',
            // 'ref_component_category_id',
            'ref_component_category_name',
            //'ref_component_id',
            'ref_component_name',
            'specification:ntext',
            'quantity',
            // 'notes:ntext',
            //'created_at:datetime',
            /*[
                'attribute' => 'created_by', 
                'value' => 'maker',
                'filter' => false
                ],*/
            //'updated_at:datetime',
            /*[
                'attribute' => 'updated_by', 
                'value' => 'updater',
                'filter' => false
                ],*/
        ],
    ]) ?>
    <div class="row">
        <?php foreach ($modelded->files as $file) : ?>
            <div class="col-md-6 text-center">
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
                            echo '<video width="200px" controls>
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