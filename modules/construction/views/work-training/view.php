<?php

use app\modules\construction\models\RegistrantTraining;
use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\modules\construction\models\WorkTraining */

$this->title = $model->training_name;
$this->params['breadcrumbs'][] = ['label' => 'Pelatihan Kerjas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="work-training-view">

            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), [$url_prev], ['class' => 'btn btn-outline-secondary']) ?>
                <?php if (Yii::$app->user->can('Administrator')): ?>
                    <?php if ($model->publication == $model::UNPUBLICATED): ?>
                        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?>

                    <?php endif; ?>
                    <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => Yii::$app->hasher->encode($model->id)], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) ?>
                <?php endif; ?>
                <?php if (!Yii::$app->user->can('Team') && Yii::$app->user->can('Tenaga Kerja') && $model->publication == $model::PUBLICATED): ?>
                    <?php $cekregister = RegistrantTraining::find()->where(['id_training' => $model->id, 'id_labor' => Yii::$app->session['tenaga_kerja']['id_labor']])->one();
                    if (!$cekregister) { ?>
                        <?= Html::a(Yii::t('app', 'Register'), ['register', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?>
                    <?php } ?>
                <?php endif; ?>
            </div>
            <br>
            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <div class="table-responsive">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                        'attributes' => [
                            'training_name',
                            'person_responsible_name',
                            [
                                'attribute' => 'kuota',
                                'value' => function ($model) {
                                    return $model->kuota . ' orang';
                                },
                            ],
                            [
                                'attribute' => 'skills',
                                'value' => function ($model) {
                                    return $model->skillsRelation ? $model->skillsRelation->name : null;
                                },
                            ],
                            'implementation_date',
                            'finish_date',
                            'implementation_time',
                            'finish_time',

                            'year_name',
                            // [
                            //     'attribute' => 'id_year',
                            //     'value' => function ($model) {
                            //         return $model->refYear ? $model->refYear->name : null;
                            //     },
                            // ],
                            'description',
                            'country',
                            'province',
                            'regency',
                            'subdistrict',
                            'ward',
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
                <?= Html::a(Yii::t('app', 'Back to List'), [$url_prev], ['class' => 'btn btn-outline-secondary']) ?>
                <?php if (Yii::$app->user->can('Administrator')): ?>

                    <?php if ($model->publication == $model::UNPUBLICATED): ?>
                        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?>

                    <?php endif; ?>
                    <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => Yii::$app->hasher->encode($model->id)], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) ?>
                <?php endif; ?>
                <?php if (!Yii::$app->user->can('Team') && Yii::$app->user->can('Tenaga Kerja') && $model->publication == $model::PUBLICATED): ?>
                    <?php $cekregister = RegistrantTraining::find()->where(['id_training' => $model->id, 'id_labor' => Yii::$app->session['tenaga_kerja']['id_labor']])->one();
                    if (!$cekregister) { ?>
                        <?= Html::a(Yii::t('app', 'Register'), ['register', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?>
                    <?php } ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php if (Yii::$app->user->can('Administrator')) : ?>
        <div class="row">
            <div class="col-sm-12 mb-3">
                <ul class="nav nav-tabs nav-justified custom-nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="identitas-tab" data-bs-toggle="tab" data-bs-target="#identitas"
                            type="button" role="tab" aria-controls="identitas" aria-selected="true">
                            <i class='bx bx-user'></i> Pendaftar
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button"
                            role="tab" aria-controls="home" aria-selected="false">
                            <i class='bx bx-briefcase'></i> Pendaftar Yang Terpilih
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="identitas" role="tabpanel" aria-labelledby="identitas-tab">
                <?= $this->render('../registrant-training/index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $model,
                ]) ?>
            </div>
            <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                <?= $this->render('../registrant-training/index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProviderSelect,
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    <?php endif; ?>
</section>