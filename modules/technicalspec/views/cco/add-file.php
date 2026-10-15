<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\technicalspec\models\McoCcoFile */

$this->title = Yii::t('technicalspec', 'Add File MCO/CCO');
$this->params['breadcrumbs'][] = ['label' => Yii::t('technicalspec', 'MCO/CCO'), 'url' => ['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($id_monitoring_task)]];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="card-body">
        <div class="mco-cco-file-create">

            <?= $this->render('_form_file', [
                'model'         => $model,
                'id_monitoring_task' =>  $id_monitoring_task
            ]) ?>

        </div>
    </div>
</section>
