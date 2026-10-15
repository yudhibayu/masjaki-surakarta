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
    //location.reload(); 
});
";
$this->registerJs($js1, View::POS_READY);
?>

<?php $this->beginContent('@app/overrides/user/views/admin/update.php', ['user' => $user]) ?>

<div class="row">

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= Html::encode($this->title) ?>
            </div>
            <div class="panel-body">
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
                            'language' => 'id', 
                            'resizeImages' => true,
                            'options' => [
                                'id' => 'profile-picture',
                                'class' => 'profile-picture',
                                'multiple' => false,
                                'accept' => 'image/jpeg, image/png, image/jpg',
                                //'disabled' => $the_disable
                            ],
                            'pluginOptions' => [
                                'language' => 'id',
                                'uploadUrl' => '/user/admin/profile-picture?id='.$model->user_id,
                                'uploadExtraData' => [
                                    //'id' => $model->user_id,
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
        </div>
    </div>
</div>
<?php $this->endContent() ?>