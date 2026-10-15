<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\models\Teamlist */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Teams'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="teamlist-view">

            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), [$url_prev], ['class' => 'btn btn-warning']) ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => Yii::$app->hasher->encode($model->id)], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
            <br>
            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                    'attributes' => [
                        'name',
                        'created_at:datetime',
                        [
                            'attribute' => 'created_by',
                            'value' => $model->maker
                        ],
                        'updated_at:datetime',
                        [
                            'attribute' => 'updated_by',
                            'value' => $model->updater
                        ],
                    ],
                ]) ?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?=
                    GridView::widget(
                        [
                            'dataProvider' => $usersDataProvider,
                            'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                            // 'filterModel' => $searchModel,
                            'pager' => [
                                'firstPageLabel' => Yii::t('app', 'First'),
                                'lastPageLabel' => Yii::t('app', 'Last'),
                            ],
                            'showFooter' => true,
                            'columns' => [
                                [
                                    'class' => 'yii\grid\SerialColumn'
                                ],
                                [
                                    'attribute' => 'id',
                                    'label' => 'ID',
                                    'visible' => false,
                                ],
                                [
                                    'attribute' => 'name',
                                    'label' => 'Nama Anggota',
                                ],
                                [
                                    'attribute' => 'fiscal_year',
                                    'label' => Yii::t('app','Fiscal Year'),
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => Yii::t('app', 'Action'),
                                    'headerOptions' => ['width' => '80'],
                                    'template' => '{view} {delete}',
                                    'buttons' => [
                                        'view' => function ($url, $user, $key) {
                                            return Html::a(
                                                '<span class="fas fa-eye"></span>',
                                                [
                                                    Url::toRoute(['/user/admin/update-profile', 'id' => $user['id']])
                                                ]
                                            );
                                        },
                                        'delete' => function ($url, $user, $key) {
                                            return Html::a(
                                                '<span class="fas fa-trash"></span>',
                                                [
                                                    Url::toRoute(['remove-member', 'id' => Yii::$app->hasher->encode($user['id'])])
                                                ],
                                                [
                                                    //'class' => 'btn btn-danger',
                                                    'data' => [
                                                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                        'method' => 'post',
                                                        'params' => [], // <- extra level
                                                    ],
                                                ]
                                            );
                                        },
                                    ],
                                    'footer' => Html::a('Tambah', ['add-member', 'teamid' => $model->id], ['class' => 'btn btn-primary']),
                                ],
                            ],
                        ],
                    ); ?>
                </div>
            </div>
            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), [$url_prev], ['class' => 'btn btn-warning']) ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => Yii::$app->hasher->encode($model->id)], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</section>