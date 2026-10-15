<?php

use app\modules\construction\models\RegistrantVacancies;
use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\modules\construction\models\ConstructionVacancies */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Construction Vacancies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="construction-vacancies-view">

            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), [$url_prev], ['class' => 'btn btn-outline-secondary']) ?>
                <?php if (Yii::$app->user->can('Badan Usaha')): ?>
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
                    <?php   $cekregister = RegistrantVacancies::find()->where(['id_vacancies' => $model->id, 'id_labor' => Yii::$app->session['tenaga_kerja']['id_labor']])->one();
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
                            'name',
                            'description',
                            [
                                'attribute' => 'construction_work',
                                'value' => function ($model) {
                                    return $model->constructionWork ? $model->constructionWork->name : null;
                                },
                            ],
                            [
                                'attribute' => 'skills',
                                'value' => function ($model) {
                                    return $model->skillsRelation ? $model->skillsRelation->name : null;
                                },
                            ],
                            [
                                'attribute' => 'education',
                                'value' => function ($model) {
                                    return $model->educationRelation ? $model->educationRelation->name : null;
                                },
                            ],
                            [
                                'attribute' => 'age_range',
                                'value' => function ($model) {
                                    return $model->age_range . ' tahun';
                                },
                            ],
                            [
                                'attribute' => 'age_range_upto',
                                'value' => function ($model) {
                                    return $model->age_range_upto . ' tahun';
                                },
                            ],
                            [
                                'attribute' => 'work_experience',
                                'value' => function ($model) {
                                    return $model->work_experience . ' tahun';
                                },
                            ],
                            [
                                'attribute' => 'quota',
                                'value' => function ($model) {
                                    return $model->quota . ' orang';
                                },
                            ],
                            [
                                'attribute' => 'salary_range',
                                'format' => 'currency',
                            ],
                            [
                                'attribute' => 'salary_range_upto',
                                'format' => 'currency',
                            ],
                            'start_date',
                            'date_of_completion',

                            [
                                'attribute' => 'type_of_work',
                                'value' => function ($model) {
                                    return $model->workType ? $model->workType : null;
                                },
                            ],
                            'year_name',
                            [
                                'attribute' => 'nama_business_entity',
                                'value' => function ($model) {
                                    return $model->nama_business_entity ?: '-';
                                },
                            ],
                            
                            // [
                            //     'attribute' => 'id_year',
                            //     'value' => function ($model) {
                            //         return $model->refYear ? $model->refYear->name : null;
                            //     },
                            // ],
                            'created_at:datetime',
                            'updated_at:datetime',
                            'start_time',
                            'end_time',
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
                <?php if (Yii::$app->user->can('Badan Usaha')): ?>

                <?php if ($model->publication == $model::UNPUBLICATED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?>

                <?php endif ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => Yii::$app->hasher->encode($model->id)], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) ?>
                <?php endif ?>
                <?php if (!Yii::$app->user->can('Team') && Yii::$app->user->can('Tenaga Kerja') && $model->publication == $model::PUBLICATED): ?>
                 <?php   $cekregister = RegistrantVacancies::find()->where(['id_vacancies' => $model->id, 'id_labor' => Yii::$app->session['tenaga_kerja']['id_labor']])->one();
                        if (!$cekregister) { ?>
                    <?= Html::a(Yii::t('app', 'Register'), ['register', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?>
                <?php } ?>
                    <?php endif; ?>
            </div>
        </div>
    </div>
    <?php if (Yii::$app->user->can('Badan Usaha')) : ?>
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
            <?= $this->render('../registrant-vacancies/index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $model,
                ]) ?>
        </div>
        <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
            <?= $this->render('../registrant-vacancies/index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProviderSelect,
                    'model' => $model,
                ]) ?>
        </div>
    </div>
    <?php endif ?>
</section>