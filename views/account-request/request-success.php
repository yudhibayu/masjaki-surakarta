<?php

use app\widgets\Alert;
use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\modules\frontMap\models\TblAccountRequest */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Account Requests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<br>
<br>
<div class="container">
    <section class="card">
        <div class="card-header">
            <div class="row" style="padding: 10px">
                <?= Alert::widget() ?>
            </div>
            <h1><?= Html::encode($this->title) ?></h1>
            <p>Silahkan Login Terlebih Dahulu</p>
        </div>
        <div class="card-body">
            <div class="tbl-account-request-view">
                <br>
                <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                        'attributes' => [
                            [
                                'attribute' => 'confirm_status',
                                'value' => function ($model) {
                                    return $model->confirmStatusLabels()[$model->confirm_status];
                                },
                            ],
                            'name',
                            // 'username',
                            // 'email:email',
                            // [
                            //     'attribute' => 'requested_account_type',
                            //     'value' => function ($model) {
                            //         return $model->requestAccountLabels()[$model->requested_account_type];
                            //     },
                            // ],
                            // 'note:ntext',
                            [
                                'attribute' => 'created_at',
                                'format' => 'date',
                                'label' => 'Direqeuest tanggal',
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </section>
</div>