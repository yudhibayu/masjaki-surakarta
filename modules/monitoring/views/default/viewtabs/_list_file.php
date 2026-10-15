<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

?>

<tr>
    <td class="text-left">
        <?php $b = '';
        if (!empty($model->file_name)) {
            $ext = pathinfo($model->file_name, PATHINFO_EXTENSION);

            if (isset($ext)) {
                if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif' || $ext == 'webp') {
                    $b = Html::img(
                        "/monitoring/default/file-item?id=" . $model->file_name,
                        $options = [
                            'style' => ['width' => '200px'],
                            'class' => 'img_gallery img_style'
                        ]
                    );
                } else {
                    $b = $model->file_name;
                }
            }
        }


        ?>
        <a href="/monitoring/default/file-item?id=<?= $model->file_name; ?>" data-lightbox="<?= $model->file_name; ?>" data-fancybox=" true" data-title="<?= $model->file_name; ?>" data-alt="<?= $model->file_name; ?>"><?php echo $b ?></a>
    </td>
    <td class="text-left">
        <?php echo nl2br(Html::encode($model->description)) ?>
    </td>
    <td class="text-left">
        <?php if ($b != '') { ?>
            <?= Html::a('<i class="bx bx-download"></i>', '/monitoring/default/file-item?id=' . $model->file_name . '', [
                'data-pjax' => 0,
                'target' => '_blank',
                'class' => 'box_button fl download_link btn btn-primary btn-xs',
                'style' => 'margin: 2px;width: 35px;',
            ]); ?>
        <?php } ?>
        <?=
        Html::a(
            '<i class="bx bx-pencil"></i>',
            [
                Url::toRoute(['/monitoring/default/update-file', 'id' => Yii::$app->hasher->encode($model->id)])
            ],
            [
                'class' => 'btn btn-xs btn-warning',
                'style' => 'margin: 2px;width: 35px;',
            ]
        );
        ?>
        <?=
        Html::a(
            '<i class="bx bx-trash"></i>',
            [
                Url::toRoute(['/monitoring/default/delete-file', 'id' => Yii::$app->hasher->encode($model->id)])
            ],
            [
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                    'params' => [], // <- extra level
                    'pjax' => 0,
                ],
                'class' => 'btn btn-xs btn-danger',
                'style' => 'margin: 2px;width: 35px;',
            ]
        );
        ?>
    </td>
</tr>