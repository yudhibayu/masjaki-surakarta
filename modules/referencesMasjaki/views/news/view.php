<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use coderius\hitCounter\widgets\hitCounter\HitCounterWidget;
/* @var $this yii\web\View */
/* @var $model app\modules\referencesMasjaki\models\News */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Update News'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
// $hits = Yii::$app->hitCounter->getHitsCount('article', $model->id);

?>
<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <!-- <p><strong>Jumlah kunjungan:</strong> <= $hits ?></p> -->
    <!-- <php
    echo HitCounterWidget::widget([]); ?> -->
    <div class="card-body">
        <div class="news-view">

            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), [$url_prev], ['class' => 'btn btn-outline-secondary']) ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => Yii::$app->hasher->encode($model->id)], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
            <br>
            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <div class="table-responsive">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                        'attributes' => [
                            [
                                'attribute' => 'thumbnail',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    $photoPath = '@web/photo/' . $model->thumbnail;
                                    $fileExtension = strtolower(pathinfo($photoPath, PATHINFO_EXTENSION));
                                    $imageExtensions = ['jpg', 'jpeg', 'png'];

                                    if (in_array($fileExtension, $imageExtensions)) {
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
                            'title',
                            [
                                'attribute' => 'content',
                                'format' => 'html', // Menampilkan sebagai HTML
                                'value' => function ($model) use ($purifier) {
                                    return $purifier->purify($model->content); // Sanitasi dan mengembalikan HTML yang aman
                                }
                            ],
                            'date',
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
            <br>
            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), [$url_prev], ['class' => 'btn btn-outline-secondary']) ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => Yii::$app->hasher->encode($model->id)], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</section>