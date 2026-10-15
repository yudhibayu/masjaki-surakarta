<?php

use app\models\Eksekutoruser;
use app\overrides\user\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\EksekutorlistSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Eksekutors');
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="eksekutorlist-index">

            <p>
                <?= Html::a(Yii::t('app', 'Create Eksekutor'), ['create'], ['class' => 'btn btn-success']) ?>
               
            </p>

            <?php Pjax::begin(['id' => 'eksekutorlist-yea-pjax']); ?>

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <?= GridView::widget(
                    [
                        'dataProvider' => $dataProvider,
                        'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                        'filterModel' => $searchModel,
                        'pager' => [
                            'firstPageLabel' => Yii::t('app', 'First'),
                            'lastPageLabel' => Yii::t('app', 'Last'),
                        ],
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            'name',
                            'user_name',
                            // [
                            //     'label' => 'User',
                            //     'format' => 'html',
                            //     'value' => function ($model) {
                            //         $usereksekutor = Eksekutoruser::find()->where(['eksekutor_id' => $model->id, 'fiscal_year' => Yii::$app->session['year']['name']])->one();
                            //         if ($usereksekutor) {
                            //             $user = User::findOne(['id' => $usereksekutor->user_id]);
                            //             $username = isset($user->profile->name) ? $user->profile->name : $user->username;
                            //             return Html::a($username, ['/user/admin/update-profile', 'id' => $user->id]);
                            //         }
                            //         return '-';
                            //     },
                            // ],
                            // 'created_at:datetime',
                            [
                                'attribute' => 'created_by',
                                'value' => 'maker',
                                'filter' => false
                            ],
                            // 'updated_at:datetime',
                            // [
                            //     'attribute' => 'updated_by',
                            //     'value' => 'updater',
                            //     'filter' => false
                            // ],

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