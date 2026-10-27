<?php

use yii\helpers\Html;

$this->title = 'Peraturan Jasa Konstruksi';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss(<<<'CSS'
.produk-hukum-public { background: #f3f6f4; padding: 24px 0 48px; min-height: 420px; }
.produk-hukum-public__inner { max-width: 1000px; margin: 0 auto; padding: 0 16px; }
.produk-hukum-public__filter { background: #fff; border-radius: 14px; padding: 14px 16px 12px; margin-bottom: 12px; }
.produk-hukum-public__label { display: block; color: #38443e; font-size: .84rem; margin-bottom: 7px; }
.produk-hukum-public__controls { display: flex; gap: 8px; align-items: stretch; }
.produk-hukum-public__select { flex: 1; min-width: 0; min-height: 44px; background-color: #f3f6f4; border: 1px solid #d1dcd5; border-radius: 9px; color: #30343a; }
.produk-hukum-public__search { width: 44px; min-width: 44px; border: 0; border-radius: 9px; background: #46524d; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 1.1rem; }
.produk-hukum-public__search:hover, .produk-hukum-public__search:focus { background: #303a35; color: #fff; }
.produk-hukum-public__report { background: #fff; border-radius: 14px; overflow: hidden; }
.produk-hukum-public__report-title { padding: 2px 16px 6px; margin: 0; color: #30343a; font-size: 1rem; font-weight: 600; border-bottom: 1px solid #e3e7e4; }
.produk-hukum-public__table-wrap { padding: 0 16px 12px; }
.produk-hukum-public__table { color: #38443e; margin-bottom: 0; }
.produk-hukum-public__table thead th { background: #f3f6f4; color: #30343a; font-weight: 600; white-space: nowrap; }
.produk-hukum-public__table tbody tr { border-color: #e3e7e4; }
.produk-hukum-public__actions { display: flex; flex-wrap: wrap; gap: 8px; }
.produk-hukum-public__action { --bs-btn-color: #46524d; --bs-btn-border-color: #aeb8b2; --bs-btn-hover-color: #fff; --bs-btn-hover-bg: #46524d; --bs-btn-hover-border-color: #46524d; --bs-btn-active-bg: #303a35; --bs-btn-active-border-color: #303a35; }
.produk-hukum-public__empty { color: #66716d; }
.produk-hukum-public__note { color: #66716d; font-size: .84rem; margin: 12px 0 0; }
CSS);
?>
<section class="produk-hukum-public">
    <div class="produk-hukum-public__inner">
        <h1 class="visually-hidden"><?= Html::encode($this->title) ?></h1>

        <?= Html::beginForm(['/peraturan-jasa-konstruksi/index'], 'get', ['class' => 'produk-hukum-public__filter']) ?>
            <?= Html::label('Pilih tahun', 'tahun-produk-hukum', ['class' => 'produk-hukum-public__label']) ?>
            <div class="produk-hukum-public__controls">
                <?= Html::dropDownList('tahun', $selectedYear, array_combine($years, $years) ?: [], [
                    'id' => 'tahun-produk-hukum',
                    'class' => 'form-select produk-hukum-public__select',
                    'prompt' => 'Pilih tahun',
                ]) ?>
                <button type="submit" class="produk-hukum-public__search" aria-label="Tampilkan dokumen" title="Tampilkan dokumen">
                    <i class="bi bi-search" aria-hidden="true"></i>
                </button>
            </div>
        <?= Html::endForm() ?>

        <section class="produk-hukum-public__report" aria-labelledby="produk-hukum-report-title">
            <h2 class="produk-hukum-public__report-title" id="produk-hukum-report-title">Daftar Dokumen</h2>
            <div class="table-responsive produk-hukum-public__table-wrap">
                <table class="table align-middle produk-hukum-public__table">
                    <thead>
                        <tr>
                            <th scope="col">Nama Produk Hukum</th>
                            <th scope="col">Dokumen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($documents as $document): ?>
                            <tr>
                                <th scope="row"><?= Html::encode($document->master->nama) ?></th>
                                <td>
                                    <div class="produk-hukum-public__actions">
                                        <?php if ($document->mime_type === 'application/pdf'): ?>
                                            <?= Html::a('Lihat', ['/peraturan-jasa-konstruksi/lihat', 'id' => $document->id], [
                                                'class' => 'btn btn-sm btn-outline-secondary produk-hukum-public__action',
                                                'target' => '_blank',
                                                'rel' => 'noopener',
                                            ]) ?>
                                        <?php endif; ?>
                                        <?= Html::a('Unduh', ['/peraturan-jasa-konstruksi/unduh', 'id' => $document->id], [
                                            'class' => 'btn btn-sm btn-outline-secondary produk-hukum-public__action',
                                        ]) ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (!$documents): ?>
                            <tr><td colspan="2" class="text-center py-4 produk-hukum-public__empty">Tidak ada dokumen publik untuk tahun yang dipilih.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
        <p class="produk-hukum-public__note">Dokumen produk hukum jasa konstruksi ditampilkan menurut tahun publikasi.</p>
    </div>
</section>
