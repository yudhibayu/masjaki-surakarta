<?php

use kartik\widgets\DatePicker;
use yii\helpers\Html;

?>
<!-- BAST Modal -->
<div class="modal fade" id="bastModal<?= Yii::$app->hasher->encode($model->id) ?>" tabindex="-1" aria-labelledby="bastModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <?= Html::beginForm(['generate-bast', 'id' => Yii::$app->hasher->encode($model->id)], 'post', ['enctype' => 'multipart/form-data']) ?>
            <div class="modal-header">
                <h5 class="modal-title" id="bastModalLabel"><?= Yii::t('monitoring', 'Generate BAST') . ' : ' . $model->name ?></h5>
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
<div class="modal fade" id="reportModal<?= Yii::$app->hasher->encode($model->id) ?>" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <?= Html::beginForm(['report-monitoring', 'id' => Yii::$app->hasher->encode($model->id)], 'post', ['enctype' => 'multipart/form-data', 'target' => '_blank']) ?>
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel"><?= Yii::t('monitoring', 'Generate Monitoring Result') . ' : ' . $model->name ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= Html::hiddenInput('id', Yii::$app->hasher->encode($model->id)) ?>
                <div class="selectReportType" id="selectReportType<?= Yii::$app->hasher->encode($model->id) ?>">
                    <label>Tipe Laporan</label>
                    <?= Html::radioList('is_spec', 0, [0 => 'Monitoring Lapangan', 1 => 'Monitoring Spesifikasi Teknis'], ['separator' => '<br>']); ?>
                </div>
                <br>
                <div class="selectDayReport" id="selectDayReport<?= Yii::$app->hasher->encode($model->id) ?>">
                    <label>
                        <?= Html::radio('type', true, [
                            'value' => 'day',
                            'class' => 'radioswitch',
                            'data-id' => Yii::$app->hasher->encode($model->id),
                        ]) ?> <?= Yii::t('app', 'Day') ?>
                    </label>
                    <?= Html::dropDownList(
                        'day',
                        null,
                        $model->dayMonitoringAvaiableArrayKey,
                        [
                            'class' => 'form-control',
                        ]
                    ) ?>
                </div>
                <br>
                <div class="selectWeekReport" id="selectWeekReport<?= Yii::$app->hasher->encode($model->id) ?>">
                    <label>
                        <?= Html::radio('type', false, [
                            'value' => 'week',
                            'class' => 'radioswitch',
                            'data-id' => Yii::$app->hasher->encode($model->id),
                        ]) ?> <?= Yii::t('app', 'Week') ?>
                    </label>
                    <?= Html::dropDownList(
                        'week',
                        null,
                        $model->orderMonitoringAvaiableArrayKey,
                        [
                            'class' => 'form-control',
                        ]
                    ) ?>
                </div>
                <br>
                <div class="selectMonthReport" id="selectMonthReport<?= Yii::$app->hasher->encode($model->id) ?>">
                    <label>
                        <?= Html::radio('type', false, [
                            'value' => 'month',
                            'class' => 'radioswitch',
                            'data-id' => Yii::$app->hasher->encode($model->id),
                        ]) ?> <?= Yii::t('app', 'Month') ?>
                    </label>
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
                <br>
                <div class="sharedReport" id="sharedReport<?= Yii::$app->hasher->encode($model->id) ?>">
                    <?php $hour_valid = Yii::$app->appsetting->get('report_timeout') ? (int)Yii::$app->appsetting->get('report_timeout') : 24; ?>
                    <label>Bagikan Link Laporan? (Berlaku <?= $hour_valid ?> Jam)</label>
                    <?= Html::radioList('is_shared', 0, [0 => Yii::t('app', 'No'), 1 => Yii::t('app', 'Yes')], ['separator' => '<br>']); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= Yii::t('app', 'Close') ?></button>
                <?= Html::submitButton(Yii::t('app', 'Ok'), ['class' => 'submit btn btn-primary']) ?>
            </div>
            <?= Html::endForm() ?>
        </div>
    </div>
</div>