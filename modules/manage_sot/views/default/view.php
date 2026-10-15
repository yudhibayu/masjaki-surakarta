<?php

use yii\widgets\DetailView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\manage_sot\models\RefSot */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('manage_sot', 'Sot'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<section class="card">
    <div class="card-body">
        <div class="ref-sot-view">

            <h3><?= Html::encode($this->title) ?></h3>

            <div class="form-group  pull-right">
                <?= Html::a(Yii::t('app', 'Back to List'), [$url_prev], ['class' => 'btn btn-warning']) ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
            <br><br>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    'note',
                    'active',
                    'created_at:datetime',
                    'updated_at:datetime',
                    [
                        'attribute' => 'created_by',
                        'value' => $model->createdByName
                    ],
                    [
                        'attribute' => 'updated_by',
                        'value' => $model->updatedByName
                    ],
                ],
            ]) ?>
            <div class="form-group  pull-right">
                <?= Html::a(Yii::t('app', 'Back to List'), [$url_prev], ['class' => 'btn btn-warning']) ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
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