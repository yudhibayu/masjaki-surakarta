<?php

use yii\helpers\Html;

$this->title = 'Rekap Data Tenaga Kerja Konstruksi yang Difasilitasi Sertifikasi';
$this->params['breadcrumbs'][] = $this->title;
$cards = [
    ['key' => 'tenaga_kerja', 'label' => 'Tenaga kerja difasilitasi', 'icon' => 'bi-people'],
    ['key' => 'keikutsertaan', 'label' => 'Keikutsertaan sertifikasi', 'icon' => 'bi-calendar-check'],
    ['key' => 'kompeten', 'label' => 'Peserta kompeten', 'icon' => 'bi-check-circle'],
    ['key' => 'skk', 'label' => 'SKK diterbitkan', 'icon' => 'bi-file-earmark-check'],
];
$this->registerCss(<<<'CSS'
.tkk-public-summary { background: #f3f6f4; padding: 24px 0 48px; min-height: 420px; }
.tkk-public-summary__inner { max-width: 1000px; margin: 0 auto; padding: 0 16px; }
.tkk-public-summary__filter { background: #fff; border-radius: 14px; padding: 14px 16px 12px; margin-bottom: 12px; }
.tkk-public-summary__label { display: block; color: #38443e; font-size: .84rem; margin-bottom: 7px; }
.tkk-public-summary__controls { display: flex; gap: 8px; align-items: stretch; }
.tkk-public-summary__select { flex: 1; min-width: 0; min-height: 44px; background-color: #f3f6f4; border: 1px solid #d1dcd5; border-radius: 9px; color: #173f31; }
.tkk-public-summary__search { width: 44px; min-width: 44px; border: 0; border-radius: 9px; background: #216f4b; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 1.1rem; }
.tkk-public-summary__search:hover, .tkk-public-summary__search:focus { background: #175839; color: #fff; }
.tkk-public-summary__grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; }
.tkk-public-summary__card { min-height: 118px; background: #fff; border-radius: 14px; padding: 15px 14px 12px; display: flex; flex-direction: column; align-items: flex-start; }
.tkk-public-summary__icon { color: #216f4b; font-size: 1.15rem; line-height: 1; }
.tkk-public-summary__value { display: block; color: #123c2f; font-size: 1.65rem; font-weight: 700; line-height: 1.25; margin-top: 13px; }
.tkk-public-summary__name { color: #38443e; font-size: .84rem; margin-top: auto; }
.tkk-public-summary__empty { color: #52635a; font-size: .84rem; margin: 12px 0 0; }
@media (max-width: 575px) { .tkk-public-summary__grid { grid-template-columns: 1fr; } }
CSS);
?>
<section class="tkk-public-summary">
    <div class="tkk-public-summary__inner">
        <h1 class="visually-hidden"><?= Html::encode($this->title) ?></h1>
        <?= Html::beginForm(['/rekap-sertifikasi-tkk/index'], 'get', ['class' => 'tkk-public-summary__filter']) ?>
            <?= Html::label('Pilih tahun anggaran', 'tkk-tahun', ['class' => 'tkk-public-summary__label']) ?>
            <div class="tkk-public-summary__controls">
                <?= Html::dropDownList('tahun', $filter->tahun, ['' => 'Semua tahun'] + $report['years'], [
                    'id' => 'tkk-tahun', 'class' => 'form-select tkk-public-summary__select',
                ]) ?>
                <button type="submit" class="tkk-public-summary__search" aria-label="Tampilkan rekap" title="Tampilkan rekap">
                    <i class="bi bi-search" aria-hidden="true"></i>
                </button>
            </div>
        <?= Html::endForm() ?>

        <div class="tkk-public-summary__grid">
            <?php foreach ($cards as $card): ?>
                <div class="tkk-public-summary__card">
                    <i class="bi <?= Html::encode($card['icon']) ?> tkk-public-summary__icon" aria-hidden="true"></i>
                    <strong class="tkk-public-summary__value" data-tkk-public-stat="<?= Html::encode($card['key']) ?>">
                        <?= Yii::$app->formatter->asInteger((int) ($report['stats'][$card['key']] ?? 0)) ?>
                    </strong>
                    <span class="tkk-public-summary__name"><?= Html::encode($card['label']) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if (!$report['years']): ?>
            <p class="tkk-public-summary__empty">Belum ada kegiatan sertifikasi yang dipublikasikan.</p>
        <?php endif; ?>
    </div>
</section>
