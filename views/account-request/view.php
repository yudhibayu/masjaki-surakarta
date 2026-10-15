<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\modules\frontMap\models\TblAccountRequest */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Account Requests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="tbl-account-request-view">
            <?php if ($model->confirm_status == $model::CONFIRM_STATUS_PENDING) : ?>
                <div class="text-left">
                    <h3>Konfirmasi Akun</h3>
                    <?= Html::a(Yii::t('app', 'Confirm'), ['confirm', 'id' => Yii::$app->hasher->encode($model->id)], [
                        'class' => 'btn btn-success',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to confirm this user?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                    <?= Html::a(Yii::t('app', 'Reject'), ['reject', 'id' => Yii::$app->hasher->encode($model->id)], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to reject this user?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            <?php endif; ?>
            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), ['index'], ['class' => 'btn btn-warning']) ?>
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
                        'username',
                        'email:email',
                        [
                            'attribute' => 'requested_account_type',
                            'value' => function ($model) {
                                return $model->requestAccountLabels()[$model->requested_account_type];
                            },
                        ],
                        [
                            'attribute' => 'confirm_status',
                            'value' => function ($model) {
                                return $model->confirmStatusLabels()[$model->confirm_status];
                            },
                        ],
                        'note:ntext',
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
        </div>
    </div>
</section>