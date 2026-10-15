<?php
/* @var $model app\models\JobVacancy */ // Sesuaikan dengan model kamu
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="job-card card mb-4">
    <div class="card-body">
        <h5 class="card-title"><?= Html::encode($model->training_name) ?></h5>
        <hr>
        <p class="card-text"><?= Html::encode($model->description) ?></p>
        <div class="details">
            <ul class="list-unstyled">
                <li>
                    <span class="label">Keterampilan:</span>
                    <span class="text-right">
                        <?= Html::encode($model->skillsRelation ? $model->skillsRelation->name : 'Tidak Tersedia') ?>
                    </span>
                </li>
                <!-- <li><span class="label">Keterampilan:</span> <= Html::encode($model->skillsRelation ? $model->skillsRelation->name : 'Tidak Tersedia') ?></li> -->
                <li><span class="label">Tahun:</span> <?= Html::encode($model->year_name) ?></li>
                <li><span class="label">Kuota:</span> <?= Html::encode($model->kuota) ?> orang</li>
            </ul>
        </div>
        <a href="<?= Url::toRoute(['view', 'id' => Yii::$app->hasher->encode($model->id)]) ?>" class="custom-btn">
            Lihat Selengkapnya
            <i class='bx bx-right-arrow-alt ms-2'></i>
        </a>
    </div>
</div>
<style>
    .text-right {
        display: block;
        text-align: right;
    }
</style>