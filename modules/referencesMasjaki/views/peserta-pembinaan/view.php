<?php

use yii\helpers\Html;
use app\modules\referencesMasjaki\models\MasyarakatJasaKonstruksi;

$this->title = 'Detail Peserta Pembinaan';
$this->params['breadcrumbs'][] = ['label' => 'Peserta Pembinaan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$name = $record->businessEntity ? $record->businessEntity->company_name : ($record->masyarakatJasaKonstruksi ? $record->masyarakatJasaKonstruksi->nama : '—');
?>
<section class="card"><div class="card-header d-flex justify-content-between align-items-center"><h3 class="mb-0">Detail Peserta</h3><div class="d-flex gap-2"><?= Html::a('Ubah Peserta', ['update', 'id' => $record->id], ['class' => 'btn btn-outline-primary']) ?><?= Html::a('Isi / Ubah Hasil', ['/references-masjaki/hasil-pembinaan/update', 'peserta_id' => $record->id], ['class' => 'btn btn-primary']) ?></div></div>
<div class="card-body">
    <p><strong>Kegiatan:</strong> <?= Html::encode($record->kegiatan ? $record->kegiatan->nama_kegiatan : '—') ?></p>
    <p><strong>Tahun:</strong> <?= Html::encode($record->kegiatan ? $record->kegiatan->tahun_anggaran : '—') ?></p>
    <p><strong>Jenis Peserta:</strong> <?= $record->id_business_entity ? 'BUJK' : ($record->masyarakatJasaKonstruksi && $record->masyarakatJasaKonstruksi->jenis_subjek === MasyarakatJasaKonstruksi::KELOMPOK ? 'Kelompok' : 'Masyarakat') ?></p>
    <p><strong>Nama:</strong> <?= Html::encode($name) ?></p>
    <hr>
    <h4>Hasil Pembinaan</h4>
    <p><strong>Hasil Capaian:</strong> <?= nl2br(Html::encode($record->hasil && $record->hasil->hasil_capaian ? $record->hasil->hasil_capaian : 'Belum diisi')) ?></p>
    <p><strong>Memperoleh Sertifikat:</strong> <?= !$record->hasil || $record->hasil->memperoleh_sertifikat === null ? 'Belum diisi' : ($record->hasil->memperoleh_sertifikat ? 'Ya' : 'Tidak') ?></p>
    <p><strong>Tindak Lanjut:</strong> <?= nl2br(Html::encode($record->hasil && $record->hasil->keterangan_tindak_lanjut ? $record->hasil->keterangan_tindak_lanjut : 'Belum diisi')) ?></p>
</div></section>
