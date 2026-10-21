<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;

$activity = $report['activity'];
$this->title = 'Detail Rekap Sertifikasi: ' . $activity['nama_kegiatan'];
$this->params['breadcrumbs'][] = ['label' => 'Rekap Sertifikasi TKK', 'url' => ['/rekap-sertifikasi-tkk/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container my-5">
    <div class="card shadow-sm border-0 overflow-hidden rounded-3">
        <div class="card-header px-4 py-4">
            <h1 class="h3 mb-2"><?= Html::encode($this->title) ?></h1>
            <p class="mb-0">Tahun Anggaran <?= Html::encode($activity['tahun_anggaran']) ?> · Ringkasan tanpa data pribadi peserta</p>
        </div>
        <div class="card-body p-4">
            <p><?= Html::a('Kembali ke Rekap', ['/rekap-sertifikasi-tkk/index'], ['class' => 'btn btn-outline-primary']) ?></p>
            <div class="table-responsive">
                <table class="table table-light table-striped table-hover align-middle">
                    <thead><tr>
                        <th scope="col">No.</th><th scope="col">Jabatan Kerja</th><th scope="col">Jenjang</th>
                        <th scope="col">Skema</th><th scope="col">Peserta</th><th scope="col">Kompeten</th>
                        <th scope="col">SKK Terbit</th>
                    </tr></thead>
                    <tbody>
                        <?php foreach ($report['rows'] as $index => $row): ?>
                            <tr>
                                <td><?= $report['pagination']->offset + $index + 1 ?></td>
                                <td><?= Html::encode($row['jabatan_kerja']) ?></td>
                                <td><?= Html::encode($row['jenjang_kualifikasi'] ?: '—') ?></td>
                                <td><?= Html::encode($row['skema_sertifikasi']) ?></td>
                                <td><?= Yii::$app->formatter->asInteger((int) $row['jumlah_peserta']) ?></td>
                                <td><?= Yii::$app->formatter->asInteger((int) $row['jumlah_kompeten']) ?></td>
                                <td><?= Yii::$app->formatter->asInteger((int) $row['jumlah_skk']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (!$report['rows']): ?>
                            <tr><td colspan="7" class="text-center py-4">Belum ada peserta untuk kegiatan ini.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?= LinkPager::widget(['pagination' => $report['pagination']]) ?>
        </div>
    </div>
</div>
