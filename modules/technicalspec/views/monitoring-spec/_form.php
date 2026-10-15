<?php

use kartik\depdrop\DepDrop;
use kartik\file\FileInput;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\widgets\Select2;
use newerton\fancybox3\FancyBox;
use yii\web\View;
use yii\widgets\Pjax;

if (!isset($is_create)) {
    $is_create = false;
}

/* @var $this yii\web\View */
/* @var $model app\modules\technicalspec\models\MonitoringSpec */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$js1 = '
if ($("#ded_data_id_select").val() !== ""){
    $.pjax({
        type: "POST",
        url: $(this).href,
        container: "#dedbn_data",
        data: {id: $("#ded_data_id_select").val()},
        dataType: "html"
    });
};

$("#ded_data_id_select").on("change", function(){
    if($(this).val() !== ""){
        $.pjax({
            type: "POST",
            url: $(this).href,
            container: "#dedbn_data",
            data: {id: $(this).val()},
            dataType: "html"
        });
    } else {
        $(".data-detail").hide();
    }  
});
$(document).on("pjax:send", function() {
    $(".loading").show()
    $(".data-detail").hide(500);
})
$(document).on("pjax:complete", function() {
    $(".loading").hide()
    $(".data-detail").show(800);
})
'; // saat pjax melakukan selesai dan dapat data, loading mati dan daftar receipt show

$this->registerJs($js1, View::POS_READY);
?>
<?php
echo FancyBox::widget([
    'target' => '[data-fancybox="mon"]',
]);
?>
<div class="monitoring-spec-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="hidden">
        <?= $form->field($model, 'monitoring_task_id')->widget(Select2::class, [
            'data' => [
                $model->monitoring_task_id => $model->monitoring_task_id,
            ],
            'language' => 'id',
            'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'monitoring_task_id_select'],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]); ?>
    </div>
    <div class='row'>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'ref_component_category_id')->widget(Select2::class, [
                'data' => $dropdown_data['ref_component_category_id'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'ref_component_category_id_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'ref_component_id')->widget(DepDrop::class, [
                'options' => [
                    'placeholder' => Yii::t('app', 'Choose'),
                    'id' => 'ref_component_id_select',
                ],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => false]],
                'pluginOptions' => [
                    'depends' => ['ref_component_category_id_select'],
                    'initialize' => true,
                    'url' => Url::to(['component/depdrop-by-category']),
                    'loadingText' => Yii::t('app', 'Loading...'),
                ]
            ]);
            ?>
        </div>
        <div class='col-md-12'>
            <?=
            $form->field($model, 'ded_data_id')->widget(DepDrop::class, [
                'options' => [
                    'placeholder' => Yii::t('app', 'Choose'),
                    'id' => 'ded_data_id_select',
                ],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => false]],
                'pluginOptions' => [
                    'depends' => ['ref_component_id_select', 'monitoring_task_id_select'],
                    'initialize' => true,
                    'url' => Url::to(['default/depdrop-by-component-task']),
                    'loadingText' => Yii::t('app', 'Loading...'),
                ]
            ]);
            ?>
        </div>

        <div class="row">
            <span class="loading" style="display:none;"></span>
        </div>
        <?php Pjax::begin(['id' => 'dedbn_data', 'timeout' => 1000, 'clientOptions' => ['method' => 'POST'], 'options' => ['class' => 'row']]) ?>
        <div class="col-md-12 data-detail" style="display:none">
            <?php
            if ($modelded) {
                echo $this->render('_ded', ['modelded' => $modelded]);
            }
            ?>
        </div>
        <?php Pjax::end() ?>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'id' => 'monitoringspec-name']) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <?php if ($is_create) : ?>
        <div class='row'>
            <div class='col-md-12'>
                <?=
                $form->field($model, 'file_uploads[]')->widget(FileInput::class, [
                    'options' => [
                        'accept' => implode(',', [
                            'image/webp',
                            'image/png',
                            'image/jpeg',
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-powerpoint',
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                            'video/mp4'
                        ]),
                        'multiple' => true,
                    ],
                    'pluginOptions' => [
                        'previewFileType' => 'image',
                        'showUpload' => false,
                        'resizePreference' => 'height',
                        'resizeImage' => true,
                        'resizeIfSizeMoreThan' => 1000,
                    ]
                ]);
                ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), ['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($model->monitoringProgress->monitoring_task_id)], ['class' => 'btn btn-warning']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>