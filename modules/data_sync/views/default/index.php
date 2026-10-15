<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\sync_rup_penyedia\models\SyncLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Sync Logs');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs(
    <<< EOT_JS_CODE

$(document).ready(function(){
    setInterval(function(){
        $.pjax.reload({container: '#data-sync-pjax', async: false});
    }, 10000);
});

EOT_JS_CODE

);

?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="sync-log-index">
            <p>
                <?php
                // echo Html::a(Yii::t('app', 'Create Sync Log'), ['create'], ['class' => 'btn btn-success']); 
                ?>
            </p>

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>
            <!-- <div class="row">
                <div class="col-md-6">
                    <h4>Sinkronisasi Tender dan Nontender</h4>
                    <?= Html::a('Tender dan Nontender', ['tender-nontender'], ['class' => 'btn btn-warning update_penyedia']) ?>
                </div>
            </div> -->
            <div class="row">
                <div class="col-md-6">
                    <h4>Sinkronisasi RUP Penyedia</h4>
                    <?= Html::a('RUP Penyedia', ['rup-penyedia'], ['class' => 'btn btn-warning update_penyedia']) ?>
                    <?= Html::a('Anggaran RUP Penyedia', ['rup-penyedia-anggaran'], ['class' => 'btn btn-warning update_penyedia']) ?>
                </div>
                <div class="col-md-6">
                    <h4>Sinkronisasi RUP Sewakelola</h4>
                    <?= Html::a('RUP Swakelola', ['rup-swakelola'], ['class' => 'btn btn-warning update_swakelola']) ?>
                    <?= Html::a('Anggaran RUP Swakelola', ['rup-swakelola-anggaran'], ['class' => 'btn btn-warning update_swakelola']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h4>Sinkronisasi PBJ (Tender)</h4>
                    <?= Html::a('Tender', ['tender-pbj'], ['class' => 'btn btn-warning update_penyedia']) ?>
                    <?= Html::a('Non Tender', ['non-tender-pbj'], ['class' => 'btn btn-warning update_penyedia']) ?>
                </div>
                <div class="col-md-6">
                    <h4>Sinkronisasi Program & Kegiatan</h4>
                    <?= Html::a('Program', ['rup-program'], ['class' => 'btn btn-warning update_program']) ?>
                    <?= Html::a('Kegiatan', ['rup-kegiatan'], ['class' => 'btn btn-warning update_kegiatan']) ?>
                </div>
            </div>
            <div class="row">
                <!-- <div class="col-md-6">
                    <h4>Sinkronisasi RUP E Purchasing</h4>
                    <?= Html::a('E Purchasing', ['epurchasing'], ['class' => 'btn btn-warning update_epurchasing']) ?>
                </div> -->
            </div>
            <!-- <div class="row">
                <div class="col-md-6">
                    <h4>Sinkronisasi Pencatatan Non Tender</h4>
                    <?= Html::a('Pencatatan Non Tender', ['pencatatan-non-tender'], ['class' => 'btn btn-warning update_pencatatan_nontender']) ?>
                    <?= Html::a('Pencatatan Non Tender Realisasi', ['pencatatan-non-tender-realisasi'], ['class' => 'btn btn-warning update_pencatatan_nontender_realisasi']) ?>
                </div>
                <div class="col-md-6">
                    <h4>Sinkronisasi Pencatatan Swakelola</h4>
                    <?= Html::a('Pencatatan Swakelola', ['pencatatan-swakelola'], ['class' => 'btn btn-warning update_pencatatan_nontender']) ?>
                    <?= Html::a('Pencatatan Swakelola Realisasi', ['pencatatan-swakelola-realisasi'], ['class' => 'btn btn-warning update_pencatatan_nontender_realisasi']) ?>
                </div>
            </div> -->

            <?php Pjax::begin(['id' => 'data-sync-pjax']); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'name',
                    'record_total',
                    'queue_total',
                    'queue_finished',
                    'created_at:datetime',
                    'updated_at:datetime',

                    // [
                    // 'class' => 'yii\grid\ActionColumn',
                    // 'header'=> Yii::t('app', 'Action'), 
                    // 'headerOptions' => ['width' => '80'],
                    // 'template' => '{view} {update} {delete}',
                    // 'buttons' => [
                    //         'view' => function ($url,$model,$key) {

                    //             return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 
                    //             [
                    //                 Url::toRoute(['view', 'id' => Yii::$app->hasher->encode($model->id)]) 
                    //             ]
                    //         );

                    //         },
                    //         'update' => function ($url,$model,$key) {

                    //             return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 
                    //             [
                    //                 Url::toRoute(['update', 'id' => Yii::$app->hasher->encode($model->id)]) 
                    //             ]
                    //         );
                    //         },
                    //         'delete' => function ($action,$model,$key) {

                    //             return Html::a('<span class="glyphicon glyphicon-trash"></span>', 
                    //                 [
                    //                     Url::toRoute(['delete', 'id' => Yii::$app->hasher->encode($model->id)])
                    //                 ], 
                    //                 [
                    //                     //'class' => 'btn btn-danger',
                    //                     'data' => [
                    //                     'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    //                     'method' => 'post',
                    //                     'params' => [], // <- extra level
                    //                 ],
                    //                 ]
                    //             );
                    //         },
                    //     ],
                    // ],
                ],
            ]); ?>

            <?php Pjax::end(); ?>

        </div>
    </div>
</section>