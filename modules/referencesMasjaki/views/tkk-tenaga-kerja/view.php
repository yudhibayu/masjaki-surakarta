<?php

use yii\helpers\Html;

$this->title = $record->nama_lengkap;
$this->params['breadcrumbs'][] = ['label' => 'Tenaga Kerja Konstruksi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card"><div class="card-header"><h3><?= Html::encode($this->title) ?></h3></div><div class="card-body">
    <p><strong>NIK:</strong> <?= Html::encode($record->nik) ?></p>
    <p><strong>Jenis Kelamin:</strong> <?= $record->jenis_kelamin === 'L' ? 'Laki-laki' : ($record->jenis_kelamin === 'P' ? 'Perempuan' : '—') ?></p>
    <p><strong>Tanggal Lahir:</strong> <?= Html::encode($record->tanggal_lahir ?: '—') ?></p>
    <p><strong>Alamat:</strong> <?= nl2br(Html::encode($record->alamat ?: '—')) ?></p>
    <p><strong>Nomor Telepon:</strong> <?= Html::encode($record->nomor_telepon ?: '—') ?></p>
    <p><strong>Pendidikan:</strong> <?= Html::encode($record->pendidikan_terakhir ?: '—') ?></p>
    <p><strong>Asal Instansi:</strong> <?= Html::encode($record->asal_instansi ?: '—') ?></p>
    <p><?= Html::a('Edit', ['update', 'id' => $record->id], ['class' => 'btn btn-primary']) ?>
       <?= Html::a('Tambah Keikutsertaan', ['/references-masjaki/tkk-peserta/create', 'tenaga_kerja_id' => $record->id], ['class' => 'btn btn-success']) ?></p>
    <h4>Riwayat Keikutsertaan</h4>
    <div class="table-responsive"><table class="table table-striped"><thead><tr><th>Tahun</th><th>Kegiatan</th><th>Jabatan</th><th>Skema</th><th>Status</th><th></th></tr></thead><tbody>
    <?php foreach ($history as $item): ?>
        <tr><td><?= Html::encode($item->kegiatan->tahun_anggaran) ?></td><td><?= Html::encode($item->kegiatan->nama_kegiatan) ?></td>
            <td><?= Html::encode($item->jabatan_kerja) ?></td><td><?= Html::encode($item->skema_sertifikasi) ?></td>
            <td><?= Html::encode($item->status_sertifikasi) ?></td><td><?= Html::a('Detail', ['/references-masjaki/tkk-peserta/view', 'id' => $item->id]) ?></td></tr>
    <?php endforeach; ?>
    <?php if (!$history): ?><tr><td colspan="6">Belum ada keikutsertaan.</td></tr><?php endif; ?>
    </tbody></table></div>
</div></section>
