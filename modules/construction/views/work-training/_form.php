<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\widgets\TimePicker;


/* @var $this yii\web\View */
/* @var $model app\modules\construction\models\WorkTraining */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
.form-control.separator {
    background-color: #fff;
    text-align: center;
    width: fit-content;
}

.field-worktraining-kuota {
    width: 75%;
}

.field-implementation_date {
    width: 30%;
}

.field-finish_date {
    width: 30%;
}

.field-implementation_time {
    width: 30%;
}

.field-finish_time {
    width: 30%;
}
</style>
<div class="work-training-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'training_name')->textInput(['maxlength' => true, 'placeholder' => 'Pelatihan Tenaga Kerja Konstruksi dengan Mobile']) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'person_responsible_name')->textInput(['maxlength' => true, 'placeholder' => 'Muhammad Budi Santoso']) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'skills')->widget(Select2::class, [
                'data' => $dropdown_data['skills'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose Skills'), 'id' => 'skills_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="age_range">Kuota</label>
                <div class="input-group">
                    <?= $form->field($model, 'kuota')->textInput(['placeholder' => '5'])->label(false) ?>
                    <span class="form-control separator">Orang</span>
                </div>
            </div>
        </div>
        <!-- <div class='col-md-6'>
            <= $form->field($model, 'kuota')->textInput(['maxlength' => true]) ?>

        </div> -->
    </div>
    <div class="row">

        <div class='col-md-6'>
            <div class="form-group">
                <label for="age_range">Tanggal</label>
                <div class="input-group">
                    <?= $form->field($model, 'implementation_date')->widget(DatePicker::classname(), [
                        'language' => 'id',
                        'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'implementation_date'],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'todayHighlight' => true,
                            'format' => 'dd-mm-yyyy',
                            //'maxViewMode' => 'month',
                            //'minViewMode' => 'days',

                        ]
                    ])->label(false);
                    ?> <span class="form-control separator">Sampai Dengan</span>
                    <?= $form->field($model, 'finish_date')->widget(DatePicker::classname(), [
                        'language' => 'id',
                        'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'finish_date'],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'todayHighlight' => true,
                            'format' => 'dd-mm-yyyy',
                            //'maxViewMode' => 'month',
                            //'minViewMode' => 'days',

                        ]
                    ])->label(false);
                    ?> </div>
            </div>
        </div>
        <div class='col-md-6'>
            <div class="form-group">
                <label for="age_range">Waktu</label>
                <div class="input-group">
                    <?= $form->field($model, 'implementation_time')->widget(TimePicker::classname(), [
                        'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'implementation_time'],
                        'pluginOptions' => [
                            'showMeridian' => false,
                            'showSeconds' => false,
                            'minuteStep' => 5,
                            'defaultTime' => 'current',
                        ]
                    ])->label(false);
                    ?> <span class="form-control separator">Sampai Dengan</span>
                    <?= $form->field($model, 'finish_time')->widget(TimePicker::classname(), [
                        'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'finish_time'],
                        'pluginOptions' => [
                            'showMeridian' => false,
                            'showSeconds' => false,
                            'minuteStep' => 5,
                            'defaultTime' => 'current',
                        ]
                    ])->label(false);
                    ?></div>
            </div>
        </div>
    </div>
    <!-- <div class='row'>
        <div class='col-md-6'>

            <=
            $form->field($model, 'implementation_date')->widget(DatePicker::classname(), [
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'implementation_date'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'todayHighlight' => true,
                    'format' => 'dd-mm-yyyy',
                    //'maxViewMode' => 'month',
                    //'minViewMode' => 'days',

                ]
            ]);
            ?>
        </div>
        <div class='col-md-6'>
            <= $form->field($model, 'finish_date')->widget(DatePicker::classname(), [
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'finish_date'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'todayHighlight' => true,
                    'format' => 'dd-mm-yyyy',
                    //'maxViewMode' => 'month',
                    //'minViewMode' => 'days',

                ]
            ]);
            ?>
        </div>
    </div> -->

    <!-- <div class='row'>
        <div class='col-md-6'>
            <=
            $form->field($model, 'implementation_time')->widget(TimePicker::classname(), [
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'implementation_time'],
                'pluginOptions' => [
                    'showMeridian' => false,
                    'showSeconds' => false,
                    'minuteStep' => 5,
                    'defaultTime' => 'current',
                ]
            ]);
            ?>
        </div>
        <div class='col-md-6'>
            <= $form->field($model, 'finish_time')->widget(TimePicker::classname(), [
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'finish_time'],
                'pluginOptions' => [
                    'showMeridian' => false,
                    'showSeconds' => false,
                    'minuteStep' => 5,
                    'defaultTime' => 'current',
                ]
            ]);
            ?>
        </div>

    </div> -->

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'id_country')->widget(Select2::class, [
                'data' => $dropdown_data['ref_negara'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Pilih Negara'), 'id' => 'country_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?>
        </div>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'id_province')->widget(DepDrop::class, [
                'options' => [
                    'placeholder' => Yii::t('app', 'Choose'),
                    'id' => 'id_province_select',
                ],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => false]],
                'pluginOptions' => [
                    'depends' => ['country_select'],
                    'initialize' => true,
                    'url' => Url::to(['/references-masjaki/ref-provinsi/depdrop-by-negara']),
                    'loadingText' => Yii::t('app', 'Loading...'),
                ]
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class='col-md-6'>
            <?=
            $form->field($model, 'id_regency')->widget(DepDrop::class, [
                'options' => [
                    'placeholder' => Yii::t('app', 'Choose'),
                    'id' => 'id_regency_select',
                ],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => false]],
                'pluginOptions' => [
                    'depends' => ['country_select', 'id_province_select'],
                    'initialize' => true,
                    'url' => Url::to(['/references-masjaki/ref-kabkota/depdrop-by-provinsi']),
                    'loadingText' => Yii::t('app', 'Loading...'),
                ]
            ]);
            ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'id_subdistrict')->widget(DepDrop::class, [
                'options' => [
                    'placeholder' => Yii::t('app', 'Choose'),
                    'id' => 'id_subdistrict_select',
                ],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => false]],
                'pluginOptions' => [
                    'depends' => ['country_select', 'id_province_select', 'id_regency_select'],
                    'initialize' => true,
                    'url' => Url::to(['/references-masjaki/ref-kecamatan/depdrop-by-kabkota']),
                    'loadingText' => Yii::t('app', 'Loading...'),
                ]
            ]);
            ?>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'id_ward')->widget(DepDrop::class, [
                'options' => [
                    'placeholder' => Yii::t('app', 'Choose'),
                    'id' => 'id_ward_select',
                ],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => false]],
                'pluginOptions' => [
                    'depends' => ['country_select', 'id_province_select', 'id_regency_select', 'id_subdistrict_select'],
                    'initialize' => true,
                    'url' => Url::to(['/references-masjaki/ref-kelurahan/depdrop-by-kecamatan']),
                    'loadingText' => Yii::t('app', 'Loading...'),
                ]
            ]);
            ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'description')->textarea(['maxlength' => true, 'placeholder' => 'Untuk meningkatkan pengetahuan tenaga kerja konstruksi melalui platform mobile yang mudah diakses']) ?>

        </div>
    </div>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>