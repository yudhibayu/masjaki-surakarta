<?php

/* @var $model app\modules\referencesMasjaki\models\News */

use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="news-card card mb-4" style="width: 100%; max-width: 360px; height: 420px; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
    <?php if ($model->thumbnail): ?>
        <img src="<?= Yii::getAlias('@web/photo') . '/' . Html::encode($model->thumbnail) ?>" class="card-img-top" alt="<?= Html::encode($model->title) ?>" style="width: 100%; height: 200px; object-fit: cover;">
    <?php endif; ?>
    <div class="card-body">
        <?php
        $maxTitleLength = 70;
        $title = Html::encode($model->title);
        if (mb_strlen($title) > $maxTitleLength) {
            $title = mb_substr($title, 0, $maxTitleLength) . '...';
        }
        ?>
        <h5 class="card-title" style="font-size: 18px; font-weight: bold; margin-bottom: 10px;"><?= $title ?></h5>
        <p class="card-date" style="font-size: 14px; color: #555; margin-bottom: 15px;">
            <i class='bx bx-calendar' style="font-size: 16px; vertical-align: middle;"></i>
            <?php
            $bulan = [
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
                'Desember'
            ];
            $tanggal = new DateTime($model->created_at);
            $hari = $tanggal->format('d');
            $bulanIndex = $tanggal->format('n') - 1;
            $tahun = $tanggal->format('Y');
            $formattedDate = $hari . ' ' . $bulan[$bulanIndex] . ' ' . $tahun;
            ?>
            <span class="post-date"><?= $formattedDate ?></span>
        </p>
        <a href="<?= Url::toRoute(['view', 'id' => Yii::$app->hasher->encode($model->id)]) ?>" class="custom-btn position-absolute bottom-0 mb-4" style="display: inline-flex; align-items: center; padding: 10px 15px; color: white; text-decoration: none; border-radius: 5px;">
            Lihat Selengkapnya
            <i class='bx bx-right-arrow-alt ms-2'></i>
        </a>
    </div>
</div>