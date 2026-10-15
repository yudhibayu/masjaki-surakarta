<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\widgets\DatePicker;
use kartik\number\NumberControl;
use kartik\widgets\Select2;


/* @var $this yii\web\View */
/* @var $model app\modules\rup\models\Pbj */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pbj-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'kodelelang')->textInput() ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'name')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'budget')->widget(NumberControl::classname(), [
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
            ]);
            ?>
        </div>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'hps')->widget(NumberControl::classname(), [
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
            ]);
            ?>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'bid_price')->widget(NumberControl::classname(), [
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
            ]);
            ?>
        </div>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'final_price')->widget(NumberControl::classname(), [
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
            ]);
            ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'pbj_type')->dropDownList(['0' => 'Tender', '1' => 'Non Tender',], ['prompt' => Yii::t('app', 'Choose')]) ?>
        </div>
        <!-- <div class='col-md-6'>
            <= $form->field($model, 'rup_code')->textInput(['maxlength' => true]) ?>
        </div> -->
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'sppbj_no')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'vendor_name')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'contract_no')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'contract_date')->widget(DatePicker::classname(), [
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'contract_date'],
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
            <?= $form->field($model, 'contract_duration')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'contract_price')->widget(NumberControl::classname(), [
                'maskedInputOptions' => [
                    'removeMaskOnSubmit' => true,
                    //'prefix' => 'Rp. ',
                    'digits' => 4,
                    'groupSeparator' => '.',
                    'radixPoint' => ','
                ],
                'options' => [
                    'class' => 'contract_price',
                ],
            ]);
            ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'contract_start_date')->widget(DatePicker::classname(), [
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'contract_start_date'],
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
            $form->field($model, 'contract_end_date')->widget(DatePicker::classname(), [
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'contract_end_date'],
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
            $form->field($model, 'work_start_date')->widget(DatePicker::classname(), [
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'work_start_date'],
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
            $form->field($model, 'work_finished_date')->widget(DatePicker::classname(), [
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'work_finished_date'],
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
        <div class='col-md-4'>
            <?= $form->field($model, 'program')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-md-4'>
            <?= $form->field($model, 'activity')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-md-4'>
            <?= $form->field($model, 'sub_activity')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-12'>
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
    </div>
    <div class="row">
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
        <div class='col-md-6'>
            <!-- <=
            $form->field($model, 'idjenispengadaan')->widget(Select2::class, [
                'data' => $dropdown_data['jenispengadaan'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'idjenispengadaan_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?> -->
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'ppk_name')->textInput() ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'ppk_nip')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?=
            $form->field($model, 'source_of_funds')->widget(Select2::class, [
                'data' => $dropdown_data['source_of_funds'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'idsource_of_funds_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
    </div>


    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-warning']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>