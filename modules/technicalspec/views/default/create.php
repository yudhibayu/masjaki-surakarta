<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\technicalspec\models\DedData */

$this->title = Yii::t('technicalspec', 'DED');
$this->params['breadcrumbs'][] = ['label' => $task->name, 'url' => ['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($task->id)]];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="task-detail">
            <h3><?= Yii::t('monitoring', 'Task Detail') ?></h3>
            <?= DetailView::widget([
                'model' => $task,
                'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
                'attributes' => [
                    'name:ntext',
                    'rup.namecode',
                    'pbj.namecode',
                    // 'unit_id',
                    'unit_name',
                    'team_name',
                ],
            ]) ?>
        </div>
        <hr>
        <div class="ded-data-create">

            <?= $this->render('_form', [
                'is_create'     => true,
                'task'          => $task,
                'model'         => $model,
                'dropdown_data' => $dropdown_data,
                'url_prev'      => $url_prev,
            ]) ?>

        </div>
    </div>
</section>