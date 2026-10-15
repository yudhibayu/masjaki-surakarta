<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\rup\models\RupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('rup', 'Construction Plan');
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <!-- <h3><= Html::encode($this->title) ?></h3> -->
        <h3><i class="menu-icon tf-icons bx bx-calendar"></i> <?= Html::encode($this->title) ?> - List Data</h3>
        <p>Manajemen Rencana Konstruksi</p>

    </div>
    <div class="card-body">
        <div class="rup-index">

            <p>
                <?= Html::a('<i class="menu-icon tf-icons bx bx-plus fw-bold"></i> ' . Yii::t('rup', 'Add Construction Plan'), ['create'], ['class' => 'btn btn-success fw-bold']) ?>
                <?= Html::a('<i class="menu-icon tf-icons bx bx-sync"></i> Sinkronisasi Rencana Konstruksi', ['/data-sync/default/rup-penyedia'], ['class' => 'btn btn-warning fw-bold']) ?>
                <!-- <= Html::a('Sinkronisasi RUP swakelola', ['/data-sync/default/rup-swakelola'], ['class' => 'btn btn-warning']) ?> -->
            </p>

            <?php Pjax::begin(['id' => 'rup-yea-pjax']); ?>

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>
            <br>

            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <div class="table-responsive">
                    <?= GridView::widget(
                        [
                            'dataProvider' => $dataProvider,
                            'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                            'filterModel' => $searchModel,
                            'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '-'],
                            'pager' => [
                                'firstPageLabel' => Yii::t('app', 'First'),
                                'lastPageLabel' => Yii::t('app', 'Last'),
                            ],
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                'idrup',
                                [
                                    'attribute' => 'statusumumkan',
                                    'filter' => $statusumumkanfilter,
                                ],
                                'namapaket:ntext',
                                'tahunanggaran',
                                [
                                    'attribute' => 'tipe_rup',
                                    'value' => function ($model) {
                                        $data = [
                                            0 => Yii::t('rup', 'RUP Penyedia'),
                                            1 => Yii::t('rup', 'RUP Swakelola'),
                                        ];
                                        return $data[$model->tipe_rup];
                                    },
                                    'filter' => [
                                        0 => Yii::t('rup', 'RUP Penyedia'),
                                        1 => Yii::t('rup', 'RUP Swakelola'),
                                    ],
                                ],
                                // 'klpd',
                                [
                                    'attribute' => 'jumlahpagu',
                                    'format' => ['decimal', 2],
                                ],
                                // [
                                //     'attribute' => 'sumberdana',
                                //     'filter' => $sumberdanafilter,
                                // ],
                                //'tahunanggaran',
                                //'namasatker:ntext',
                                //'kodesatker',
                                //'metodepengadaan',
                                // 'idmetodepengadaan',
                                [
                                    'attribute' => 'jenispengadaan',
                                    'value' => 'jenispengadaan',
                                    'filter' => $jenispengadaan,
                                ],
                                //'spesifikasi:ntext',
                                //'lokasi:ntext',
                                //'tanggalkebutuhan:date',
                                //'tanggalawalpemilihan:date',
                                //'tanggalakhirpemilihan:date',
                                //'tanggalawalpekerjaan:date',
                                //'tanggalakhirpekerjaan:date',
                                //'statuspradipa',
                                //'statuspenyedia',
                                //'statuspdn',
                                //'statususahakecil',
                                //'keterangan:ntext',
                                //'ppk',
                                //'username',
                                //'tanggalpengumuman:date',
                                //'id_swakelola',
                                //'statusdeletepaket',
                                //'statusaktifpaket',
                                //'id_rup_client',
                                //'kd_klpd',
                                //'nip_ppk',
                                //'volume:ntext',
                                //'lokasi_kab:ntext',
                                //'lokasi_prov:ntext',
                                //'statuspaketkonsolidasi',
                                //'idsatker',
                                //'tipe_swakelola',
                                //'created_at:datetime',
                                //'updated_at:datetime',
                                /*[
                        'attribute' => 'created_by', 
                        'value' => 'maker',
                        'filter' => false
                        ],*/
                                /*[
                        'attribute' => 'updated_by', 
                        'value' => 'updater',
                        'filter' => false
                        ],*/
                                //'kode_klpd_penyelenggara',
                                //'nama_klpd_penyelenggara',
                                //'nama_satker_penyelenggara:ntext',

                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => Yii::t('app', 'Action'),
                                    'headerOptions' => ['width' => '80'],
                                    'template' => '{view} {update} {delete}',
                                    'buttons' => [
                                        'view' => function ($url, $model, $key) {

                                            return Html::a(
                                                '<span class="bx bx-show"></span>',
                                                [
                                                    Url::toRoute(['view', 'id' => Yii::$app->hasher->encode($model->id)])
                                                ],
                                                [
                                                    'class' => 'btn btn-xs btn-primary',
                                                    'style' => 'margin: 2px;width: 35px;',
                                                ]
                                            );
                                        },
                                        'update' => function ($url, $model, $key) {

                                            return Html::a(
                                                '<span class="bx bx-edit"></span>',
                                                [
                                                    Url::toRoute(['update', 'id' => Yii::$app->hasher->encode($model->id)])
                                                ],
                                                [
                                                    'class' => 'btn btn-xs btn-warning',
                                                    'style' => 'margin: 2px;width: 35px;',
                                                ]
                                            );
                                        },
                                        'delete' => function ($action, $model, $key) {

                                            return Html::a(
                                                '<span class="bx bx-trash"></span>',
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
                                                    'class' => 'btn btn-xs btn-danger',
                                                    'style' => 'margin: 2px;width: 35px;',
                                                ]
                                            );
                                        },
                                    ],
                                ],
                            ],
                        ],
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