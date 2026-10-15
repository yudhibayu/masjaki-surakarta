<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\modules\rup\models\Pbj */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rup', 'PBJs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="pbj-view">
            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), [$url_prev], ['class' => 'btn btn-warning']) ?>

                <?php if (Yii::$app->user->can('Administrator')): ?>
                    <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->kodelelang)], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => Yii::$app->hasher->encode($model->kodelelang)], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                <?php endif; ?>
            </div>
            <br>
            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                    'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '-'],
                    'attributes' => [
                        'kodelelang',
                        'name:ntext',
                        'rup_code',
                        [
                            'attribute' => 'budget',
                            'format' => ['decimal', 2]
                        ],

                        'fiscal_year',
                        [
                            'attribute' => 'hps',
                            'format' => ['decimal', 2]
                        ],
                        [
                            'attribute' => 'bid_price',
                            'format' => ['decimal', 2]
                        ],
                        [
                            'attribute' => 'final_price',
                            'format' => ['decimal', 2]
                        ],
                        'vendor_name:ntext',
                        // 'sppbj_no',
                        // 'contract_no',
                        // 'contract_date:date',
                        // 'contract_duration',
                        // [
                        //     'attribute' => 'contract_price',
                        //     'format' => ['decimal', 2]
                        // ],
                        // 'contract_start_date:date',
                        // 'contract_end_date:date',
                        'work_start_date:date',
                        'work_finished_date:date',
                        // 'program',
                        // 'activity',
                        // 'sub_activity',
                        'namasatker:ntext',
                        'kodesatker',
                        // // 'idmetodepengadaan',
                        // 'metodepengadaan',
                        // 'idjenispengadaan',
                        'jenispengadaan',
                        // // 'ppk_id',
                        'ppk_name',
                        'step',
                        [
                            'attribute' => 'source_of_funds',
                            'value' => function ($model) {
                                return $model->sourceOfFunds ? $model->sourceOfFunds->name : null;
                            },
                        ],
                        // 'ppk_nip',
                        'created_at:datetime',
                        'updated_at:datetime',
                        [
                            'attribute' => 'created_by',
                            'value' => $model->maker
                        ],
                        [
                            'attribute' => 'updated_by',
                            'value' => $model->updater
                        ],
                    ],
                ]) ?>
            </div>
            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), [$url_prev], ['class' => 'btn btn-warning']) ?>

                <?php if (Yii::$app->user->can('Administrator')): ?>
                    <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->kodelelang)], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => Yii::$app->hasher->encode($model->kodelelang)], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (Yii::$app->user->can('Administrator')): ?>
        <?= $this->render('@app/modules/construction/views/pbj-business-entity/index', [
            'searchModel' => $searchModelBusiness,
            'dataProvider' => $dataProviderBusiness,
            'model' => $model,
            'existingRecord' => $existingRecord,
        ]) ?>
    <?php endif; ?>

    <?= $this->render('@app/modules/construction/views/construction-accident/index', [
        'constructionAccidentModel' => $constructionAccidentModel,
        'constructionAccidentDataProvider' => $constructionAccidentDataProvider,
    ]) ?>

    <?= $this->render('@app/modules/construction/views/construction-safety/index', [
        // 'model' => $model,
        'constructionSafetyModel' => $constructionSafetyModel,
        'constructionSafetyDataProvider' => $constructionSafetyDataProvider,
    ]) ?>
</section>