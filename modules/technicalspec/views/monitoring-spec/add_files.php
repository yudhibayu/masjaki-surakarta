<?php

use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\technicalspec\models\MonitoringSpec */

$this->title = Yii::t('technicalspec', 'Add Files for {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('technicalspec', 'Monitoring Specifications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => Yii::$app->hasher->encode($model->id)]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="monitoring-spec-update">
            <br>
            <?php $form = ActiveForm::begin(); ?>
            <div class='row'>
                <div class='col-md-12'>
                    <?=
                    $form->field($model, 'file_uploads[]')->widget(FileInput::classname(), [
                        'language' => 'id',
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
                <div class="col-md-12">
                    <?= $form->field($model, 'description')->textarea(['rows' => 4]); ?>
                </div>
            </div>
            <div class="text-right">
                <?= Html::a(Yii::t('app', 'Cancel'), ['/technicalspec/monitoring-spec/view', 'id' => Yii::$app->hasher->encode($model->id)], ['class' => 'btn btn-warning']) ?>
                <?= Html::submitButton(Yii::t('app', 'Add'), ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</section>