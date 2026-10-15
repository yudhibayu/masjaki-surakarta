<?php

use newerton\fancybox3\FancyBox;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\modules\construction\models\ConstructionSafety */

$this->title = Yii::$app->formatter->asDate($model->date, 'php:j F Y');
$this->params['breadcrumbs'][] = ['label' => 'Construction Works', 'url' => ['./construction-work']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Construction Safeties'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<?php
echo FancyBox::widget([
    'target' => '[data-fancybox="image-fancybox"]',
]);
?>

<section class="card">
    <div class="card-header">
        <h3>Laporan Per Tanggal <?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="construction-safety-view">

            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), ['/rup/pbj/view', 'id' => Yii::$app->hasher->encode($model->id_construction_work)], ['class' => 'btn btn-outline-secondary']) ?>

                <?php if (Yii::$app->user->can('Badan Usaha')): ?>
                    <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?>
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
                        'options' => ['class' => 'table table-striped table-borderless', 'style' => 'font-size:13px'],
                        'attributes' => [
                            // 'id_construction_work',
                            [
                                'attribute' => 'date',
                                'format' => ['date', 'php:j F Y'],
                                'label' => 'Tanggal',
                            ],
                            [
                                'attribute' => 'file_report',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    $photoPath = ['/construction/construction-safety/fetch-file', 'id' => Yii::$app->hasher->encode($model->id)];
                                    if ((strtolower($model->file_extension) == 'pdf')) {
                                        $pdfUrl = Url::to($photoPath);
                                        return Html::a(
                                            '<i class="bx bxs-file-pdf"></i> Preview PDF',
                                            $pdfUrl,
                                            [
                                                'class' => 'badge badge-warning',
                                                'data' => [
                                                    'pjax' => 0,
                                                ],
                                            ]
                                        ) . ' ' .
                                            Html::a(
                                                '<i class="bx bx-download"></i> Download PDF',
                                                $pdfUrl,
                                                [
                                                    'class' => 'badge badge-success',
                                                    'download' => true,
                                                    'data' => [
                                                        'pjax' => 0,
                                                    ],
                                                ]
                                            );
                                    } elseif ((strtolower($model->file_extension) == 'png' || 'jpg' || 'jpeg')) {
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
                                    }
                                },
                            ],
                            'created_at:datetime',
                            'updated_at:datetime',
                            // 'deleted_at',
                            [
                                'attribute' => 'created_by',
                                'value' => $model->maker
                            ],
                            [
                                'attribute' => 'updated_by',
                                'value' => $model->updater
                            ],
                            // 'deleted_by',
                        ],
                    ]) ?>
                </div>
            </div>
            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), ['/rup/pbj/view', 'id' => Yii::$app->hasher->encode($model->id_construction_work)], ['class' => 'btn btn-outline-secondary']) ?>

                <?php if (Yii::$app->user->can('Badan Usaha')): ?>
                    <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?>
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
</section>