<?php

use yii\helpers\Html;

$this->title = 'Rekap Data BUJK dan Masyarakat Jasa Konstruksi yang Dibina dan Ditingkatkan Kapasitasnya';
$reportTitle = $this->title;
$stats = $report['stats'];
$cards = [
    ['key' => 'bujk', 'label' => 'BUJK dibina', 'tone' => 'green'],
    ['key' => 'masyarakat', 'label' => 'Masyarakat dibina', 'tone' => 'blue'],
    ['key' => 'kegiatan', 'label' => 'Total kegiatan', 'tone' => 'amber'],
    ['key' => 'keikutsertaan', 'label' => 'Total keikutsertaan', 'tone' => 'purple'],
];
$this->registerCss(<<<'CSS'
.rekap-pembinaan-stat-card { border: 0; border-top: 4px solid var(--stat-accent); border-radius: 14px; background: var(--stat-bg); color: var(--stat-ink); }
.rekap-pembinaan-stat-card .text-muted { color: var(--stat-muted) !important; }
.rekap-pembinaan-stat-card__value { color: var(--stat-ink); }
.rekap-pembinaan-stat-card--green { --stat-bg: #e9f4ed; --stat-accent: #28754f; --stat-ink: #17452f; --stat-muted: #416a54; }
.rekap-pembinaan-stat-card--blue { --stat-bg: #eaf2f8; --stat-accent: #3479a7; --stat-ink: #1f4e6a; --stat-muted: #4f7085; }
.rekap-pembinaan-stat-card--amber { --stat-bg: #fff4df; --stat-accent: #c18422; --stat-ink: #704d13; --stat-muted: #816a43; }
.rekap-pembinaan-stat-card--purple { --stat-bg: #f2ecf8; --stat-accent: #79589b; --stat-ink: #4d3864; --stat-muted: #71617f; }
CSS);
?>
<main class="container py-4">
    <header class="mb-4">
        <h1 class="h2"><?= Html::encode($reportTitle) ?></h1>
        <p class="text-muted mb-0">Ringkasan hanya memuat kegiatan yang telah dipublikasikan. Nama peserta dan data pribadi tidak ditampilkan.</p>
    </header>
    <section class="card mb-4 rekap-pembinaan-stat-card rekap-pembinaan-stat-card--amber"><div class="card-body">
        <?= Html::beginForm(['/rekap-pembinaan'], 'get', ['class' => 'row g-2 align-items-end']) ?>
            <div class="col-sm-6 col-md-4">
                <label class="form-label" for="pembinaan-tahun">Pilih tahun</label>
                <?= Html::dropDownList('tahun', $selectedYear, array_combine($report['years'], $report['years']) ?: [], ['id' => 'pembinaan-tahun', 'class' => 'form-select', 'prompt' => 'Semua tahun']) ?>
            </div>
            <div class="col-auto"><button class="btn btn-primary">Tampilkan</button></div>
        <?= Html::endForm() ?>
    </div></section>
    <section class="row g-3 mb-4" aria-label="Ringkasan statistik">
        <?php foreach ($cards as $card): ?>
            <div class="col-6 col-xl-3"><div class="card h-100 rekap-pembinaan-stat-card rekap-pembinaan-stat-card--<?= Html::encode($card['tone']) ?>"><div class="card-body">
                <div class="small text-muted"><?= Html::encode($card['label']) ?></div>
                <div class="fs-3 fw-semibold rekap-pembinaan-stat-card__value"><?= number_format($stats[$card['key']], 0, ',', '.') ?></div>
            </div></div></div>
        <?php endforeach; ?>
    </section>
    <section class="card rekap-pembinaan-stat-card rekap-pembinaan-stat-card--amber"><div class="card-header"><h2 class="h5 mb-0">Rekap Tahunan</h2></div><div class="card-body table-responsive">
        <table class="table table-striped align-middle mb-0"><thead><tr>
            <th>Tahun</th><th>BUJK dibina</th><th>Masyarakat dibina</th><th>Total kegiatan</th><th>Total keikutsertaan</th>
        </tr></thead><tbody>
            <?php foreach ($report['rows'] as $row): ?><tr>
                <td><?= Html::encode($row['tahun']) ?></td>
                <td><?= number_format((int) $row['bujk'], 0, ',', '.') ?></td>
                <td><?= number_format((int) $row['masyarakat'], 0, ',', '.') ?></td>
                <td><?= number_format((int) $row['kegiatan'], 0, ',', '.') ?></td>
                <td><?= number_format((int) $row['keikutsertaan'], 0, ',', '.') ?></td>
            </tr><?php endforeach; ?>
            <?php if (!$report['rows']): ?><tr><td colspan="5" class="text-muted">Belum ada kegiatan yang dipublikasikan.</td></tr><?php endif; ?>
        </tbody></table>
    </div></section>
</main>
