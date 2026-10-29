<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $selectedYear int|null */
/* @var $yearOptions array */
/* @var $report array */

$this->title = 'Rekap Pengawasan Tertib Penyelenggaraan Jasa Konstruksi';
$stats = $report['stats'];
$cards = [
    ['key' => 'paket', 'label' => 'Paket diawasi', 'icon' => 'bi-box-seam'],
    ['key' => 'pengawasan', 'label' => 'Pengawasan', 'icon' => 'bi-clipboard-check'],
    ['key' => 'temuan', 'label' => 'Temuan dipublikasikan', 'icon' => 'bi-exclamation-circle'],
    ['key' => 'selesai', 'label' => 'Tindak lanjut selesai', 'icon' => 'bi-check-circle'],
];

$this->registerCss(<<<'CSS'
.tertib-public { background: #f3f6f4; padding: 24px 0 48px; min-height: 420px; }
.tertib-public__inner { max-width: 1000px; margin: 0 auto; padding: 0 16px; }
.tertib-public__filter { background: #fff; border-radius: 14px; padding: 14px 16px 12px; margin-bottom: 12px; }
.tertib-public__label { display: block; color: #38443e; font-size: .84rem; margin-bottom: 7px; }
.tertib-public__controls { display: flex; gap: 8px; align-items: stretch; }
.tertib-public__select { flex: 1; min-width: 0; min-height: 44px; background-color: #f3f6f4; border: 1px solid #d1dcd5; border-radius: 9px; color: #30343a; }
.tertib-public__search { width: 44px; min-width: 44px; border: 0; border-radius: 9px; background: #46524d; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 1.1rem; }
.tertib-public__search:hover, .tertib-public__search:focus { background: #303a35; color: #fff; }
.tertib-public__grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; margin-bottom: 20px; }
.tertib-public__card { min-height: 118px; background: #fff; border-radius: 14px; padding: 15px 14px 12px; display: flex; flex-direction: column; align-items: flex-start; }
.tertib-public__icon { color: #46524d; font-size: 1.15rem; line-height: 1; }
.tertib-public__value { display: block; color: #30343a; font-size: 1.65rem; font-weight: 700; line-height: 1.25; margin-top: 13px; }
.tertib-public__name { color: #38443e; font-size: .84rem; margin-top: auto; }
.tertib-public__report { background: #fff; border-radius: 14px; overflow: hidden; }
.tertib-public__report-title { padding: 2px 16px 6px; margin: 0; color: #30343a; font-size: 1rem; font-weight: 600; border-bottom: 1px solid #e3e7e4; }
.tertib-public__table-wrap { padding: 0 16px 12px; }
.tertib-public__table { color: #38443e; margin-bottom: 0; }
.tertib-public__table thead th { background: #f3f6f4; color: #30343a; font-weight: 600; white-space: nowrap; }
.tertib-public__table tbody tr { border-color: #e3e7e4; }
.tertib-public__status { color: #46524d; white-space: nowrap; }
.tertib-public__note { color: #66716d; font-size: .84rem; margin: 12px 0 0; }
@media (max-width: 575px) { .tertib-public__grid { grid-template-columns: 1fr; } }
CSS);
?>
<section class="tertib-public">
    <div class="tertib-public__inner">
        <h1 class="visually-hidden"><?= Html::encode($this->title) ?></h1>

        <?= Html::beginForm(Url::to(['/rekap-pengawasan-tertib']), 'get', ['class' => 'tertib-public__filter']) ?>
            <?= Html::label('Pilih tahun', 'tertib-public-year', ['class' => 'tertib-public__label']) ?>
            <div class="tertib-public__controls">
                <?= Html::dropDownList('tahun', $selectedYear, $yearOptions, [
                    'id' => 'tertib-public-year',
                    'class' => 'form-select tertib-public__select',
                    'aria-label' => 'Pilih tahun anggaran',
                ]) ?>
                <button type="submit" class="tertib-public__search" aria-label="Tampilkan rekap" title="Tampilkan rekap">
                    <i class="bi bi-search" aria-hidden="true"></i>
                </button>
            </div>
        <?= Html::endForm() ?>

        <div class="tertib-public__grid" aria-label="<?= Html::encode($selectedYear === null ? 'Ringkasan semua tahun' : 'Ringkasan tahun ' . $selectedYear) ?>">
            <?php foreach ($cards as $card): ?>
                <article class="tertib-public__card">
                    <i class="bi <?= Html::encode($card['icon']) ?> tertib-public__icon" aria-hidden="true"></i>
                    <strong class="tertib-public__value">
                        <?= Yii::$app->formatter->asInteger((int) ($stats[$card['key']] ?? 0)) ?>
                    </strong>
                    <span class="tertib-public__name"><?= Html::encode($card['label']) ?></span>
                </article>
            <?php endforeach; ?>
        </div>

        <section class="tertib-public__report" aria-labelledby="tertib-public-report-title">
            <h2 class="tertib-public__report-title" id="tertib-public-report-title">Rekap Pengawasan Tahunan</h2>
            <div class="table-responsive tertib-public__table-wrap">
                <table class="table align-middle tertib-public__table">
                    <thead>
                        <tr>
                            <th scope="col">Tahun anggaran</th>
                            <th scope="col" class="text-end">Paket diawasi</th>
                            <th scope="col" class="text-end">Pengawasan</th>
                            <th scope="col" class="text-end">Temuan publik</th>
                            <th scope="col" class="text-end">Tindak lanjut selesai</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($report['rows']): ?>
                            <?php foreach ($report['rows'] as $row): ?>
                                <tr>
                                    <th scope="row"><?= Html::encode($row['tahun']) ?></th>
                                    <td class="text-end"><?= Yii::$app->formatter->asInteger((int) $row['paket']) ?></td>
                                    <td class="text-end"><?= Yii::$app->formatter->asInteger((int) $row['pengawasan']) ?></td>
                                    <td class="text-end"><?= Yii::$app->formatter->asInteger((int) $row['temuan']) ?></td>
                                    <td class="text-end"><?= Yii::$app->formatter->asInteger((int) $row['selesai']) ?></td>
                                    <td><span class="tertib-public__status">Tersedia</span></td>
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
        </section>
        <p class="tertib-public__note">Rekap hanya menghitung kegiatan dan temuan yang telah diizinkan untuk dipublikasikan.</p>
    </div>
</section>
