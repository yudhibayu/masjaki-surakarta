<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\modules\api\models\ApiAccessToken */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Api Access Tokens'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="api-access-token-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <div class="text-right">
        <?php // echo Html::a(Yii::t('app', 'Back to List'), [$url_prev], ['class' => 'btn btn-warning']) ?>
        <?php // echo Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => Yii::$app->hasher->encode($model->id)], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <br>
    <script>
        function showToken(id){
            if(document.getElementById(id).hasAttribute('hidden')){
                document.getElementById(id).removeAttribute('hidden');
                document.getElementById(id+'-button').setAttribute('hidden','true');
                document.getElementById(id+'-button').setAttribute('class','btn btn-warning hidden');
            }
            else{
                document.getElementById(id).setAttribute('hidden','true');
                document.getElementById(id+'-button').removeAttribute('hidden');
            }
        }
    </script>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            [
                'attribute' => 'token',
                'format' => 'raw',
                'value' => function($model){
                    return '<p><button class="btn btn-warning" id="token-'.$model->id.'-button" onclick="showToken(\'token-'.$model->id.'\')">'.Yii::t('app','View').'</button><code><b id="token-'.$model->id.'" hidden="true">'.$model->token.'</b></code></p>';
                }
            ],
            'created_at:datetime',
            [
              'attribute' => 'created_by', 
              'value' => $model->maker
            ],
            // 'updated_at:datetime',
            // [
            //   'attribute' => 'updated_by', 
            //   'value' => $model->updater
            // ],
        ],
    ]) ?>
    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Back to List'), [$url_prev], ['class' => 'btn btn-warning']) ?>
    </div>
</div>
