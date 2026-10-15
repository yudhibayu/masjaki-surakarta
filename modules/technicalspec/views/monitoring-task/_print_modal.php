<?php

use kartik\widgets\DatePicker;
use yii\helpers\Html;

?>
<!-- BAST Modal -->
<div class="modal fade" id="bastModal<?=Yii::$app->hasher->encode($model->id)?>" tabindex="-1" aria-labelledby="bastModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <?= Html::beginForm(['generate-bast', 'id' => Yii::$app->hasher->encode($model->id)], 'post', ['enctype' => 'multipart/form-data']) ?>
            <div class="modal-header">
                <h5 class="modal-title" id="bastModalLabel"><?= Yii::t('monitoring', 'Generate BAST').' : '.$model->name ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= Html::hiddenInput('id', Yii::$app->hasher->encode($model->id)) ?>
                <?php
                echo '<label class="form-label">' . Yii::t('app', 'Date') . '</label>';
                echo DatePicker::widget([
                    'name' => 'bastdate',
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'value' => date('Y-m-d'),
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                    ]
                ]);
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= Yii::t('app', 'Close') ?></button>
                <?= Html::submitButton(Yii::t('app', 'Ok'), ['class' => 'submit btn btn-primary']) ?>
            </div>
            <?= Html::endForm() ?>
        </div>
    </div>
</div>

<!-- Report Modal -->
<div class="modal fade" id="reportModal<?=Yii::$app->hasher->encode($model->id)?>" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <?= Html::beginForm(['report-monitoring', 'id' => Yii::$app->hasher->encode($model->id)], 'post', ['enctype' => 'multipart/form-data', 'target'=>'_blank']) ?>
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel"><?= Yii::t('monitoring', 'Generate Monitoring Result').' : '.$model->name ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= Html::hiddenInput('id', Yii::$app->hasher->encode($model->id)) ?>
                <?= '<label class="form-label">' . Yii::t('app', 'Month') . '</label>'; ?>
                <?= Html::dropDownList(
                    'month',
                    13,
                    [
                        'Tanpa Bulan',
                        'Januari',
                        'Februari',
                        'Maret',
                        'April',
                        'Mei',
                        'Juni',
                        'Juli',
                        'Agustus',
                        'September',
                        'Oktober',
                        'November',
                        'Desember',
                        'Semua Bulan',
                    ],
                    [
                        'class' => 'form-control',
                    ]
                ) ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= Yii::t('app', 'Close') ?></button>
                <?= Html::submitButton(Yii::t('app', 'Ok'), ['class' => 'submit btn btn-primary']) ?>
            </div>
            <?= Html::endForm() ?>
        </div>
    </div>
</div>