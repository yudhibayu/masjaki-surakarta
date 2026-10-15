<?php

use yii\helpers\Html;
use yii\widgets\ListView;
?>
<div class="row">
    <div class="col-md-12">
        <p class="text-center">
            <?= Html::a(Yii::t('app', 'Add'), ['/technicalspec/monitoring-spec/create', 'progressid' => Yii::$app->hasher->encode($monitoring_progress_id)], ['class' => 'btn btn-success', 'data-pjax' => 0 ]) ?>
        </p>
        <?= ListView::widget(
            [
                'dataProvider' => $dataProvider,
                'itemView' => '_view_bare',
            ],
        ); ?>
    </div>
</div>