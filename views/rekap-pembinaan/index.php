<?php

use yii\helpers\Html;

$this->title = 'Rekap Data BUJK dan Masyarakat Jasa Konstruksi yang Dibina dan Ditingkatkan Kapasitasnya';
$stats = $report['stats'];
$cards = [
    ['key' => 'bujk', 'label' => 'BUJK dibina', 'icon' => 'bi-building'],
    ['key' => 'masyarakat', 'label' => 'Masyarakat dibina', 'icon' => 'bi-people'],
    ['key' => 'kegiatan', 'label' => 'Total kegiatan', 'icon' => 'bi-calendar-check'],
    ['key' => 'keikutsertaan', 'label' => 'Total keikutsertaan', 'icon' => 'bi-person-check'],
];

$this->registerCss(<<<'CSS'
.pembinaan-public { background: #f3f6f4; padding: 24px 0 48px; min-height: 420px; }
.pembinaan-public__inner { max-width: 1000px; margin: 0 auto; padding: 0 16px; }
.pembinaan-public__filter { background: #fff; border-radius: 14px; padding: 14px 16px 12px; margin-bottom: 12px; }
.pembinaan-public__label { display: block; color: #38443e; font-size: .84rem; margin-bottom: 7px; }
.pembinaan-public__controls { display: flex; gap: 8px; align-items: stretch; }
.pembinaan-public__select { flex: 1; min-width: 0; min-height: 44px; background-color: #f3f6f4; border: 1px solid #d1dcd5; border-radius: 9px; color: #30343a; }
.pembinaan-public__search { width: 44px; min-width: 44px; border: 0; border-radius: 9px; background: #46524d; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 1.1rem; }
.pembinaan-public__search:hover, .pembinaan-public__search:focus { background: #303a35; color: #fff; }
.pembinaan-public__grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; margin-bottom: 20px; }
.pembinaan-public__card { min-height: 118px; background: #fff; border-radius: 14px; padding: 15px 14px 12px; display: flex; flex-direction: column; align-items: flex-start; }
.pembinaan-public__icon { color: #46524d; font-size: 1.15rem; line-height: 1; }
.pembinaan-public__value { display: block; color: #30343a; font-size: 1.65rem; font-weight: 700; line-height: 1.25; margin-top: 13px; }
.pembinaan-public__name { color: #38443e; font-size: .84rem; margin-top: auto; }
.pembinaan-public__report { background: #fff; border-radius: 14px; overflow: hidden; }
.pembinaan-public__report-title { padding: 2px 16px 6px; margin: 0; color: #30343a; font-size: 1rem; font-weight: 600; border-bottom: 1px solid #e3e7e4; }
.pembinaan-public__table-wrap { padding: 0 16px 12px; }
.pembinaan-public__table { color: #38443e; margin-bottom: 0; }
.pembinaan-public__table thead th { background: #f3f6f4; color: #30343a; font-weight: 600; white-space: nowrap; }
.pembinaan-public__table tbody tr { border-color: #e3e7e4; }
.pembinaan-public__note { color: #66716d; font-size: .84rem; margin: 12px 0 0; }
@media (max-width: 575px) { .pembinaan-public__grid { grid-template-columns: 1fr; } }
CSS);
?>
<section class="pembinaan-public">
    <div class="pembinaan-public__inner">
        <h1 class="visually-hidden"><?= Html::encode($this->title) ?></h1>

        <?= Html::beginForm(['/rekap-pembinaan'], 'get', ['class' => 'pembinaan-public__filter']) ?>
            <?= Html::label('Pilih tahun', 'pembinaan-tahun', ['class' => 'pembinaan-public__label']) ?>
            <div class="pembinaan-public__controls">
                <?= Html::dropDownList('tahun', $selectedYear, array_combine($report['years'], $report['years']) ?: [], [
                    'id' => 'pembinaan-tahun',
                    'class' => 'form-select pembinaan-public__select',
                    'prompt' => 'Semua tahun',
                ]) ?>
                <button type="submit" class="pembinaan-public__search" aria-label="Tampilkan rekap" title="Tampilkan rekap">
                    <i class="bi bi-search" aria-hidden="true"></i>
                </button>
            </div>
        <?= Html::endForm() ?>

        <div class="pembinaan-public__grid" aria-label="Ringkasan statistik">
            <?php foreach ($cards as $card): ?>
                <div class="pembinaan-public__card">
                    <i class="bi <?= Html::encode($card['icon']) ?> pembinaan-public__icon" aria-hidden="true"></i>
                    <strong class="pembinaan-public__value">
                        <?= Yii::$app->formatter->asInteger((int) ($stats[$card['key']] ?? 0)) ?>
                    </strong>
                    <span class="pembinaan-public__name"><?= Html::encode($card['label']) ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <section class="pembinaan-public__report" aria-labelledby="pembinaan-report-title">
            <h2 class="pembinaan-public__report-title" id="pembinaan-report-title">Rekap Tahunan</h2>
            <div class="table-responsive pembinaan-public__table-wrap">
                <table class="table align-middle pembinaan-public__table">
                    <thead><tr>
                        <th>Tahun</th><th>BUJK dibina</th><th>Masyarakat dibina</th><th>Total kegiatan</th><th>Total keikutsertaan</th>
                    </tr></thead>
                    <tbody>
                        <?php foreach ($report['rows'] as $row): ?>
                            <tr>
                                <td><?= Html::encode($row['tahun']) ?></td>
                                <td><?= Yii::$app->formatter->asInteger((int) $row['bujk']) ?></td>
                                <td><?= Yii::$app->formatter->asInteger((int) $row['masyarakat']) ?></td>
                                <td><?= Yii::$app->formatter->asInteger((int) $row['kegiatan']) ?></td>
                                <td><?= Yii::$app->formatter->asInteger((int) $row['keikutsertaan']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (!$report['rows']): ?>
                            <tr><td colspan="5" class="text-muted">Belum ada kegiatan yang dipublikasikan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
        <p class="pembinaan-public__note">Ringkasan hanya memuat kegiatan yang telah dipublikasikan. Nama peserta dan data pribadi tidak ditampilkan.</p>
    </div>
</section>
