<?php

use app\modules\referencesMasjaki\models\DokumenPesertaSertifikasi;
use app\modules\referencesMasjaki\models\PesertaFasilitasiSertifikasi;
use yii\helpers\Html;

$this->title = $record->tenagaKerja->nama_lengkap . ' — ' . $record->kegiatan->nama_kegiatan;
$this->params['breadcrumbs'][] = ['label' => 'Keikutsertaan Sertifikasi TKK', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card"><div class="card-header"><h3><?= Html::encode($this->title) ?></h3></div><div class="card-body">
    <p><strong>Tenaga Kerja:</strong> <?= Html::a(Html::encode($record->tenagaKerja->nama_lengkap), ['/references-masjaki/tkk-tenaga-kerja/view', 'id' => $record->tenaga_kerja_id]) ?></p>
    <p><strong>Tahun Anggaran:</strong> <?= Html::encode($record->kegiatan->tahun_anggaran) ?></p>
    <p><strong>LSP:</strong> <?= Html::encode($record->kegiatan->lsp->nama_lsp) ?></p>
    <p><strong>Jabatan Kerja:</strong> <?= Html::encode($record->jabatan_kerja) ?></p>
    <p><strong>Jenjang:</strong> <?= Html::encode($record->jenjang_kualifikasi ?: '—') ?></p>
    <p><strong>Skema:</strong> <?= Html::encode($record->skema_sertifikasi) ?></p>
    <p><strong>Jenis Fasilitasi:</strong> <?= Html::encode(PesertaFasilitasiSertifikasi::JENIS[$record->jenis_fasilitasi] ?? $record->jenis_fasilitasi) ?></p>
    <p><strong>Status:</strong> <?= Html::encode(PesertaFasilitasiSertifikasi::STATUS[$record->status_sertifikasi] ?? $record->status_sertifikasi) ?></p>
    <p><strong>Nomor SKK:</strong> <?= Html::encode($record->nomor_skk ?: 'Belum terbit') ?></p>
    <p><strong>Tanggal Terbit SKK:</strong> <?= Html::encode($record->tanggal_terbit_skk ?: '—') ?></p>
    <p><strong>Masa Berlaku Hingga:</strong> <?= Html::encode($record->masa_berlaku_skk_hingga ?: '—') ?></p>
    <p><strong>Keterangan:</strong> <?= nl2br(Html::encode($record->keterangan ?: '—')) ?></p>
    <p><?= Html::a('Edit', ['update', 'id' => $record->id], ['class' => 'btn btn-primary']) ?>
       <?= Html::a('Unggah Dokumen', ['/references-masjaki/tkk-dokumen/upload', 'peserta_id' => $record->id], ['class' => 'btn btn-success']) ?></p>
    <h4>Dokumen Pendukung</h4>
    <div class="table-responsive"><table class="table table-striped"><thead><tr><th>Jenis</th><th>Nama File</th><th>Ukuran</th><th>Aksi</th></tr></thead><tbody>
    <?php foreach ($record->dokumen as $file): ?>
        <tr><td><?= Html::encode(DokumenPesertaSertifikasi::JENIS[$file->jenis_dokumen] ?? $file->jenis_dokumen) ?></td>
            <td><?= Html::encode($file->nama_file) ?></td><td><?= Yii::$app->formatter->asShortSize($file->file_size) ?></td>
            <td><?= Html::a('Unduh', ['/references-masjaki/tkk-dokumen/unduh', 'id' => $file->id], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                <?= Html::beginForm(['/references-masjaki/tkk-dokumen/delete', 'id' => $file->id], 'post', ['style' => 'display:inline']) ?>
                <?= Html::submitButton('Hapus', ['class' => 'btn btn-sm btn-danger', 'data-confirm' => 'Hapus dokumen ini?']) ?>
                <?= Html::endForm() ?></td></tr>
    <?php endforeach; ?>
    <?php if (!$record->dokumen): ?><tr><td colspan="4">Belum ada dokumen.</td></tr><?php endif; ?>
    </tbody></table></div>
</div></section>
