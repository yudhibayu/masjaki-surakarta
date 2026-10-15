<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\widgets\Select2;
use kartik\widgets\FileInput;


/* @var $this yii\web\View */
/* @var $model app\modules\technicalspec\models\MonitoringSpecFiles */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="monitoring-spec-files-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-12'>
            <?=$form->field($model, 'name')->textInput(['maxlength' => true])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-12'>
            <?=$form->field($model, 'description')->textarea(['rows' => 4])?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-12'>
            <?=
            $form->field($model, 'file_upload')->widget(FileInput::classname(), [
                'options' => [
                    'accept' => 'image/jpeg, image/png, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                ],
                'pluginOptions' => [
                    'previewFileType' => 'image',
                    'showUpload' => false,
                    'resizePreference'=> 'height',
                    'resizeImage'=> true,
                    'resizeIfSizeMoreThan'=> 1000,
                ]
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
