<?php

use kartik\widgets\DepDrop;
use kartik\widgets\FileInput;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\widgets\Select2;


/* @var $this yii\web\View */
/* @var $model app\modules\technicalspec\models\DedData */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ded-data-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-12'>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'mco_cco_id')->widget(Select2::classname(), [
                'data' => $dropdown_data['mco_cco_id'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'mco_cco_id_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
        <div class='col-md-6'>
            <?=
            $form->field($model, 'ref_component_category_id')->widget(Select2::classname(), [
                'data' => $dropdown_data['ref_component_category_id'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'ref_component_category_id_select'],
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
            $form->field($model, 'ref_component_id')->widget(DepDrop::classname(), [
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
        <div class='col-md-3'>
            <?= $form->field($model, 'quantity')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-md-3'>
            <?=
            $form->field($model, 'ref_satuan_id')->widget(Select2::classname(), [
                'data' => $dropdown_data['ref_satuan_id'],
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'ref_satuan_id_select'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'specification')->textarea(['rows' => 4]) ?>
        </div>
        <div class='col-md-6'>
            <?= $form->field($model, 'notes')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <?php if ($is_create) : ?>
        <div class='row'>
            <div class='col-md-12'>
                <?=
                $form->field($model, 'file_uploads[]')->widget(FileInput::classname(), [
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
        <?= Html::a(Yii::t('app', 'Cancel'), ['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)], ['class' => 'btn btn-warning']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>