<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use dosamigos\tinymce\TinyMce;
use kartik\widgets\DatePicker;
use dosamigos\ckeditor\CKEditor;
use newerton\fancybox3\FancyBox;

// CKEd::register($this);


/* @var $this yii\web\View */
/* @var $model app\modules\referencesMasjaki\models\News */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-form">
    <?php
    echo FancyBox::widget([
        'target' => '[img_gallery]',
    ]);
    ?>
    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-md-6'>
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'date')->widget(DatePicker::classname(), [
                'language' => 'id',
                'options' => ['placeholder' => Yii::t('app', 'Choose'), 'id' => 'date'],
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
    </div>
    <!-- <div class='row'>
        <div class='col-md-6'>
            <php
            if (!empty($model->thumbnail)) {
                $ext = pathinfo($model->thumbnail, PATHINFO_EXTENSION);
                $b = '';
                if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                    $b = Html::img(
                        '@web/photo/' . $model->thumbnail,
                        [
                            'style' => 'width:200px;',
                            'class' => 'img_gallery img_style'
                        ]
                    );
                } else {
                    $b = Html::encode($model->thumbnail);
                }
                echo 'File Sebelumnya: ';
                echo '<p>' . Html::a(
                    $b,
                    '@web/photo/' . $model->thumbnail,
                    [
                        'data-lightbox' => $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' ? $model->thumbnail : null,
                        'data-fancybox' => $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' ? 'true' : null,
                        'data-title' => $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' ? $model->thumbnail : null,
                        'data-alt' => $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' ? $model->thumbnail : null
                    ]
                );
            }
            ?>
        </div>
    </div> -->
    <div class='row'>

        <div class='col-md-12'>
            <?= $form->field($model, 'thumbnail')->widget(FileInput::class, [
                'language' => 'id',
                'options' => [
                    'id' => 'thumbnail',
                    'multiple' => false,
                    'accept' => 'image/jpeg, image/jpg, image/png',
                ],
                'pluginOptions' => [
                    'resizeImages' => true,
                    'previewFileType' => 'any',
                    'showPreview' => true,
                    'showCaption' => true,
                    'showRemove' => true,
                    'showUpload' => false,
                    'resizePreference' => 'height',
                    'resizeImage' => true,
                    'resizeIfSizeMoreThan' => 250,
                    'maxFileSize' => 150,
                    'maxFileCount' => 1,
                    'allowedFileExtensions' => ['jpg', 'jpeg', 'png'],
                    'allowedPreviewExtensions' => ['jpg', 'jpeg', 'png'],
                    'initialPreview' =>
                    $model->id && $model->thumbnail ? [
                        Html::img(
                            '@web/photo/' . $model->thumbnail,
                            [
                                'style' => 'width:100%; height:100%;object-fit: contain;',
                                'class' => 'img_gallery img_style'
                            ]
                        ),
                    ] : [],

                ]
            ]);
            echo '<p class="form-text text-muted"><i><i class="fa fa-info-circle"></i> Ekstensi gambar (jpg, jpeg, png). Ukuran maksimal file 150KB.</i></p>';
            ?>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-12'>
            <?= $form->field($model, 'content')->widget(TinyMce::className(), [
                'options' => ['rows' => 6],
                'language' => 'id',
                'clientOptions' => [
                    'plugins' => [
                        'advlist',
                        'autolink',
                        'lists',
                        'link',
                        'charmap',
                        'print',
                        'preview',
                        'hr',
                        'anchor',
                        'pagebreak',
                        'searchreplace',
                        'visualblocks',
                        'visualchars',
                        'code',
                        'fullscreen',
                        'insertdatetime',
                        'nonbreaking',
                        'save',
                        'table',
                        'contextmenu',
                        'directionality',
                        'template',
                        'paste',
                        'textcolor',
                        'image',
                        'link'
                    ],
                    'toolbar' => "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media | forecolor backcolor",
                    'toolbar_items_size' => 'small',
                    'image_advtab' => true,
                    'relative_urls' => false,
                    'height' => 400,
                    'image_title' => true,
                    'automatic_uploads' => true,
                    'file_picker_types' => 'image',
                    'file_picker_callback' => new JsExpression("
            function(cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                
                // Note: In modern browsers input[type=\"file\"] is functional without
                // even adding it to the DOM, but that might not be the case in some older
                // or quirky browsers like IE, so you might want to add it to the DOM
                // just in case, and visually hide it. And do not forget to remove it
                // once you do not need it anymore.
                input.onchange = function() {
                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.onload = function() {
                        // Note: Now we need to register the blob in TinyMCE's image blob
                        // registry. In the next release this part hopefully won't be
                        // necessary, as we are looking to handle it internally.
                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);
                        
                        // call the callback and populate the Title field with the file name
                        cb(blobInfo.blobUri(), { title: file.name });
                    };
                    reader.readAsDataURL(file);
                };
                input.click();
            }
        "),
                ],
            ]); ?>

        </div>
    </div>

    <!-- <div class='row'>
        <div class='col-md-6'>
            <=$form->field($model, 'deleted_at')->textInput()?>
        </div>
    </div>
        
    <div class='row'>
        <div class='col-md-6'>
            <=$form->field($model, 'deleted_by')->textInput()?>
        </div>
    </div> -->

    <div class="text-right">
        <?= Html::a(Yii::t('app', 'Cancel'), [$url_prev], ['class' => 'btn btn-outline-secondary']) ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>