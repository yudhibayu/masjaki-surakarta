<?php

use yii\helpers\Html;
use app\modules\referencesMasjaki\models\MasyarakatJasaKonstruksi;

$this->title = $record->nama_kegiatan;
$this->params['breadcrumbs'][] = ['label' => 'Kegiatan Pembinaan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$date = function ($value) { return $value ? Yii::$app->formatter->asDate($value, 'php:d-m-Y') : '—'; };
?>
<section class="card mb-3"><div class="card-header d-flex justify-content-between align-items-center">
    <div><h3 class="mb-1"><?= Html::encode($record->nama_kegiatan) ?></h3><div><?= Html::encode($record->tahun_anggaran . ' — ' . ($record->jenisKegiatan ? $record->jenisKegiatan->nama : '')) ?></div></div>
    <div class="d-flex gap-2">
        <?= Html::a('Ubah', ['update', 'id' => $record->id], ['class' => 'btn btn-outline-primary']) ?>
        <?= Html::a($record->is_published ? 'Cabut Publikasi' : 'Terbitkan', ['publish', 'id' => $record->id], ['class' => 'btn btn-outline-secondary', 'data-method' => 'post', 'data-confirm' => 'Ubah status publikasi kegiatan?']) ?>
    </div>
</div><div class="card-body">
    <p><strong>Tanggal:</strong> <?= Html::encode($date($record->tanggal_mulai)) ?> – <?= Html::encode($date($record->tanggal_selesai)) ?></p>
    <p><strong>Lokasi:</strong> <?= Html::encode($record->lokasi ?: '—') ?></p>
    <p><strong>Penyelenggara:</strong> <?= Html::encode($record->penyelenggara ?: '—') ?></p>
    <p><strong>Publikasi:</strong> <?= $record->is_published ? 'Dipublikasikan' : 'Draft' ?></p>
    <?php if ($record->keterangan): ?><p><?= nl2br(Html::encode($record->keterangan)) ?></p><?php endif; ?>
    <div class="d-flex gap-2 flex-wrap">
        <?= Html::a('Tambah Peserta', ['/references-masjaki/peserta-pembinaan/create', 'kegiatan_id' => $record->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Dokumen Pendukung', ['/references-masjaki/dokumen-pembinaan/index', 'kegiatan_id' => $record->id], ['class' => 'btn btn-outline-primary']) ?>
    </div>
</div></section>
<section class="card"><div class="card-header"><h4>Peserta dan Hasil Pembinaan</h4></div><div class="card-body table-responsive">
    <table class="table table-striped align-middle"><thead><tr><th>No.</th><th>Jenis Peserta</th><th>Nama</th><th>Hasil Capaian</th><th>Sertifikat</th><th></th></tr></thead><tbody>
    <?php foreach ($participants as $i => $participant):
        $name = $participant->businessEntity ? $participant->businessEntity->company_name : ($participant->masyarakatJasaKonstruksi ? $participant->masyarakatJasaKonstruksi->nama : '—'); ?>
        <tr><td><?= $i + 1 ?></td><td><?= $participant->id_business_entity ? 'BUJK' : ($participant->masyarakatJasaKonstruksi && $participant->masyarakatJasaKonstruksi->jenis_subjek === MasyarakatJasaKonstruksi::KELOMPOK ? 'Kelompok' : 'Masyarakat') ?></td><td><?= Html::encode($name) ?></td>
            <td><?= Html::encode($participant->hasil && $participant->hasil->hasil_capaian ? $participant->hasil->hasil_capaian : 'Belum diisi') ?></td>
            <td><?= !$participant->hasil || $participant->hasil->memperoleh_sertifikat === null ? 'Belum diisi' : ($participant->hasil->memperoleh_sertifikat ? 'Ya' : 'Tidak') ?></td>
            <td><?= Html::a('Detail', ['/references-masjaki/peserta-pembinaan/view', 'id' => $participant->id], ['class' => 'btn btn-sm btn-outline-secondary']) ?></td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$participants): ?><tr><td colspan="6" class="text-muted">Belum ada peserta.</td></tr><?php endif; ?>
    </tbody></table>
</div></section>
