<?php

use yii\helpers\Html;

$this->title = $record->nama_kegiatan;
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Fasilitasi Sertifikasi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card"><div class="card-header"><h3><?= Html::encode($this->title) ?></h3></div><div class="card-body">
    <p><strong>Tahun Anggaran:</strong> <?= Html::encode($record->tahun_anggaran) ?></p>
    <p><strong>Tanggal:</strong> <?= Html::encode($record->tanggal_mulai) ?> – <?= Html::encode($record->tanggal_selesai) ?></p>
    <p><strong>LSP:</strong> <?= Html::encode($record->lsp->nama_lsp) ?></p>
    <p><strong>Lokasi:</strong> <?= Html::encode($record->lokasi_kegiatan ?: '—') ?></p>
    <p><strong>Keterangan:</strong> <?= nl2br(Html::encode($record->keterangan ?: '—')) ?></p>
    <p><strong>Publikasi:</strong> <?= $record->is_public ? 'Tampil di web umum' : 'Belum diterbitkan' ?></p>
    <p><?= Html::a('Edit', ['update', 'id' => $record->id], ['class' => 'btn btn-primary']) ?>
       <?= Html::a('Tambah Peserta', ['/references-masjaki/tkk-peserta/create', 'kegiatan_id' => $record->id], ['class' => 'btn btn-success']) ?></p>
    <?= Html::beginForm(['toggle-public', 'id' => $record->id], 'post') ?>
        <?= Html::submitButton($record->is_public ? 'Cabut Publikasi' : 'Terbitkan di Web Umum', [
            'class' => $record->is_public ? 'btn btn-outline-danger mb-3' : 'btn btn-outline-primary mb-3',
            'data-confirm' => $record->is_public
                ? 'Cabut publikasi kegiatan ini dari web umum?' : 'Terbitkan rekap kegiatan ini di web umum?',
        ]) ?>
    <?= Html::endForm() ?>
    <h4>Peserta (<?= count($participants) ?>)</h4>
    <div class="table-responsive"><table class="table table-striped"><thead><tr><th>Nama</th><th>Jabatan</th><th>Skema</th><th>Status</th><th></th></tr></thead><tbody>
    <?php foreach ($participants as $item): ?>
        <tr><td><?= Html::encode($item->tenagaKerja->nama_lengkap) ?></td><td><?= Html::encode($item->jabatan_kerja) ?></td>
            <td><?= Html::encode($item->skema_sertifikasi) ?></td><td><?= Html::encode($item->status_sertifikasi) ?></td>
            <td><?= Html::a('Detail', ['/references-masjaki/tkk-peserta/view', 'id' => $item->id]) ?></td></tr>
    <?php endforeach; ?>
    <?php if (!$participants): ?><tr><td colspan="5">Belum ada peserta.</td></tr><?php endif; ?>
    </tbody></table></div>
</div></section>
