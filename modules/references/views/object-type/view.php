<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\modules\references\models\ObjectType */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Object Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="object-type-view">

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
                'options' => ['class' => 'table table-striped table-bordered', 'style'=>'font-size:13px'],
                'attributes' => [
                    'name',
                    'created_at:datetime',
                    [
                        'attribute' => 'map_color',
                        'format' => 'raw',
                        'value' => function($model){
                            $dot = '<span class="dot" style="height: 25px;width: 25px;border-radius: 50%;display: inline-block;background-color: '.Html::encode($model->map_color).';"></span><br><b>'.Html::encode($model->map_color).'</b>';
                            return $dot;
                        }
                    ],
                    [
                        'attribute' => 'icon',
                        'format' => 'raw',
                        'value' => function($model){
                            if($model->icon != 'marker-icon.png'){
                                return Html::img($model->folderurl.'/'.$model->icon,['width' => '32px', 'height' => '32px']);
                            }
                            return Html::img($model->folderurl.'/'.$model->icon,['width' => '25px', 'height' => '41px']);
                        },
                    ],
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
