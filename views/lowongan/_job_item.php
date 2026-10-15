<?php
/* @var $model app\models\JobVacancy */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="job-card card mb-4">
    <div class="card-body">
        <h5 class="card-title"><?= Html::encode($model->name) ?></h5>
        <p class="card-text"><?= Html::encode($model->description) ?></p>
        <div class="details">
            <ul class="list-unstyled">
                <p class="label">Tipe Konstruksi:</p>
                <li>
                    <p class="value">
                        <?php
                        $maxChars = 75;
                        $constructionName = $model->constructionWork ? $model->constructionWork->name : 'Tidak Tersedia';
                        if (mb_strlen($constructionName) > $maxChars) {
                            $constructionName = mb_substr($constructionName, 0, $maxChars) . '...';
                        }
                        echo Html::encode($constructionName);
                        ?>
                    </p>
                </li>
                <li>
                    <span class="label">Keterampilan:</span>
                    <span class="text-right">
                        <?= Html::encode($model->skillsRelation ? $model->skillsRelation->name : 'Tidak Tersedia') ?>
                    </span>
                </li>
                <li><span class="label">Pendidikan:</span> <?= Html::encode($model->educationRelation ? $model->educationRelation->name : 'Tidak Tersedia') ?></li>
                <li><span class="label">Tipe Pekerjaan:</span> <?= Html::encode($model->workType) ?></li>
                <li><span class="label">Tahun:</span> <?= Html::encode($model->year_name) ?></li>
                <li>
                    <span class="label">Kisaran Gaji:</span>
                    <span class="text-right">
                        <?= Html::encode('Rp ' . number_format($model->salary_range, 0, ',', '.') . ' - Rp ' . number_format($model->salary_range_upto, 0, ',', '.')) ?>
                    </span>
                </li>
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