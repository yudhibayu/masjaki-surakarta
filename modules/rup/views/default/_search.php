<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\rup\models\RupSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rup-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'tipe_rup') ?>

    <?= $form->field($model, 'klpd') ?>

    <?= $form->field($model, 'tahunanggaran') ?>

    <?= $form->field($model, 'idrup') ?>

    <?= $form->field($model, 'namapaket') ?>

    <?php // echo $form->field($model, 'jumlahpagu') ?>

    <?php // echo $form->field($model, 'namasatker') ?>

    <?php // echo $form->field($model, 'kodesatker') ?>

    <?php // echo $form->field($model, 'metodepengadaan') ?>

    <?php // echo $form->field($model, 'idmetodepengadaan') ?>

    <?php // echo $form->field($model, 'jenispengadaan') ?>

    <?php // echo $form->field($model, 'idjenispengadaan') ?>

    <?php // echo $form->field($model, 'spesifikasi') ?>

    <?php // echo $form->field($model, 'lokasi') ?>

    <?php // echo $form->field($model, 'tanggalkebutuhan') ?>

    <?php // echo $form->field($model, 'tanggalawalpemilihan') ?>

    <?php // echo $form->field($model, 'tanggalakhirpemilihan') ?>

    <?php // echo $form->field($model, 'tanggalawalpekerjaan') ?>

    <?php // echo $form->field($model, 'tanggalakhirpekerjaan') ?>

    <?php // echo $form->field($model, 'statuspradipa') ?>

    <?php // echo $form->field($model, 'statuspenyedia') ?>

    <?php // echo $form->field($model, 'statuspdn') ?>

    <?php // echo $form->field($model, 'statususahakecil') ?>

    <?php // echo $form->field($model, 'statusumumkan') ?>

    <?php // echo $form->field($model, 'keterangan') ?>

    <?php // echo $form->field($model, 'ppk') ?>

    <?php // echo $form->field($model, 'username') ?>

    <?php // echo $form->field($model, 'tanggalpengumuman') ?>

    <?php // echo $form->field($model, 'id_swakelola') ?>

    <?php // echo $form->field($model, 'statusdeletepaket') ?>

    <?php // echo $form->field($model, 'statusaktifpaket') ?>

    <?php // echo $form->field($model, 'id_rup_client') ?>

    <?php // echo $form->field($model, 'kd_klpd') ?>

    <?php // echo $form->field($model, 'nip_ppk') ?>

    <?php // echo $form->field($model, 'volume') ?>

    <?php // echo $form->field($model, 'lokasi_kab') ?>

    <?php // echo $form->field($model, 'lokasi_prov') ?>

    <?php // echo $form->field($model, 'statuspaketkonsolidasi') ?>

    <?php // echo $form->field($model, 'idsatker') ?>

    <?php // echo $form->field($model, 'tipe_swakelola') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'kode_klpd_penyelenggara') ?>

    <?php // echo $form->field($model, 'nama_klpd_penyelenggara') ?>

    <?php // echo $form->field($model, 'nama_satker_penyelenggara') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary form-reset']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php 
$this->registerJs( 
<<< EOT_JS_CODE

$(document).ready(function(){
    $('.form-reset').on('click', function () {
        $.ajax({
            url:"reset_session",
            type: "POST",
            //data:"",
            success:function(data){
            }
        });
    });
});

EOT_JS_CODE

);
?>