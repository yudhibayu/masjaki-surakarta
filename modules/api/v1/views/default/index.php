<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\api\models\ApiAccessTokenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Api Access Tokens');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="api-access-token-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a(Yii::t('app', 'Create Api Access Token'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            // 'token',
            'created_at:datetime',
            [
              'attribute' => 'created_by', 
              'value' => 'maker',
              'filter' => false
            ],
            // 'updated_at:datetime',
             /*[
              'attribute' => 'updated_by', 
              'value' => 'updater',
              'filter' => false
            ],*/ 

            [
            'class' => 'yii\grid\ActionColumn',
            'header'=> Yii::t('app', 'Action'), 
            'headerOptions' => ['width' => '80'],
            'template' => '{view}',
            'buttons' => [
                    'view' => function ($url,$model,$key) {
                        
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span> View', 
                        [
                            Url::toRoute(['view', 'id' => Yii::$app->hasher->encode($model->id)]) 
                        ]
                    );
                    
                    },
                    'update' => function ($url,$model,$key) {

                        return Html::a('<span class="glyphicon glyphicon-pencil"></span> Update', 
                        [
                            Url::toRoute(['update', 'id' => Yii::$app->hasher->encode($model->id)]) 
                        ]
                    );
                    },
                    'delete' => function ($action,$model,$key) {

                        return Html::a('<span class="glyphicon glyphicon-trash"></span> Delete', 
                            [
                                Url::toRoute(['delete', 'id' => Yii::$app->hasher->encode($model->id)])
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
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
