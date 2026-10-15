<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\technicalspec\models\DedFiles */

$this->title = Yii::t('technicalspec', 'DED Files');
$this->params['breadcrumbs'][] = ['label' => $ded->monitoringTask->name, 'url' => ['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($ded->monitoringTask->id)]];
$this->params['breadcrumbs'][] = ['label' => $ded->name, 'url' => ['default/view', 'id' => Yii::$app->hasher->encode($ded->id)]];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <?= DetailView::widget([
                    'model' => $ded,
                    'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                    'attributes' => [
                        'name',
                        // 'monitoring_task_id',
                        'monitoring_task_name:ntext',
                        [
                            'attribute' => 'cco',
                            'value' => function ($ded) {
                                if ($ded->cco == 0) {
                                    return 'MCO';
                                }
                                return 'CCO ' . $ded->cco;
                            },
                        ],
                        'specification:ntext',
                        'quantity',
                    ],
                ]) ?>
            </div>
        </div>
        <div class="ded-files-create">

            <?= $this->render('_form', [
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>