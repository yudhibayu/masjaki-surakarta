<?php

use app\modules\monitoring\models\TertibChecklist;
use app\modules\monitoring\models\TertibPengawasan;
use app\modules\monitoring\models\TertibTemuan;
use app\modules\monitoring\models\TertibTindakLanjut;
use yii\helpers\Html;

/* @var $model app\modules\monitoring\models\TertibPengawasan */

$this->title = 'Detail Pengawasan Tertib';
$this->params['breadcrumbs'][] = ['label' => 'Rekap Pengawasan Tertib', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$task = $model->monitoringTask;
?>
<div class="tertib-view">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
        <div>
            <h1 class="h3 mb-1"><?= Html::encode($this->title) ?></h1>
            <div class="text-muted"><?= Html::encode($task ? $task->name : 'Paket tidak tersedia') ?></div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <?= Html::a('Ubah', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Hapus', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-outline-danger',
                'data-method' => 'post',
                'data-confirm' => 'Hapus kegiatan pengawasan ini? Data yang memiliki riwayat tidak dapat dihapus.',
            ]) ?>
            <?= Html::a('Kembali', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3"><div class="text-muted small">Tahun anggaran</div><strong><?= Html::encode($task ? $task->fiscal_year : '—') ?></strong></div>
                <div class="col-md-3"><div class="text-muted small">Tanggal pengawasan</div><strong><?= Yii::$app->formatter->asDate($model->tanggal_pengawasan) ?></strong></div>
                <div class="col-md-3"><div class="text-muted small">Metode</div><strong><?= Html::encode(TertibPengawasan::metodeOptions()[$model->metode_pengawasan] ?? $model->metode_pengawasan) ?></strong></div>
                <div class="col-md-3"><div class="text-muted small">Status publikasi</div><strong><?= $model->is_public ? 'Dipublikasikan' : 'Internal' ?></strong></div>
                <div class="col-12"><div class="text-muted small">Uraian kegiatan (internal)</div><div><?= nl2br(Html::encode($model->uraian)) ?></div></div>
            </div>
        </div>
    </div>

    <section class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="h5 mb-0">Checklist Pengawasan</h2>
            <?= Html::a('Tambah item', ['checklist-create', 'pengawasanId' => $model->id], ['class' => 'btn btn-sm btn-outline-primary']) ?>
        </div>
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead><tr><th>Persyaratan atau dokumen</th><th>Hasil</th><th>Catatan</th><th></th></tr></thead>
                <tbody>
                    <?php if ($model->checklists): foreach ($model->checklists as $item): ?>
                        <tr>
                            <td><?= Html::encode($item->nama_persyaratan) ?></td>
                            <td><?= Html::encode(TertibChecklist::statusOptions()[$item->status_pemeriksaan] ?? $item->status_pemeriksaan) ?></td>
                            <td><?= Html::encode($item->catatan) ?></td>
                            <td class="text-nowrap">
                                <?= Html::a('Ubah', ['checklist-update', 'id' => $item->id], ['class' => 'btn btn-sm btn-outline-secondary']) ?>
                                <?= Html::a('Hapus', ['checklist-delete', 'id' => $item->id], ['class' => 'btn btn-sm btn-outline-danger', 'data-method' => 'post', 'data-confirm' => 'Hapus item checklist ini?']) ?>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="4" class="text-muted text-center py-3">Belum ada item checklist.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="h5 mb-0">Temuan dan Rekomendasi</h2>
            <?= Html::a('Tambah temuan', ['temuan-create', 'pengawasanId' => $model->id], ['class' => 'btn btn-sm btn-outline-primary']) ?>
        </div>
        <div class="card-body">
            <?php if ($model->findings): foreach ($model->findings as $finding): ?>
                <article class="border rounded p-3 mb-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                        <div>
                            <h3 class="h6 mb-1"><?= Html::encode($finding->kategori) ?></h3>
                            <div class="small text-muted">
                                Prioritas: <?= Html::encode(TertibTemuan::prioritasOptions()[$finding->prioritas] ?? $finding->prioritas) ?> ·
                                <?= Html::encode(TertibTemuan::statusOptions()[$finding->status_tindak_lanjut] ?? $finding->status_tindak_lanjut) ?> ·
                                <?= $finding->is_public ? 'Jumlah masuk rekap publik' : 'Internal' ?>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <?= Html::a('Catat tindak lanjut', ['tindak-lanjut-create', 'temuanId' => $finding->id], ['class' => 'btn btn-sm btn-outline-success']) ?>
                            <?= Html::a('Ubah', ['temuan-update', 'id' => $finding->id], ['class' => 'btn btn-sm btn-outline-secondary']) ?>
                            <?= Html::a('Hapus', ['temuan-delete', 'id' => $finding->id], ['class' => 'btn btn-sm btn-outline-danger', 'data-method' => 'post', 'data-confirm' => 'Hapus temuan ini? Temuan dengan riwayat tindak lanjut tetap dipertahankan.']) ?>
                        </div>
                    </div>
                    <p class="mt-3 mb-2"><?= nl2br(Html::encode($finding->uraian)) ?></p>
                    <?php if ($finding->rekomendasi !== ''): ?>
                        <p class="mb-2"><strong>Rekomendasi:</strong> <?= nl2br(Html::encode($finding->rekomendasi)) ?></p>
                    <?php endif; ?>
                    <?php if ($finding->catatan_internal !== ''): ?>
                        <p class="small text-muted mb-2"><strong>Catatan internal:</strong> <?= nl2br(Html::encode($finding->catatan_internal)) ?></p>
                    <?php endif; ?>
                    <h4 class="h6 mt-3">Riwayat tindak lanjut</h4>
                    <?php if ($finding->tindakLanjuts): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($finding->tindakLanjuts as $followUp): ?>
                                <li class="list-group-item px-0">
                                    <div class="d-flex flex-wrap justify-content-between gap-2">
                                        <strong><?= Yii::$app->formatter->asDate($followUp->tanggal_tindak_lanjut) ?></strong>
                                        <span class="badge <?= $followUp->status === TertibTindakLanjut::SELESAI ? 'text-bg-success' : 'text-bg-warning' ?>">
                                            <?= Html::encode(TertibTindakLanjut::statusOptions()[$followUp->status] ?? $followUp->status) ?>
                                        </span>
                                    </div>
                                    <div><?= nl2br(Html::encode($followUp->uraian_tindakan)) ?></div>
                                    <?php if ($followUp->hasil_verifikasi): ?><div class="small text-muted">Verifikasi: <?= nl2br(Html::encode($followUp->hasil_verifikasi)) ?></div><?php endif; ?>
                                    <?php if ($followUp->catatan_internal): ?><div class="small text-muted">Catatan internal: <?= nl2br(Html::encode($followUp->catatan_internal)) ?></div><?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="small text-muted mb-0">Belum ada riwayat tindak lanjut.</p>
                    <?php endif; ?>
                </article>
            <?php endforeach; else: ?>
                <p class="text-muted mb-0">Belum ada temuan pada kegiatan ini.</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="h5 mb-0">Dokumen Internal</h2>
            <?= Html::a('Unggah dokumen', ['dokumen-upload', 'id' => $model->id], ['class' => 'btn btn-sm btn-outline-primary']) ?>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Jenis</th><th>Nama berkas</th><th>Ukuran</th><th></th></tr></thead>
                <tbody>
                    <?php if ($model->documents): foreach ($model->documents as $document): ?>
                        <tr>
                            <td><?= Html::encode($document->jenis_dokumen) ?></td>
                            <td><?= Html::encode($document->nama_file) ?></td>
                            <td><?= Yii::$app->formatter->asShortSize($document->file_size) ?></td>
                            <td class="text-nowrap">
                                <?= Html::a('Unduh', ['dokumen-download', 'id' => $document->id], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                                <?= Html::a('Hapus', ['dokumen-delete', 'id' => $document->id], ['class' => 'btn btn-sm btn-outline-danger', 'data-method' => 'post', 'data-confirm' => 'Hapus dokumen ini?']) ?>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="4" class="text-muted text-center py-3">Belum ada dokumen pendukung.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
