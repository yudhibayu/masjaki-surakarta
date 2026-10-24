<?php

use yii\helpers\Html;

$this->title = 'Dashboard Pembinaan BUJK dan Masyarakat';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card mb-3">
    <div class="card-header"><h3 class="mb-0">Dashboard Pembinaan</h3></div>
    <div class="card-body">
        <p>Kelola data pembinaan melalui sub menu berikut.</p>
        <div class="row g-2">
            <?php foreach ([
                ['/references-masjaki/masyarakat-jasa-konstruksi', 'Data Masyarakat Jasa Konstruksi'],
                ['/references-masjaki/jenis-kegiatan-pembinaan', 'Master Jenis Kegiatan'],
                ['/references-masjaki/kegiatan-pembinaan', 'Kegiatan Pembinaan'],
                ['/references-masjaki/peserta-pembinaan', 'Peserta Pembinaan'],
                ['/references-masjaki/hasil-pembinaan', 'Hasil Pembinaan'],
                ['/references-masjaki/dokumen-pembinaan', 'Dokumen Pendukung'],
            ] as $item): ?>
                <div class="col-md-6 col-lg-4"><?= Html::a(Html::encode($item[1]), $item[0], ['class' => 'btn btn-outline-primary w-100 text-start']) ?></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?= $this->render('../rekap-pembinaan-admin/_report', [
    'selectedYear' => $selectedYear,
    'selectedType' => $selectedType,
    'typeOptions' => $typeOptions,
    'report' => $report,
    'filterRoute' => ['/references-masjaki/pembinaan/index'],
]) ?>
