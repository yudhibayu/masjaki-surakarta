<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\modules\construction\models\Labor */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Labor'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="labor-view">

            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), [$url_prev], ['class' => 'btn btn-outline-secondary']) ?>
                <?php if ($model->account_verification !== $model::BLACKLIST) : ?>
                    <?php if (!Yii::$app->user->can('Badan Usaha')): ?>
                        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?>
                    <?php endif; ?>
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
                            'name',
                            'username',
                            // 'password_hash',
                            'nik_passport_number',
                            'npwp',
                            [
                                'attribute' => 'phone_number',
                                'value' => function ($model) {
                                    return '+62 ' . ltrim($model->phone_number, '0');
                                },
                            ],
                            'email:email',
                            'skilled_power_type',
                            [
                                'attribute' => 'citizenship',
                                'value' => function ($model) {
                                    return $model->wniWna;
                                },
                            ],
                            'bpjs_health_number',
                            'employment_bpjs_number',
                            [
                                'attribute' => 'last_education',
                                'value' => function ($model) {
                                    return $model->education ? $model->education->name : null;
                                },
                            ],
                            // 'id_construction_vacancies',
                            // 'last_education',

                            [
                                'attribute' => 'long_work_experience',
                                'value' => function ($model) {
                                    return $model->long_work_experience . ' tahun';
                                },
                            ],
                            [
                                'attribute' => 'employment_status',
                                'value' => function ($model) {
                                    return $model->employmentStatus0 ? $model->employmentStatus0 : null;
                                },
                            ],
                            'professional_expertise',
                            'description',
                            'address',
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
                <?php if ($model->account_verification !== $model::BLACKLIST) : ?>
                    <?php if (!Yii::$app->user->can('Badan Usaha')): ?>
                        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?>
                    <?php endif; ?>
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
    <?php if (Yii::$app->user->can('Team')): ?>

        <?= $this->render('../registrant-vacancies/index-labor', [
            'searchModelVacancies' => $searchModelVacancies,
            'dataProviderVacancies' => $dataProviderVacancies,
        ]) ?>
        <?= $this->render('../registrant-training/index-labor', [
            'searchModelTraining' => $searchModelTraining,
            'dataProviderTraining' => $dataProviderTraining,
        ]) ?>
    <?php endif; ?>
</section>