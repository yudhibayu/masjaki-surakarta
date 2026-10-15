<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'News'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-view">

    <header id="header" class="text-center">
        <div id="post-header" class="page-header" style="position: relative; overflow: hidden;">
            <div class="background-img" style="background-image: url('<?= Yii::getAlias('@web/photo') . '/' . Html::encode($model->thumbnail) ?>'); background-size: cover; background-position: center; height: 300px;">
                <div class="overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);"></div>
            </div>
            <div class="container" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #fff; text-align: center;">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <h1 style="font-size: 2.5rem; font-weight: bold; color:#fff; padding: 10px;"><?= Html::encode($model->title) ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="section" style="padding: 40px 0;">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="main-post" style="background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                        <div class="post-meta" style="font-size: 1rem; margin-top: 10px;">
                            <?php if ($model->thumbnail): ?>
                                <figure class="figure-img" style="margin-top: 20px;">
                                    <img src="<?= Yii::getAlias('@web/photo') . '/' . Html::encode($model->thumbnail) ?>" class="card-img-top rounded-2" alt="<?= Html::encode($model->title) ?>" style="width: 100%; height: auto; object-fit: cover;">
                                    <figcaption style="text-align: center; font-size: 0.9rem; margin-top: 10px;"><?= Html::encode($model->title) ?></figcaption>
                                </figure>
                            <?php endif; ?>
                        </div>
                        <p style="font-size: 1.1rem; line-height: 1.6;"> <?= HtmlPurifier::process($model->content) ?></p>
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

                        echo "<i class='bx bx-calendar'></i> ";
                        ?>

                        <span class="post-date"><?= $formattedDate ?></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-rekomen">
                        <div class="card-header mb-3">
                            <h4><?= Yii::t('app', 'Other News') ?></h4>
                        </div>
                        <div class="rekomen-view">
                            <?php foreach ($modelberita as $berita) { ?>
                                <div class="news-item">
                                    <img src="<?= Yii::getAlias('@web/photo') . '/' . Html::encode($berita['thumbnail']) ?>" alt="<?= Html::encode($berita['title']) ?>">
                                    <a class="rekomen-link" href="/berita/view?id=<?= Yii::$app->hasher->encode($berita['id']) ?>"><?= Html::encode($berita['title']) ?></a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .news-item {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .news-item img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 5px;
        margin-right: 10px;
    }

    .news-item .rekomen-link {
        font-size: 1rem;
        color: #007bff;
        text-decoration: none;
        font-weight: 500;
    }

    .news-item .rekomen-link:hover {
        text-decoration: underline;
    }

    .card-rekomen {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>