<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JsExpression;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\widgets\TimePicker;
use kartik\number\NumberControl;


/* @var $this yii\web\View */
/* @var $model app\modules\construction\models\ConstructionVacancies */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
/* Optional: Responsive adjustment */

.form-control.separator {
    background-color: #fff;
    text-align: center;
    width: fit-content;
}

/* 
    .field-constructionvacancies-quota {
        width: 75%;
    }

    .field-constructionvacancies-work_experience {
        width: 75%;
    } */

.field-constructionvacancies-age_range {
    width: 30%;
}

.field-constructionvacancies-age_range_upto {
    width: 30%;
}

.field-start {
    width: 30%;
}

.field-upto {
    width: 30%;
}

.field-start_date {
    width: 30%;
}

.field-date_of_completion {
    width: 30%;
}

.field-start_time {
    width: 30%;
}

.field-end_time {
    width: 30%;
}
</style>
<div class="construction-vacancies-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Tenaga Pengawas Lapangan Proyek Konstruksi Pasar']) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'description')->textInput(['maxlength' => true, 'placeholder' => 'Menjadi mandor untuk proyek konstruksi Pasar Jongke Laweyan']) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'construction_work')->widget(Select2::class, [
                'data' => $dropdown_data['construction_work'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose Construction Work'), 'id' => 'construction_work_select', 'onChange' => 'detail()'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?>
            <div class="form-group" id="construction_work_details" style="display: none;">
                <label class="control-label">Details:</label>
                <p id="company_name"></p>
                <p id="source_of_funds"></p>
                <p id="winner"></p>
            </div>
        </div>
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
    </div>
    <!-- buatkan saat sudah memilih, bawahnya akan ada detailnya dari tabel construction_work field = business entity dari tabel business_entity ambil field company_name, source_of_funds dari table source_of_funds ambil field name, dan winner dari table labor ambil field name -->

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'education')->widget(Select2::class, [
                'data' => $dropdown_data['education'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose Education'), 'id' => 'education_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?> </div>

        <div class="col-md-6">
            <?= $form->field($model, 'type_of_work')->widget(Select2::class, [
                'data' => [
                    0 => 'Part Time',
                    1 => 'Full Time',
                ],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Pilih Tipe Pekerjaan'), 'id' => 'type_of_work_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]); ?>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-6'>
            <div class="form-group">
                <label for="age_range">Rentang Umur</label>
                <div class="input-group">
                    <?= $form->field($model, 'age_range')->textInput(['placeholder' => 'Dari'])->label(false) ?>
                    <span class="form-control separator">Sampai Dengan</span>
                    <?= $form->field($model, 'age_range_upto')->textInput(['placeholder' => 'Hingga'])->label(false) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="age_range">Kisaran Gaji</label>
                <div class="input-group">
                    <?= $form->field($model, 'salary_range')->widget(NumberControl::classname(), [
                        'maskedInputOptions' => [
                            'removeMaskOnSubmit' => true,
                            'prefix' => 'Rp. ',
                            'digits' => 2,
                            'groupSeparator' => '.',
                            'radixPoint' => ','
                        ],
                        'options' => [
                            'id' => 'start',
                        ],
                    ])->label(false); ?>
                    <span class="form-control separator">Sampai Dengan</span>
                    <?= $form->field($model, 'salary_range_upto')->widget(NumberControl::classname(), [
                        'maskedInputOptions' => [
                            'removeMaskOnSubmit' => true,
                            'prefix' => 'Rp. ',
                            'digits' => 2,
                            'groupSeparator' => '.',
                            'radixPoint' => ',',
                        ],
                        'options' => [
                            'id' => 'upto',
                        ],
                    ])->label(false) ?>
                </div>
            </div>
        </div>
    </div>
    <div class='row'>
        <div class="col-md-6">
            <?= $form->field(
                $model,
                'work_experience',
                [
                    'template' => '{label}<div class="input-group">{input} <span style="padding: 12px; background-color: #ebebeb; border-top-right-radius: 3px; border-bottom-right-radius: 3px; border-top: 1px solid #ced4da; border-right: 1px solid #ced4da; border-bottom: 1px solid #ced4da" class="input-group-addon">Tahun</span></div>{error}{hint}',
                    'inputOptions' => ['class' => 'form-control']
                ]
            )->textInput(['placeholder' => '5']) ?>

        </div>
        <div class="col-md-6">
            <?= $form->field(
                $model,
                'quota',
                [
                    'template' => '{label}<div class="input-group">{input} <span style="padding: 12px; background-color: #ebebeb; border-top-right-radius: 3px; border-bottom-right-radius: 3px; border-top: 1px solid #ced4da; border-right: 1px solid #ced4da; border-bottom: 1px solid #ced4da" class="input-group-addon">Orang</span></div>{error}{hint}',
                    'inputOptions' => ['class' => 'form-control']
                ]
            )->textInput(['placeholder' => '15']) ?>

        </div>
    </div>


    <div class='row'>
        <div class='col-md-6'>
            <div class="form-group">
                <label for="age_range">Tanggal</label>
                <div class="input-group">
                    <?= $form->field($model, 'start_date')->widget(DatePicker::classname(), [
                        'language' => 'id',
                        'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'start_date'],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'todayHighlight' => true,
                            'format' => 'dd-mm-yyyy',
                            //'maxViewMode' => 'month',
                            //'minViewMode' => 'days',

                        ]
                    ])->label(false);
                    ?> <span class="form-control separator">Sampai Dengan</span>
                    <?= $form->field($model, 'date_of_completion')->widget(DatePicker::classname(), [
                        'language' => 'id',
                        'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'date_of_completion'],
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
        <div class="col-md-6">
            <div class="form-group">
                <label for="age_range">Waktu</label>
                <div class="input-group">
                    <?=
                    $form->field($model, 'start_time')->widget(TimePicker::classname(), [
                        'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'start_time'],
                        'pluginOptions' => [
                            'showMeridian' => false,
                            'showSeconds' => false,
                            'minuteStep' => 5,
                            'defaultTime' => 'current',
                        ]
                    ])->label(false);
                    ?> <span class="form-control separator">Sampai Dengan</span>
                    <?= $form->field($model, 'end_time')->widget(TimePicker::classname(), [
                        'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'end_time'],
                        'pluginOptions' => [
                            'showMeridian' => false,
                            'showSeconds' => false,
                            'minuteStep' => 5,
                            'defaultTime' => 'current',
                        ]
                    ])->label(false);
                    ?> </div>
            </div>
        </div>
    </div>
    <?php if (Yii::$app->user->can('Administrator')) : ?>
    <div class='row'>
        <div class='col-md-6'>

            <?=
                $form->field($model, 'id_business_entity')->widget(DepDrop::class, [
                    'options' => [
                        'placeholder' => Yii::t('app', 'Choose'),
                        'id' => 'id_business_entity_select',
                    ],
                    'type' => DepDrop::TYPE_SELECT2,
                    'select2Options' => ['pluginOptions' => ['allowClear' => false]],
                    'pluginOptions' => [
                        'depends' => ['construction_work_select'],
                        'initialize' => true,
                        'url' => Url::to(['/construction/pbj-business-entity/depdrop-by-pbj']),
                        'loadingText' => Yii::t('app', 'Loading...'),
                    ]
                ]);
                ?>
            <!-- <= $form->field($model, 'id_business_entity')->widget(Select2::class, [
                    'data' => $dropdown_data['id_business_entity'],
                    'language' => 'id',
                    'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'id_business_entity_select'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]); ?> -->
        </div>

    </div>
    <?php endif; ?>
    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
function detail() {
    var dropdown = document.getElementById('construction_work_select');

    // Mendapatkan nilai terpilih
    var constructionWorkId = dropdown.value;

    if (constructionWorkId) {
        $.ajax({

            url: '<?= Url::to(['construction-vacancies/construction-work-details']) ?>',
            type: 'GET',
            dataType: 'json',
            data: {
                id: constructionWorkId
            },
            success: function(data) {
                if (data.company_name && data.source_of_funds && data.winner) {
                    $('#company_name').text('Company: ' + data.company_name);
                    $('#source_of_funds').text('Source of Funds: ' + data
                        .source_of_funds);
                    $('#winner').text('Winner: ' + data.winner);
                    $('#construction_work_details').show();
                } else {
                    $('#construction_work_details').hide();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching construction work details:', error);
            }
        });
    } else {
        $('#construction_work_details').hide();
    }
}
</script>