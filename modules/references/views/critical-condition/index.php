<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\references\models\RefCriticalConditionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Critical Conditions');
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="ref-critical-condition-index">

            <p>
                <?= Html::a(Yii::t('app', 'Create Critical Condition'), ['create'], ['class' => 'btn btn-success']) ?>
            </p>

            <?php Pjax::begin(['id' => 'ref-critical-condition-yea-pjax']); ?>

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <?= GridView::widget(
                    [
                        'dataProvider' => $dataProvider,
                        'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            'name',
                            'code',
                            [
                                'attribute' => 'is_active',
                                'filter' => [
                                    0 => Yii::t('app', 'No'),
                                    1 => Yii::t('app', 'Yes'),
                                ],
                                'value' => function ($model) {
                                    $value = [
                                        0 => Yii::t('app', 'No'),
                                        1 => Yii::t('app', 'Yes'),
                                    ];
                                    return $value[$model->is_active];
                                },
                            ],
                            'created_at:datetime',
                            [
                                'attribute' => 'created_by',
                                'value' => 'maker',
                                'filter' => false
                            ],
                            'updated_at:datetime',
                            [
                                'attribute' => 'updated_by',
                                'value' => 'updater',
                                'filter' => false
                            ],

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => Yii::t('app', 'Action'),
                                'headerOptions' => ['width' => '80'],
                                'template' => '{view} {update} {delete}',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {

                                        return Html::a(
                                            '<span class="fas fa-eye"></span>',
                                            [
                                                Url::toRoute(['view', 'id' => Yii::$app->hasher->encode($model->id)])
                                            ]
                                        );
                                    },
                                    'update' => function ($url, $model, $key) {

                                        return Html::a(
                                            '<span class="fas fa-edit"></span>',
                                            [
                                                Url::toRoute(['update', 'id' => Yii::$app->hasher->encode($model->id)])
                                            ]
                                        );
                                    },
                                    'delete' => function ($action, $model, $key) {

                                        return Html::a(
                                            '<span class="fas fa-trash"></span>',
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
                    ],
                ); ?>
            </div>
            <?php Pjax::end(); ?>

        </div>
    </div>
</section>

<?= $this->registerJs(
    <<< EOT_JS_CODE

(function($){
    $(document).on('ajaxError', function(event, xhr) {
      if (xhr.status === 401 || xhr.status === 403) {
        window.location.reload();
      }
    });
})(jQuery);

EOT_JS_CODE

);
?>