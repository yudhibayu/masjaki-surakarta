<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $selectedYear int */
/* @var $yearOptions array */
/* @var $report array */

$this->title = 'Rekap Pengawasan Tertib Penyelenggaraan Jasa Konstruksi';
$stats = $report['stats'];
$cards = [
    ['key' => 'paket', 'label' => 'Paket Diawasi'],
    ['key' => 'pengawasan', 'label' => 'Pengawasan'],
    ['key' => 'temuan', 'label' => 'Temuan Dipublikasikan'],
    ['key' => 'selesai', 'label' => 'Tindak Lanjut Selesai'],
];

$this->registerCss(<<<CSS
.tertib-public-page { padding-top: 1.25rem; padding-bottom: 2.5rem; }
.tertib-public-title { color: #27364b; font-weight: 700; }
.tertib-public-description { color: #687586; max-width: 760px; }
.tertib-public-card { height: 100%; border: 1px solid #e4e8ee; border-radius: 12px; background: #fff; }
.tertib-public-card .card-body { min-height: 112px; display: flex; flex-direction: column; justify-content: space-between; }
.tertib-public-card__label { color: #687586; font-size: .95rem; }
.tertib-public-card__value { color: #26364b; font-size: 1.8rem; font-weight: 700; line-height: 1.1; }
.tertib-public-section { border: 1px solid #e4e8ee; border-radius: 12px; background: #fff; }
.tertib-public-section .table { margin-bottom: 0; }
@media (max-width: 575.98px) {
    .tertib-public-page { padding-top: .5rem; }
    .tertib-public-card .card-body { min-height: 96px; }
}
CSS);
?>

<main class="container tertib-public-page">
    <header class="mb-4">
        <h1 class="h3 tertib-public-title"><?= Html::encode($this->title) ?></h1>
        <p class="tertib-public-description mb-0">
            Informasi pengawasan penyelenggaraan jasa konstruksi di Kota Surakarta.
        </p>
    </header>

    <section class="mb-4" aria-label="Filter rekap pengawasan">
        <?= Html::beginForm(Url::to(['/rekap-pengawasan-tertib']), 'get', ['class' => 'row g-2 align-items-center']) ?>
            <div class="col-auto">
                <?= Html::label('Tahun anggaran', 'tertib-public-year', ['class' => 'form-label fw-semibold mb-0']) ?>
            </div>
            <div class="col-12 col-sm-5 col-md-3">
                <?= Html::dropDownList('tahun', $selectedYear, $yearOptions, [
                    'id' => 'tertib-public-year',
                    'class' => 'form-select',
                    'aria-label' => 'Pilih tahun anggaran',
                ]) ?>
            </div>
            <div class="col-auto">
                <?= Html::submitButton('Tampilkan', ['class' => 'btn btn-danger']) ?>
            </div>
        <?= Html::endForm() ?>
    </section>

    <section class="row g-3 mb-4" aria-label="Ringkasan tahun <?= Html::encode($selectedYear) ?>">
        <?php foreach ($cards as $card): ?>
            <div class="col-12 col-sm-6 col-xl-3">
                <article class="card tertib-public-card">
                    <div class="card-body">
                        <div class="tertib-public-card__label"><?= Html::encode($card['label']) ?></div>
                        <div class="tertib-public-card__value"><?= number_format((int) $stats[$card['key']], 0, ',', '.') ?></div>
                    </div>
                </article>
            </div>
        <?php endforeach; ?>
    </section>

    <section class="tertib-public-section p-3 p-md-4">
        <h2 class="h5 tertib-public-title mb-3">Rekap Pengawasan Tahunan</h2>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">Tahun Anggaran</th>
                        <th scope="col" class="text-end">Paket Diawasi</th>
                        <th scope="col" class="text-end">Pengawasan</th>
                        <th scope="col" class="text-end">Temuan Publik</th>
                        <th scope="col" class="text-end">Tindak Lanjut Selesai</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($report['rows']): ?>
                        <?php foreach ($report['rows'] as $row): ?>
                            <tr>
                                <th scope="row"><?= Html::encode($row['tahun']) ?></th>
                                <td class="text-end"><?= number_format($row['paket'], 0, ',', '.') ?></td>
                                <td class="text-end"><?= number_format($row['pengawasan'], 0, ',', '.') ?></td>
                                <td class="text-end"><?= number_format($row['temuan'], 0, ',', '.') ?></td>
                                <td class="text-end"><?= number_format($row['selesai'], 0, ',', '.') ?></td>
                                <td><span class="badge text-bg-success">Tersedia</span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada rekap yang diizinkan untuk ditampilkan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <p class="small text-muted mt-3 mb-0">
            Rekap hanya menghitung kegiatan dan temuan yang telah diizinkan untuk dipublikasikan.
        </p>
    </section>
</main>
