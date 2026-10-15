<?php

use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\modules\hitcounter\models\HitCounter */

$this->title = $model->cookie_mark;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hit Counters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="hit-counter-view">

            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), [$url_prev], ['class' => 'btn btn-outline-secondary']) ?>
                <!-- <= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?> -->
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
                        'counter_id',
                        'cookie_mark',
                        [
                            'attribute' => 'serv_cookies',
                            'value' => function ($model) {
                                $data = is_string($model->serv_cookies)
                                    ? Json::decode($model->serv_cookies)
                                    : $model->serv_cookies;
                                return '<pre>' . Json::encode($data, JSON_PRETTY_PRINT) . '</pre>';
                            },
                            'contentOptions' => ['style' => 'white-space: pre-wrap;'],
                        ],
                        [
                            'attribute' => 'serv_os',
                            'value' => function ($model) {
                                $data = is_string($model->serv_os)
                                    ? Json::decode($model->serv_os)
                                    : $model->serv_os;
                                return '<pre>' . Json::encode($data, JSON_PRETTY_PRINT) . '</pre>';
                            },
                            'contentOptions' => ['style' => 'white-space: pre-wrap;'],
                        ],
                        [
                            'attribute' => 'serv_client',
                            'value' => function ($model) {
                                $data = is_string($model->serv_client)
                                    ? Json::decode($model->serv_client)
                                    : $model->serv_client;
                                return '<pre>' . Json::encode($data, JSON_PRETTY_PRINT) . '</pre>';
                            },
                            'contentOptions' => ['style' => 'white-space: pre-wrap;'],
                        ],
                        'js_cookei_enabled',
                        'js_java_enabled',
                        'js_timezone_offset:datetime',
                        'js_timezone',
                        'js_connection',
                        'js_current_url:ntext',
                        'js_referer_url:ntext',
                        'js_screen_width',
                        'js_screen_height',
                        'js_color_depth',
                        'js_browser_language',
                        'js_history_length',
                        'js_is_toutch_device',
                        'js_processor_ram',
                        'serv_ip',
                        'serv_user_agent:ntext',
                        'serv_referer_url:ntext',
                        'serv_server_name',
                        'serv_auth_user_id',
                        'serv_port',

                        // 'serv_cookies',
                        // 'serv_os',
                        // 'serv_client',
                        'serv_device',
                        'serv_brand',
                        'serv_model',
                        'serv_bot',
                        'serv_host_by_ip',
                        'serv_is_proxy_or_vpn',
                        'created_at:datetime',
                    ],
                ]) ?>
            </div>
            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), [$url_prev], ['class' => 'btn btn-outline-secondary']) ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?>
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