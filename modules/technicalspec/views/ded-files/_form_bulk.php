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
            <div class="col-md-12">
                <?= $form->field($model, 'description')->textarea(['rows' => 4]);  ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), ['default/view', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?>
        <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>