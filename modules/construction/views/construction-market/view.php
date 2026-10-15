<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\modules\construction\models\ConstructionPlan */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Construction Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="rup-view">

            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), [$url_prev], ['class' => 'btn btn-warning']) ?>
                <?= Html::a(Yii::t('app', 'Update'), ['/rup/default/update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['/rup/default/delete', 'id' => Yii::$app->hasher->encode($model->id)], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
            <br>
            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <div class="table-responsive">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                        'attributes' => [
                            [
                                'attribute' => 'tipe_rup',
                                'value' => function ($model) {
                                    $data = [
                                        0 => Yii::t('rup', 'RUP Penyedia'),
                                        1 => Yii::t('rup', 'RUP Swakelola'),
                                    ];
                                    return $data[$model->tipe_rup];
                                },
                            ],
                            'idrup',
                            'namapaket:ntext',
                            'tahunanggaran',
                            'jumlahpagu',
                            'klpd',
                            'namasatker:ntext',
                            'tahunanggaran',
                            'kodesatker',
                            'metodepengadaan',
                            // 'idmetodepengadaan',
                            'jenispengadaan',
                            // 'idjenispengadaan',
                            'sumberdana',
                            'spesifikasi:ntext',
                            'lokasi:ntext',
                            'tanggalkebutuhan:date',
                            'tanggalawalpemilihan:date',
                            'tanggalakhirpemilihan:date',
                            'tanggalawalpekerjaan:date',
                            'tanggalakhirpekerjaan:date',
                            'statuspradipa',
                            'statuspenyedia',
                            'statuspdn',
                            'statususahakecil',
                            'statusumumkan',
                            'keterangan:ntext',
                            'ppk',
                            'username',
                            'tanggalpengumuman:date',
                            'id_swakelola',
                            'statusdeletepaket',
                            'statusaktifpaket',
                            'id_rup_client',
                            'kd_klpd',
                            'nip_ppk',
                            'volume:ntext',
                            'lokasi_kab:ntext',
                            'lokasi_prov:ntext',
                            'statuspaketkonsolidasi',
                            'idsatker',
                            'tipe_swakelola',
                            'created_at:datetime',
                            'updated_at:datetime',
                            [
                                'attribute' => 'created_by',
                                'value' => $model->maker
                            ],
                            [
                                'attribute' => 'updated_by',
                                'value' => $model->updater
                            ],
                            'kode_klpd_penyelenggara',
                            'nama_klpd_penyelenggara',
                            'nama_satker_penyelenggara:ntext',
                        ],
                    ]) ?>
                </div>
            </div>
            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Back to List'), [$url_prev], ['class' => 'btn btn-warning']) ?>
                <?= Html::a(Yii::t('app', 'Update'), ['/rup/default/update', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['/rup/default/delete', 'id' => Yii::$app->hasher->encode($model->id)], [
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