<?php

use kartik\number\NumberControl;
use kartik\widgets\DatePicker;
use kartik\widgets\DateTimePicker;
use kartik\widgets\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\rup\models\Rup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rup-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- Wajib Diisi -->
    <h3>Data Wajib</h3>
    <br>
    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'tipe_rup')->dropDownList([
                0 => 'RUP Penyedia',
                1 => 'RUP Swakelola',
            ]) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'idrup')->textInput() ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'namapaket')->textarea(['rows' => 4]) ?>
        </div>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'jumlahpagu')->widget(NumberControl::classname(), [
                'maskedInputOptions' => [
                    'removeMaskOnSubmit' => true,
                    'prefix' => 'Rp. ',
                    'digits' => 2,
                    'groupSeparator' => '.',
                    'radixPoint' => ','
                ],
                'options' => [
                    'id' => 'jumlahpagu',
                ],
            ]);
            ?>
        </div>
    </div>
    <hr>
    <!-- Wajib Diisi -->

    <!-- Tidak Dipakai -->
    <!-- 
    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'kode_klpd_penyelenggara')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'nama_klpd_penyelenggara')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'nama_satker_penyelenggara')->textarea(['rows' => 4]) ?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'statusdeletepaket')->textInput() ?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'statusaktifpaket')->textInput() ?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'id_rup_client')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'id_swakelola')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
     -->
    <!-- Tidak Dipakai -->

    <!-- Opsional Dari Sync -->
    <!--     
    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'idsatker')->textInput() ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
        <?= $form->field($model, 'kd_klpd')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'klpd')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    -->
    <!-- Opsional Dari Sync -->

    <!-- Otomatis -->
    <!--
    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'tahunanggaran')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    -->
    <!-- Otomatis -->

    <!-- Pilihan -->
    <div class='row'>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'kodesatker')->widget(Select2::class, [
                'data' => $dropdown_data['satker'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'kodesatker_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'tipe_swakelola')->dropDownList([
                null => 'Bukan Swakelola',
                1 => 'I',
                2 => 'II',
                3 => 'III',
                4 => 'IV',
            ]) ?>
        </div>
    </div>

    <!-- <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'namasatker')->textarea(['rows' => 4]) ?>
        </div>
    </div> -->

    <div class='row'>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'idmetodepengadaan')->widget(Select2::class, [
                'data' => $dropdown_data['metodepengadaan'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'idmetodepengadaan_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
        <!-- <div class='col-md-6'>
            <=
            $form->field($model, 'idjenispengadaan')->widget(Select2::class, [
                'data' => $dropdown_data['jenispengadaan'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'idjenispengadaan_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div> -->
    </div>

    <!-- <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'metodepengadaan')->textInput(['maxlength' => true]) ?>
        </div>
    </div> -->

    <!-- <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'jenispengadaan')->textInput(['maxlength' => true]) ?>
        </div>
    </div> -->

    <div class='row'>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'statuspradipa')->widget(Select2::class, [
                'data' => $dropdown_data['statuspradipa'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'statuspradipa_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'statuspenyedia')->widget(Select2::class, [
                'data' => $dropdown_data['statuspenyedia'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'statuspenyedia_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'statuspdn')->widget(Select2::class, [
                'data' => $dropdown_data['statuspdn'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'statuspdn_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'statususahakecil')->widget(Select2::class, [
                'data' => $dropdown_data['statususahakecil'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'statususahakecil_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <!-- <=
            $form->field($model, 'statusumumkan')->widget(Select2::class, [
                'data' => $dropdown_data['statusumumkan'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'statusumumkan_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?> -->
        </div>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'statuspaketkonsolidasi')->widget(Select2::class, [
                'data' => $dropdown_data['statuspaketkonsolidasi'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'statuspaketkonsolidasi_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'sumberdana')->textInput(['maxlength']);
            ?>
        </div>
    </div>

    <!-- Pilihan -->
    <hr>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'spesifikasi')->textarea(['rows' => 4]) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'lokasi')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'tanggalkebutuhan')->widget(DatePicker::classname(), [
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'tanggalkebutuhan_select'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'todayHighlight' => true,
                    'format' => 'yyyy-mm-dd',
                    //'maxViewMode' => 'month',
                    //'minViewMode' => 'days',

                ]
            ]);
            ?>
        </div>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'tanggalpengumuman')->widget(DateTimePicker::classname(), [
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'tanggalpengumuman_select'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'todayHighlight' => true,
                    'format' => 'yyyy-mm-dd hh:ii',
                    //'maxViewMode' => 'month',
                    //'minViewMode' => 'days',
                ]
            ]);
            ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'tanggalawalpemilihan')->widget(DatePicker::classname(), [
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'tanggalawalpemilihan_select'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'todayHighlight' => true,
                    'format' => 'yyyy-mm-dd',
                    //'maxViewMode' => 'month',
                    //'minViewMode' => 'days',

                ]
            ]);
            ?>
        </div>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'tanggalakhirpemilihan')->widget(DatePicker::classname(), [
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'tanggalakhirpemilihan_select'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'todayHighlight' => true,
                    'format' => 'yyyy-mm-dd',
                    //'maxViewMode' => 'month',
                    //'minViewMode' => 'days',

                ]
            ]);
            ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'tanggalawalpekerjaan')->widget(DatePicker::classname(), [
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'tanggalawalpekerjaan_select'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'todayHighlight' => true,
                    'format' => 'yyyy-mm-dd',
                    //'maxViewMode' => 'month',
                    //'minViewMode' => 'days',

                ]
            ]);
            ?>
        </div>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'tanggalakhirpekerjaan')->widget(DatePicker::classname(), [
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'tanggalakhirpekerjaan_select'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'todayHighlight' => true,
                    'format' => 'yyyy-mm-dd',
                    //'maxViewMode' => 'month',
                    //'minViewMode' => 'days',

                ]
            ]);
            ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'ppk')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'nip_ppk')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'keterangan')->textarea(['rows' => 4]) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'volume')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'lokasi_kab')->textarea(['rows' => 4]) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'lokasi_prov')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-warning']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>