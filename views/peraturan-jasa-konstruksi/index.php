<?php

use yii\helpers\Html;

$this->title = 'Peraturan Jasa Konstruksi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container mt-4">
    <div class="card shadow-sm border-0 overflow-hidden" style="border-radius: 12px;">
        <div class="card-header pt-5 px-5 py-3">
            <h3 class="mb-2"><?= Html::encode($this->title) ?></h3>
            <p class="mb-0">Dokumen produk hukum jasa konstruksi menurut tahun.</p>
        </div>
        <div class="card-body">
            <?= Html::beginForm(['/peraturan-jasa-konstruksi/index'], 'get', ['class' => 'row g-2 mb-3']) ?>
                <div class="col-auto"><?= Html::label('Tahun', 'tahun-produk-hukum', ['class' => 'form-label']) ?></div>
                <div class="col-auto"><?= Html::dropDownList('tahun', $selectedYear, array_combine($years, $years) ?: [], ['id' => 'tahun-produk-hukum', 'class' => 'form-select']) ?></div>
                <div class="col-auto"><?= Html::submitButton('Tampilkan', ['class' => 'btn btn-primary']) ?></div>
            <?= Html::endForm() ?>
            <div class="table-responsive">
                <table class="table table-light table-striped table-hover">
                    <thead><tr><th>Nama Produk Hukum</th><th>Dokumen</th></tr></thead>
                    <tbody>
                        <?php foreach ($documents as $document): ?>
                            <tr>
                                <th><?= Html::encode($document->master->nama) ?></th>
                                <td>
                                    <?php if ($document->mime_type === 'application/pdf'): ?>
                                        <?= Html::a('Lihat', ['/peraturan-jasa-konstruksi/lihat', 'id' => $document->id], ['class' => 'btn btn-sm btn-outline-primary', 'target' => '_blank', 'rel' => 'noopener']) ?>
                                    <?php endif; ?>
                                    <?= Html::a('Unduh', ['/peraturan-jasa-konstruksi/unduh', 'id' => $document->id], ['class' => 'btn btn-sm btn-primary']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (!$documents): ?>
                            <tr><td colspan="2" class="text-center">Tidak ada dokumen publik untuk tahun yang dipilih.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
