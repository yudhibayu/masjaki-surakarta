<?php

use dosamigos\leaflet\LeafLetAsset;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use kartik\number\NumberControl;
use kartik\widgets\DepDrop;
use yii\web\JsExpression;
use yii\widgets\Pjax;

LeafLetAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\modules\monitoring\models\MonitoringTask */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="monitoring-task-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-data-monitoring',
    ]); ?>

    <h3>Detail Paket</h3>

    <div class='row'>
        <div class='col-md-12'>
            <?php // echo $form->field($model, 'unit_id')->dropDownList($dropdown_data['unit_id'], ['prompt' => Yii::t('app', 'Choose')])
            ?>
            <?=
            $form->field($model, 'unit_id')->widget(Select2::class, [
                'data' => $dropdown_data['unit_id'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'unit_id_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <?=
            $form->field($model, 'idjenispengadaanrup')->widget(Select2::class, [
                'data' => $dropdown_data['idjenispengadaanrup'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'idjenispengadaanrup_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
        <div class="col-md-9">
            <?=
            $form->field($model, 'idrup')->widget(DepDrop::class, [
                'options' => [
                    'placeholder' => Yii::t('app', 'Choose'),
                    'id' => 'idrup_select',
                ],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => false]],
                'pluginOptions' => [
                    'depends' => ['unit_id_select', 'idjenispengadaanrup_select'],
                    'initialize' => true,
                    'url' => Url::to(['/rup/default/depdrop-by-satker']),
                    'loadingText' => Yii::t('app', 'Loading...'),
                ]
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?=
            $form->field($model, 'idjenispengadaanpbj')->widget(Select2::class, [
                'data' => $dropdown_data['idjenispengadaanpbj'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'idjenispengadaanpbj_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
        <div class="col-md-9">
            <?=
            $form->field($model, 'pbj_id')->widget(DepDrop::class, [
                'options' => [
                    'placeholder' => Yii::t('app', 'Choose'),
                    'id' => 'pbj_id_select',
                ],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                'pluginOptions' => [
                    'depends' => ['unit_id_select', 'idjenispengadaanpbj_select'],
                    'initialize' => true,
                    'url' => Url::to(['/rup/pbj/depdrop-by-satker']),
                    'loadingText' => Yii::t('app', 'Loading...'),
                ]
            ]);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= Html::button(Yii::t('app', 'Autofill'), ['id' => 'autofillbutton', 'class' => 'btn btn-primary']) ?>
            <?= Html::a('Travel', '', ['id' => 'redirectfill', 'hidden' => true, 'class' => 'hidden',]); ?>
        </div>
    </div>

    <hr>

    <div class='row'>
        <div class='col-md-12'>
            <?= $form->field($model, 'name')->textarea(['rows' => 4]) ?>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'location_description')->textarea(['rows' => 4]) ?>
        </div>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'object_type_id')->widget(Select2::class, [
                'data' => $dropdown_data['object_type'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'object_type_id_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class='col-md-6'>
            <?=
            $form->field($model, 'kecamatan_id')->widget(Select2::class, [
                'data' => $dropdown_data['kecamatan_id'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'kecamatan_id_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'kelurahan_id')->widget(DepDrop::class, [
                'options' => [
                    'placeholder' => Yii::t('app', 'Choose'),
                    'id' => 'kelurahan_id_select',
                ],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                'pluginOptions' => [
                    'depends' => ['kecamatan_id_select'],
                    'initialize' => true,
                    'url' => Url::to(['/references/kelurahan/depdrop-by-kecamatan']),
                    'loadingText' => Yii::t('app', 'Loading...'),
                ]
            ]); ?>
        </div>
    </div>
    <div class="row">
        <div class='col-md-2'>
            <?= $form->field($model, 'work_range_start')->textInput() ?>
        </div>
        <div class='col-md-2'>
            <?= $form->field($model, 'work_range_end')->textInput() ?>
        </div>
        <div class='col-md-2'>
            <?= $form->field($model, 'work_units_of')->textInput() ?>
        </div>
        <div class='col-md-3'>
            <?= $form->field($model, 'work_volume')->textInput() ?>
        </div>
        <div class='col-md-3'>
            <?= $form->field($model, 'work_finished_number')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class='col-md-2'>
            <?= $form->field($model, 'default_lat')->textInput([
                'id' => 'monitoringtask-default_lat'
            ]) ?>
        </div>
        <div class='col-md-2'>
            <?= $form->field($model, 'default_lng')->textInput([
                'id' => 'monitoringtask-default_lng'
            ]) ?>
        </div>
        <div class='col-md-8'>
            <?= Html::label('<i>Gunakan Marker untuk memilih titik utama</i>', 'map'); ?>
            <div class="map" id="map" style="height: 600px; border: 1px solid rgb(204, 204, 204); position: relative;">Peta</div>
        </div>
    </div>

    <hr>
    <h3>Anggaran</h3>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'budget')->widget(NumberControl::class, [
                'maskedInputOptions' => [
                    'removeMaskOnSubmit' => true,
                    //'prefix' => 'Rp. ',
                    'digits' => 4,
                    'groupSeparator' => '.',
                    'radixPoint' => ','
                ],
                'options' => [
                    'class' => 'budget',
                ],
            ]) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'hps')->widget(NumberControl::class, [
                'maskedInputOptions' => [
                    'removeMaskOnSubmit' => true,
                    //'prefix' => 'Rp. ',
                    'digits' => 4,
                    'groupSeparator' => '.',
                    'radixPoint' => ','
                ],
                'options' => [
                    'class' => 'hps',
                ],
            ]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'bid_price')->widget(NumberControl::class, [
                'maskedInputOptions' => [
                    'removeMaskOnSubmit' => true,
                    //'prefix' => 'Rp. ',
                    'digits' => 4,
                    'groupSeparator' => '.',
                    'radixPoint' => ','
                ],
                'options' => [
                    'class' => 'bid_price',
                ],
            ]) ?>
        </div>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'final_price')->widget(NumberControl::class, [
                'maskedInputOptions' => [
                    'removeMaskOnSubmit' => true,
                    //'prefix' => 'Rp. ',
                    'digits' => 4,
                    'groupSeparator' => '.',
                    'radixPoint' => ','
                ],
                'options' => [
                    'class' => 'final_price',
                ],
            ]) ?>
        </div>
    </div>

    <hr>
    <h3>Satuan bersangkutan</h3>

    <div class='row'>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'team_id')->widget(Select2::class, [
                'data' => $dropdown_data['teams'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'teams_status_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
        <!-- <div class='col-md-6'>
            <?= $form->field($model, 'supervisor_name')->textInput()->hiddenInput()->label(false) ?>
        </div> -->
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'ppkom_name')->textInput() ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'ppkom_nip')->textInput() ?>
        </div>
        <!-- <div class='col-md-6'>
            <?php // echo $form->field($model, 'executor_name')->textInput()
            ?>
        </div> -->
    </div>

    <hr>
    <h3>Program dan Kegiatan</h3>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'program_id')->widget(Select2::class, [
                'data' => [],
                'options' => [
                    'placeholder' => Yii::t('app', 'Choose'),
                    'id' => 'program_id_select'
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'ajax' => [
                        'url' => Url::to(['/rup/program/select']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                ],
            ]) ?>
            <?php // echo $form->field($model, 'program_name')->textInput() 
            ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'kegiatan_id')->widget(DepDrop::class, [
                'options' => [
                    'placeholder' => Yii::t('app', 'Choose'),
                    'id' => 'kegiatan_id_select',
                ],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                'pluginOptions' => [
                    'depends' => ['program_id_select'],
                    'initialize' => true,
                    'url' => Url::to(['/rup/kegiatan/depdrop-by-program']),
                    'loadingText' => Yii::t('app', 'Loading...'),
                ]
            ]); ?>
            <?php // echo $form->field($model, 'kegiatan_name')->textInput() 
            ?>
        </div>
    </div>

    <hr>
    <h3>Jadwal</h3>

    <div class='row'>
        <div class='col-md-4'>
            <?=
            $form->field($model, 'start_date')->widget(DatePicker::class, [
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'start_date'],
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
        <div class='col-md-4'>
            <?=
            $form->field($model, 'end_date')->widget(DatePicker::class, [
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'end_date'],
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
        <div class='col-md-4'>
            <?=
            $form->field($model, 'finally_finished_date')->widget(DatePicker::class, [
                'language' => 'id',
                'options' => ['placeholder' => 'Choose', 'id' => 'finally_finished_date'],
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
            $form->field($model, 'provider_selection_start_date')->widget(DatePicker::class, [
                'language' => 'id',
                'options' => ['placeholder' => 'Choose', 'id' => 'provider_selection_start_date'],
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
            $form->field($model, 'provider_selection_end_date')->widget(DatePicker::class, [
                'language' => 'id',
                'options' => ['placeholder' => 'Choose', 'id' => 'provider_selection_end_date'],
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
        <!-- <div class='col-md-6'>
        <?= $form->field($model, 'estimated_week_finished')->textInput() ?>
    </div> -->
        <div class='col-md-6'>
            <?= $form->field($model, 'work_days')->textInput() ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'critical_day_at')->textInput() ?>
        </div>
        <!-- <div class='col-md-4'>
            <?=
            $form->field($model, 'critical_condition_id')->widget(Select2::class, [
                'data' => $dropdown_data['criticalcondition'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'critical_condition_id_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ])->hiddenInput()->label(false);
            ?>
        </div> -->
    </div>

    <hr>
    <h3>Kontrak</h3>

    <div class='row'>
        <div class='col-md-12'>
            <?= $form->field($model, 'contractor_name')->textInput() ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'contract_number')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'contract_date')->widget(DatePicker::class, [
                'language' => 'id',
                'options' => ['placeholder' => 'Choose', 'id' => 'contract_date'],
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
            <?= $form->field($model, 'spmk_number')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'spmk_date')->widget(DatePicker::class, [
                'language' => 'id',
                'options' => ['placeholder' => 'Choose', 'id' => 'spmk_date'],
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

    <hr>
    <h3>K3</h3>

    <div class='row'>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'is_bpjstk')->widget(Select2::class, [
                'data' => $model->bpjstkStatusName(),
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'is_bpjstk_status_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'bpjstk_number')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'bpjstk_contrib_amount')->widget(NumberControl::class, [
                'maskedInputOptions' => [
                    'removeMaskOnSubmit' => true,
                    //'prefix' => 'Rp. ',
                    'digits' => 4,
                    'groupSeparator' => '.',
                    'radixPoint' => ','
                ],
                'options' => [
                    'class' => 'bpjstk_contrib_amount',
                ],
            ])
            ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'bpjstk_number_workers_protected')->textInput() ?>
        </div>
    </div>

    <hr>
    <h3>Status</h3>

    <div class='row'>
        <div class='col-md-6'>
            <?php // echo $form->field($model, 'unit_id')->dropDownList($dropdown_data['unit_id'], ['prompt' => Yii::t('app', 'Choose')])
            ?>
            <?=
            $form->field($model, 'implementation_status')->widget(Select2::class, [
                'data' => $model->implementationStatusNames(),
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'implementation_status_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'is_active')->widget(Select2::class, [
                'data' => $model->taskActiveStatusName(),
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'is_active_status_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-4'>
            <?=
            $form->field($model, 'is_bast_done')->widget(Select2::class, [
                'data' => $model->bastDoneStatusName(),
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'is_bast_done_status_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
        <div class='col-md-4'>
            <?= $form->field($model, 'bast_no')->textInput(); ?>
        </div>
        <div class='col-md-4'>
            <?=
            $form->field($model, 'bast_date')->widget(DatePicker::class, [
                'language' => 'id',
                'options' => ['placeholder' => 'Choose', 'id' => 'bast_date_select'],
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
        <div class='col-md-12'>
            <?= $form->field($model, 'data_adendum')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-warning']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<div class="load-data-monitoring">
    <?php $form = ActiveForm::begin([
        'id' => 'form-load-monitoring',
        'enableClientValidation' => false,
    ]); ?>
    <div id="fill-load-form" class="hidden" hidden="true">
    </div>
    <?= Html::hiddenInput('autofill', 1) ?>
    <?php ActiveForm::end(); ?>
</div>

<?php

$this->registerJs(
    <<< EOT_JS_CODE
$(document).ready(function(){
    $("#autofillbutton").on('click',() => {
        let realform = $("#form-data-monitoring");
        let loadformfiller = $("#fill-load-form");
        let formData = new FormData(document.getElementById("form-data-monitoring"));

        // loadformfiller.html(realform.html());

        let loadform = $("#form-load-monitoring");
        $("#fill-load-form").submit(function(e){
            for (let [key, value] of formData) {
                $("<input />").attr("type", "hidden")
                    .attr("name", key)
                    .attr("value", value)
                    .appendTo("#fill-load-form");
            }
            return true;
        });
        $("#fill-load-form").submit();
    });
});

EOT_JS_CODE

);
?>

<?php
$lat = Yii::$app->appsetting->get('default_latitude') ? Yii::$app->appsetting->get('default_latitude') : '-7.5557674322944965';
$lng = Yii::$app->appsetting->get('default_longitude') ? Yii::$app->appsetting->get('default_longitude') : '110.82480558356784';
$lat = $model->default_lat ? $model->default_lat : $lat;
$lng = $model->default_lng ? $model->default_lng : $lng;

$this->registerJs(
    <<< EOT_JS_CODE
let osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
let osmAttrib = '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMaps</a> contributors';
let osm = L.tileLayer(osmUrl, { maxZoom: 18, attribution: osmAttrib });
let map = new L.Map('map', { center: new L.LatLng($lat, $lng), zoom: 13 });

osm.addTo(map);

// add Leaflet-Geoman controls with some options to the map
let options = {
    position: 'topleft', // toolbar position, options are 'topleft', 'topright', 'bottomleft', 'bottomright'
    drawMarker: true,  // adds button to draw markers
    drawPolygon: false,  // adds button to draw a polygon
    drawPolyline: false,  // adds button to draw a polyline
    drawCircle: false,  // adds button to draw a cricle
    drawCircleMarker: false,  // adds button to draw a cricle marker
    drawText: false,  // adds button to draw a text marker
    editPolygon: false,  // adds button to toggle global edit mode
    deleteLayer: false,   // adds a button to delete layers
    drawRectangle: false,  // adds button to draw a rectangle
    editControls: false
};
map.pm.addControls(options);

map.on("pm:create",function(e) {
    map.eachLayer(function(layer){
        if(layer instanceof L.Marker){
            map.removeLayer(layer);   
        }
    });
    if(e.shape == "Marker"){
        let latlng = e.layer.getLatLng();
        let lat = latlng.lat;
        let lng = latlng.lng;
        $("#monitoringtask-default_lat").val(lat);
        $("#monitoringtask-default_lng").val(lng);
        // console.log(latlng);
        e.layer.addTo(map);
        map.pm.disableDraw();
    }
});

EOT_JS_CODE
);
?>