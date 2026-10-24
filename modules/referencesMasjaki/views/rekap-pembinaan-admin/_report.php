<?php

use yii\helpers\Html;

$stats = $report['stats'];
?>
<section class="card">
    <div class="card-header"><h3 class="mb-0">Rekapitulasi Pembinaan</h3></div>
    <div class="card-body">
        <?= Html::beginForm($filterRoute, 'get', ['class' => 'row g-2 mb-4']) ?>
            <div class="col-md-4"><?= Html::dropDownList('tahun', $selectedYear, array_combine($report['years'], $report['years']) ?: [], ['class' => 'form-select', 'prompt' => 'Semua tahun']) ?></div>
            <div class="col-md-4"><?= Html::dropDownList('jenis', $selectedType, $typeOptions, ['class' => 'form-select', 'prompt' => 'Semua jenis kegiatan']) ?></div>
            <div class="col-md-3"><button class="btn btn-outline-primary">Terapkan</button></div>
        <?= Html::endForm() ?>
        <div class="row g-3 mb-4">
            <?php foreach (['bujk' => 'BUJK unik', 'masyarakat' => 'Masyarakat unik', 'kegiatan' => 'Kegiatan', 'keikutsertaan' => 'Keikutsertaan'] as $key => $label): ?>
                <div class="col-sm-6 col-xl-3"><div class="card"><div class="card-body"><div class="text-muted small"><?= Html::encode($label) ?></div><div class="fs-3 fw-semibold"><?= number_format($stats[$key], 0, ',', '.') ?></div></div></div></div>
            <?php endforeach; ?>
        </div>
        <h4 class="mt-4">Rekap per Jenis Kegiatan</h4>
        <div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th>Jenis kegiatan</th><th>BUJK dibina</th><th>Masyarakat dibina</th><th>Kegiatan</th><th>Keikutsertaan</th></tr></thead><tbody>
            <?php foreach ($report['typeRows'] as $row): ?><tr>
                <td><?= Html::encode($row['jenis_kegiatan']) ?></td><td><?= number_format((int) $row['bujk'], 0, ',', '.') ?></td>
                <td><?= number_format((int) $row['masyarakat'], 0, ',', '.') ?></td><td><?= number_format((int) $row['kegiatan'], 0, ',', '.') ?></td>
                <td><?= number_format((int) $row['keikutsertaan'], 0, ',', '.') ?></td>
            </tr><?php endforeach; ?>
            <?php if (!$report['typeRows']): ?><tr><td colspan="5" class="text-muted">Belum ada data untuk jenis kegiatan ini.</td></tr><?php endif; ?>
        </tbody></table></div>
        <h4 class="mt-4">Rekap per Tahun</h4>
        <div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th>Tahun</th><th>BUJK dibina</th><th>Masyarakat dibina</th><th>Kegiatan</th><th>Keikutsertaan</th></tr></thead><tbody>
            <?php foreach ($report['rows'] as $row): ?><tr>
                <td><?= Html::encode($row['tahun']) ?></td><td><?= number_format((int) $row['bujk'], 0, ',', '.') ?></td>
                <td><?= number_format((int) $row['masyarakat'], 0, ',', '.') ?></td><td><?= number_format((int) $row['kegiatan'], 0, ',', '.') ?></td>
                <td><?= number_format((int) $row['keikutsertaan'], 0, ',', '.') ?></td>
            </tr><?php endforeach; ?>
            <?php if (!$report['rows']): ?><tr><td colspan="5" class="text-muted">Belum ada kegiatan.</td></tr><?php endif; ?>
        </tbody></table></div>
    </div>
</section>
