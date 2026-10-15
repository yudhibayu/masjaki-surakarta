<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\Pjax;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\hitcounter\models\HitCounterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Hit Counters');
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="hit-counter-index">
            <p>
                <!-- <= Html::a(Yii::t('app', 'Create Hit Counter'), ['create'], ['class' => 'btn btn-success']) ?> -->
            </p>

            <?php Pjax::begin(['id' => 'hit-counter-yea-pjax']); ?>

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <div class="table-responsive">

                    <?= GridView::widget(
                        [
                            'dataProvider' => $dataProvider,
                            'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover', 'style' => 'font-size:11px'],
                            'filterModel' => $searchModel,
                            'pager' => [
                                'firstPageLabel' => Yii::t('app', 'First'),
                                'lastPageLabel' => Yii::t('app', 'Last'),
                            ],
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => Yii::t('app', 'Action'),
                                    'headerOptions' => ['width' => '150'],
                                    'template' => '{view}',
                                    'buttons' => [
                                        'view' => function ($url, $model, $key) {

                                            return Html::a(
                                                '<span class="fas fa-eye"></span>',
                                                [
                                                    Url::toRoute(['view', 'id' => Yii::$app->hasher->encode($model->id)])
                                                ],
                                                [
                                                    'class' => 'btn btn-xs btn-primary',
                                                    'style' => 'margin: 2px;width: 35px;',
                                                ]
                                            );
                                        },
                                        // 'update' => function ($url, $model, $key) {

                                        //     return Html::a(
                                        //         '<span class="fas fa-edit"></span>',
                                        //         [
                                        //             Url::toRoute(['update', 'id' => Yii::$app->hasher->encode($model->id)])
                                        //         ],
                                        //         [
                                        //             'class' => 'btn btn-xs btn-warning',
                                        //             'style' => 'margin: 2px;width: 35px;',
                                        //         ]
                                        //     );
                                        // },
                                        // 'delete' => function ($action, $model, $key) {

                                        //     return Html::a(
                                        //         '<span class="fas fa-trash"></span>',
                                        //         [
                                        //             Url::toRoute(['delete', 'id' => Yii::$app->hasher->encode($model->id)])
                                        //         ],
                                        //         [
                                        //             'data' => [
                                        //                 'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                        //                 'method' => 'post',
                                        //                 'params' => [], // <- extra level,
                                        //             ],
                                        //             'class' => 'btn btn-xs btn-danger',
                                        //             'style' => 'margin: 2px;width: 35px;',
                                        //         ]
                                        //     );
                                        // },
                                    ],
                                ],
                                'counter_id',
                                'cookie_mark',
                                // 'js_cookei_enabled',
                                // 'js_java_enabled',
                                // 'js_timezone_offset:datetime',
                                'js_timezone',
                                // 'js_connection',
                                'js_current_url:ntext',
                                // 'js_referer_url:ntext',
                                // 'js_screen_width',
                                // 'js_screen_height',
                                // 'js_color_depth',
                                // 'js_browser_language',
                                // 'js_history_length',
                                // 'js_is_toutch_device',
                                // 'js_processor_ram',
                                'serv_ip',
                                // 'serv_user_agent:ntext',
                                'serv_referer_url:ntext',
                                // 'serv_server_name',
                                // 'serv_auth_user_id',
                                'serv_port',

                                // 'serv_cookies',
                                // 'serv_os',
                                // 'serv_client',
                                'serv_device',
                                // 'serv_brand',
                                // 'serv_model',
                                // 'serv_bot',
                                'serv_host_by_ip',
                                // 'serv_is_proxy_or_vpn',
                                'created_at:datetime',

                            ],
                        ]
                    ); ?>
                </div>
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