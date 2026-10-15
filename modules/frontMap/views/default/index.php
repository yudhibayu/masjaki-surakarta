<?php

use yii\helpers\Html;
use app\assets\FrontMapAsset;
use miloschuman\highcharts\Highcharts;
use newerton\fancybox3\FancyBox;
use yii\bootstrap5\ActiveForm;

FrontMapAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\monitoring\models\MonitoringTaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Map');

echo FancyBox::widget([
    'target' => '[data-fancybox="monitoring"]',
]);

$this->registerJs("

$(document).ready(function(){
    $('.show-task-popup').on('click', function () {
        let taskid = $(this).attr('data-taskid');
        $.ajax({
            url:'/front-map/default/task-data',
            type: 'POST',
            data:'taskid='+taskid,
            success:function(data){
                let modal = $('#target-modal-body');
                modal.html(data);
            }
        });
    });
});
");

?>
<script>
</script>
<div id="highchartdump" class="hidden" hidden>
    <?= Highcharts::widget() ?>
</div>
<div id="map" class="sidebar-map"></div>

<div id="sidebar" class="sidebar collapsed">
    <!-- Nav tabs -->
    <div class="sidebar-tabs">
        <ul role="tablist">
            <li><a href="#year" role="tab"><i class="fa fa-calendar"></i></a></li>
            <li><a href="#typeobject" role="tab"><i class="fa fa-building"></i></a></li>
            <li><a href="#kecamatan" role="tab"><i class="fa fa-map-signs"></i></a></li>
            <li><a href="#progress" role="tab"><i class="fa fa-chart-line"></i></a></li>
            <li><a href="#sumberdana" role="tab"><i class="fa fa-money-bill"></i></a></li>
            <?= !Yii::$app->user->isGuest ? '<li><a href="#admin-filter" role="tab"><i class="fa fa-user-group"></i></a></li>' : '' ?>
        </ul>

        <ul role="tablist">
            <li><a href="#settings" role="tab"><i class="fa fa-gear"></i></a></li>
        </ul>
    </div>

    <!-- Tab panes -->
    <div class="sidebar-content">
        <div class="sidebar-pane" id="typeobject">
            <h1 class="sidebar-header">
                Filter Objek di Peta
                <span class="sidebar-close"><i class="fa fa-caret-right"></i></span>
            </h1>
            <br>
            <?php $form = ActiveForm::begin(['id' => 'filter-form']); ?>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'object_type_id')->checkboxList($dropdown_data['object_type'], ['class' => 'map-filter-data'])->label(false) ?>
                </div>
            </div>
        </div>

        <div class="sidebar-pane" id="progress">
            <h1 class="sidebar-header">
                Status Pelaksanaan <span class="sidebar-close"><i class="fa fa-caret-right"></i></span>
            </h1>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'implementation_status')->checkboxList($dropdown_data['progress'], ['class' => 'map-filter-data'])->label(false) ?>
                </div>
            </div>
        </div>

        <div class="sidebar-pane" id="kecamatan">
            <h1 class="sidebar-header">
                Kecamatan<span class="sidebar-close"><i class="fa fa-caret-right"></i></span>
            </h1>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'kecamatan_id')->checkboxList($dropdown_data['kecamatan_id'], ['class' => 'map-filter-data'])->label(false) ?>
                </div>
            </div>
        </div>

        <div class="sidebar-pane" id="year">
            <h1 class="sidebar-header">
                Tahun Anggaran<span class="sidebar-close"><i class="fa fa-caret-right"></i></span>
            </h1>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'fiscal_year')->checkboxList($dropdown_data['fiscal_year'], ['class' => 'map-filter-data'])->label(false) ?>
                </div>
            </div>
        </div>

        <div class="sidebar-pane" id="sumberdana">
            <h1 class="sidebar-header">
                Sumber Dana<span class="sidebar-close"><i class="fa fa-caret-right"></i></span>
            </h1>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'sumberdana')->checkboxList($dropdown_data['sumberdana'], ['class' => 'map-filter-data'])->label(false) ?>
                </div>
            </div>
        </div>

        <?php if (!Yii::$app->user->isGuest) : ?>
            <div class="sidebar-pane" id="admin-filter">
                <h1 class="sidebar-header">
                    Filter <span class="sidebar-close"><i class="fa fa-caret-right"></i></span>
                </h1>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'team_id')->checkboxList($dropdown_data['teams'], ['class' => 'map-filter-data']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'is_bast_done')->checkboxList($dropdown_data['is_bast_done'], ['class' => 'map-filter-data']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'is_active')->checkboxList($dropdown_data['is_active'], ['class' => 'map-filter-data']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'latest_monitored_physical_percent')->checkboxList($dropdown_data['percent_progress'], ['class' => 'map-filter-data']) ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="sidebar-pane" id="settings">
            <h1 class="sidebar-header">
                Pengaturan<span class="sidebar-close"><i class="fa fa-caret-right"></i></span>
            </h1>
            <div class="row">
                <div class="col-md-6">
                    <br>
                    <button id="osm-map" type="button" class="btn btn-primary">Open Street Map</button>
                </div>
                <div class="col-md-6">
                    <br>
                    <button id="gst-map" type="button" class="btn btn-primary">Google Street Map</button>
                </div>
                <!-- <div class="col-md-6">
                        <br>
                        <button id="gsat-map" type="button" class="btn btn-primary">Google Satelite</button>
                    </div>
                    <div class="col-md-6">
                        <br>
                        <button id="gtr-map" type="button" class="btn btn-primary">Google Terrain</button>
                    </div> -->
            </div>
        </div>
        <?= Html::button(Yii::t('app', 'Update'), ['class' => 'btn btn-success hidden', 'id' => 'submit-filter-data', 'type' => 'button', 'hidden' => true]) ?>
        <?php ActiveForm::end(); ?>
    </div>
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


    <!-- Modal -->
    <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body" id="target-modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>

    <div class="hidden" hidden="true">
        <?php $form = ActiveForm::begin(['id' => 'task-form', 'action' => '/front-map/default/task-data']); ?>
        <?php ActiveForm::end(); ?>
    </div>