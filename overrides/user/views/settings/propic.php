<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */


use kartik\widgets\FileInput;
use yii\bootstrap5\Html;
use yii\web\View;
use yii\bootstrap5\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\Profile $model
 */

$this->title = Yii::t('user', 'Profile Picture');
//$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile("@web/js/pool/piexif.min.js");

$js1 = "
$('.profile-picture').on('fileuploaded', function(event, previewId, index, fileId) {
    //console.log('File uploaded', previewId, index, fileId);
    //alert('Selesai Upload');
    location.reload(); 
});
";
$this->registerJs($js1, View::POS_READY);
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>
<!-- <div class="block-header">
    <h2><= strtoupper($this->title); ?></h2>
</div> -->
<div class="row">
    <div class=" col-md-3">
        <?= $this->render('_menu', ['suffix' => $suffix]) ?>
    </div>
    <div class="col-md-9">
        <section class="card">
            <header class="card-header">
                <div class="card-actions">
                    <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                </div>
                <h4> <?= Html::encode($this->title) ?></h4>
            </header>
            <div class="card-body">

                <?php $form = ActiveForm::begin([
                    'id' => 'profile-form',
                    'options' => ['class' => 'form-horizontal'],
                    // 'fieldConfig' => [
                    //     'template' => "{label}\n<div class=\"col-lg-9 form-line\">{input}</div>\n<div class=\"col-sm-offset-3 col-lg-9\">{error}\n{hint}</div>",
                    //     'labelOptions' => ['class' => 'col-lg-3 control-label'],
                    // ],
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                    'validateOnBlur' => false,
                ]); ?>
                <?=
                $form->field($model, 'file_name')->widget(FileInput::classname(), [
                    'resizeImages' => true,
                    'language' => 'id',
                    'options' => [
                        'id' => 'profile-picture',
                        'class' => 'profile-picture',
                        'multiple' => false,
                        'accept' => 'image/jpeg, image/png, image/jpg',
                        //'disabled' => $the_disable
                    ],
                    'pluginOptions' => [
                        'language' => 'id',
                        'uploadUrl' => '/user/settings/profile-picture',
                        'uploadExtraData' => [
                            //'user_id'            => $model->user_id,
                        ],
                        'previewFileType' => 'image',
                        'maxFileCount' => 1,
                        'showUploadedThumbs' => true,
                        'resizeIfSizeMoreThan' => 250,

                        'resizePreference' => 'width',
                        'maxImageWidth' => 500,
                        'maxImageHeight' => 500,
                    ]
                ])->label(false);
                ?>

                <?php ActiveForm::end(); ?>

            </div>
        </section>
        <!-- <div class="panel panel-default">
            <div class="panel-heading">

            </div>
            <div class="panel-body">

            </div>
        </div> -->
    </div>
</div>