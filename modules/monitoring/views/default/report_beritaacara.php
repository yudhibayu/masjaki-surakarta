<?php

use app\modules\monitoring\models\MonitoringTask;
use yii\helpers\Html;
use yii\i18n\Formatter;

$formatter = isset(Yii::$app->formatter) ? Yii::$app->formatter : new Formatter();
$formatter->nullDisplay = '-';
$rupSource = isset($task) && $task instanceof MonitoringTask ? $task->rupSource : null;

if (!isset($renderpdf)) {
	$renderpdf = null;
}

?>
<!DOCTYPE html>
<html>
<?php if (!$renderpdf) : ?>

	<head>
		<title>Berita Acara Monitoring Lapangan</title>
		<style>
			* {
				box-sizing: border-box;
			}

			body {
				width: 100%;
				height: 100%;
				margin: 0;
				padding: 0;
				background-color: #FAFAFA;
				font: 12pt "Times";
			}

			p {
				line-height: 1.6;
			}

			table tbody tr {
				height: 33px;
			}

			table th {
				font: 12pt "Times";
			}

			* {
				box-sizing: border-box;
				-moz-box-sizing: border-box;
			}

			.page {
				width: 215mm;
				min-height: 330mm;
				padding: 10mm;
				margin: 5mm auto;
				border: none;
				border-radius: 5px;
				background: white;
				box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
			}

			@page {
				size: 21.5cm 33.0cm;
				margin-top: 1cm;
				margin-left: 0cm;
				margin-right: 0cm;
				margin-bottom: 1cm;
			}

			@media print {
				h3 {
					margin: 3px;
				}

				html,
				body {
					size: 21.5cm 33.0cm;
				}

				.page {
					margin: 0;
					border: initial;
					border-radius: initial;
					width: initial;
					min-height: initial;
					box-shadow: initial;
					background: initial;
					page-break-after: always;

				}
			}
		</style>
	</head>
<?php endif; ?>

<body>
	<div class="page">
		<h3 style="text-align: center;">
			BERITA ACARA MONITORING LAPANGAN
			<br>PELAKSANAAN KEGIATAN TAHUN ANGGARAN <?= $task->fiscal_year;  ?>
			<br>PEMERINTAH KOTA SURAKARTA
		</h3>
		<br>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pada hari ini <?= $date['dayname'] ?> Tanggal <?= $date['day'] ?> Bulan <?= $date['month'] ?> Tahun <?= $date['year'] ?>, dilaksanakan Monitoring Lapangan dengan hasil sebagai berikut :</p>
		<table width="100%" border="0">
			<tr>
				<td valign="top" width="35%">1. Nama SKPD</td>
				<td valign="top">:</td>
				<td valign="top"><?= Html::encode($task->unit_name); ?></td>
			</tr>
			<tr>
				<td valign="top">2. Nama Paket Kegiatan</td>
				<td valign="top">:</td>
				<td valign="top"><?= Html::encode($task->name); ?></td>
			</tr>
			<tr>
				<td valign="top">3. Alamat Lokasi</td>
				<td valign="top">:</td>
				<td valign="top"><?= Html::encode($task->location_description); ?></td>
			</tr>
			<tr>
				<td valign="top">4. Sumber Anggaran</td>
				<td valign="top">:</td>
				<td valign="top"><?php
					$sourceOfFunds = isset($rupSource->sumberdana)
						? $rupSource->sumberdana
						: (isset($rupSource->sourceOfFunds->name) ? $rupSource->sourceOfFunds->name : '');
					echo Html::encode($sourceOfFunds);
				?></td>
			</tr>
			<!-- <tr>
					<td valign="top" style="padding-left: 3%;">a. APBN/DAK/DID</td>
					<td valign="top">:</td>
					<td valign="top">
					</td>
				</tr> -->
			<!-- <tr>
					<td valign="top" style="padding-left: 3%;">b. BANGUB/BANKEU</td>
					<td valign="top">:</td>
					<td valign="top">
					</td>
				</tr> -->
			<!-- <tr>
					<td valign="top" style="padding-left: 3%;">c. APBD</td>
					<td valign="top">:</td>
					<td valign="top">
					</td>
				</tr> -->
			<tr>
				<td valign="top">5. Metode Pengadaan</td>
				<td valign="top">:</td>
				<td valign="top"><?= Html::encode($rupSource->metodepengadaan ?? ''); ?></td>
			</tr>
			<tr>
				<td valign="top">6. Data Kontrak</td>
				<td valign="top"></td>
				<td valign="top"></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 3%;">a. Nomor & Tanggal Kontrak</td>
				<td valign="top">:</td>
				<td valign="top"><?= Html::encode($task->contract_number); ?> <?= $task->contract_date ? '/ ' . $formatter->asDate($task->contract_date) : '' ?></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 3%;">b. Nomor & Tanggal SPMK</td>
				<td valign="top">:</td>
				<td valign="top"><?= Html::encode($task->spmk_number); ?> <?= $task->spmk_date ? '/ ' . $formatter->asDate($task->spmk_date) : '' ?></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 3%;">c. Tanggal Mulai dan Akhir Kontrak</td>
				<td valign="top">:</td>
				<td valign="top"><?= $formatter->asDate($task->start_date) ?> s/d <?= $formatter->asDate($task->end_date) ?></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 3%;">d. Jangka Waktu Kontrak</td>
				<td valign="top">:</td>
				<td valign="top"><?= Html::encode($task->work_days); ?></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 3%;">e. Pelaksana Kontrak</td>
				<td valign="top">:</td>
				<td valign="top"><?= Html::encode($task->contractor_name); ?></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 3%;">f. Nilai Kontrak</td>
				<td valign="top">:</td>
				<td valign="top"><?= $formatter->asCurrency($task->final_price, 'IDR'); ?></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 3%;">g. Nomor Registrasi BPJS TK</td>
				<td valign="top">:</td>
				<td valign="top"><?= Html::encode($task->bpjstk_number); ?></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 3%;">h. Iuran BPJS TK</td>
				<td valign="top">:</td>
				<td valign="top"><?= $formatter->asCurrency($task->bpjstk_contrib_amount, 'IDR'); ?></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 3%;">i. Personel Terdaftar BPJS TK</td>
				<td valign="top">:</td>
				<td valign="top"><?= $formatter->asDecimal($task->bpjstk_number_workers_protected, 0); ?> orang</td>
			</tr>
			<tr>
				<td valign="top">7. Target (pada minggu ke-.........)</td>
				<td valign="top"></td>
				<td valign="top"></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 3%;">a. Fisik</td>
				<td valign="top">:</td>
				<td valign="top">................%</td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 3%;">b. Keuangan</td>
				<td valign="top">:</td>
				<td valign="top">Rp. ............................. : ..........%</td>
			</tr>
			<tr>
				<td valign="top">8. Realisasi (pada minggu ke-.........)</td>
				<td valign="top"></td>
				<td valign="top"></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 3%;">a. Fisik</td>
				<td valign="top">:</td>
				<td valign="top">................%</td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 3%;">b. Keuangan</td>
				<td valign="top">:</td>
				<td valign="top">Rp. ............................. : ..........%</td>
			</tr>
			<!-- <br> -->
			<tr>
				<td valign="top">9. Monitoring K3</td>
				<td valign="top"></td>
				<td valign="top"></td>
			</tr>
			<tr>
				<td valign="top" width="40%" style="padding-left: 3%;">a. Item Pekerjaan Beresiko Tinggi</td>
				<td valign="top">:</td>
				<td valign="top"><br> <br> <br></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 3%;">b. Item Pekerjaan Terjadi Kecelakaan</td>
				<td valign="top">:</td>
				<td valign="top"><br> <br> <br></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 3%;">
					c. Jumlah Kecelakaan Ringan
					<span>
						<small>
							<p style="margin: 0px;padding: 0px; text-align: justify;"><i>Terjadi luka ringan dan dapat segera kembali bekerja</i></p>
						</small>
					</span>
				</td>
				<td valign="top">:</td>
				<td valign="top"></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 3%;">
					d. Jumlah Kecelakaan Sedang
					<span>
						<small>
							<p style="margin: 0px;padding: 0px; text-align: justify;"><i>Membutuhkan Perawatan di RS dan dapat kembali bekerja setelah sembuh</i></p>
						</small>
					</span>
				</td>
				<td valign="top">:</td>
				<td valign="top"></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 3%;">
					e. Jumlah Kecelakaan Berat/MD
					<span>
						<small>
							<p style="margin: 0px;padding: 0px; text-align: justify;"><i>Terjadi cacat tetap dan tidak bisa kembali bekerja atau Meninggal Dunia</i></p>
						</small>
					</span>
				</td>
				<td valign="top">:</td>
				<td valign="top"></td>
			</tr>
			<tr>
				<td valign="top">10. Permasalahan</td>
				<td valign="top"></td>
				<td valign="top"></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 3%;">a. Hambatan</td>
				<td valign="top">:</td>
				<td valign="top"><br> <br> <br></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 3%;">b. Tindak Lanjut/Solusi</td>
				<td valign="top">:</td>
				<td valign="top"><br> <br> <br></td>
			</tr>
			<tr>
				<td valign="top">11. BAST</td>
				<td valign="top">:</td>
				<td valign="top">(Ada/Belum Ada) <sup>
						<smalll>pilih salah satu</smalll>
					</sup></td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 3%;">&nbsp;&nbsp;No. dan Tgl. BAST <sup>
						<smalll>isi jika sudah ada</smalll>
					</sup> </td>
				<td valign="top">:</td>
				<td valign="top"></td>
			</tr>
			<tr>
				<td valign="top">12. Catatan Penting</td>
				<td valign="top">:</td>
				<td valign="top"></td>
			</tr>
			<tr>
				<td valign="top" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
			</tr>
			<tr>
				<td valign="top">13. Kesimpulan Monitoring</td>
				<td valign="top">:</td>
				<td valign="top"></td>
			</tr>
			<tr>
				<td valign="top" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
			</tr>
		</table>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Demikian Berita Acara ini dibuat sesuai dengan keadaan pada saat dilaksanakan monitoring lapangan, sebagai bahan evaluasi dalam Pengendalian pelaksanaan kegiatan APBD Kota Surakarta Tahun Anggaran <?php $task->fiscal_year;  ?>.</p>

		<table width="100%" border="0">
			<tr>
				<th width=50%>PPK/Direksi/PTK/PPTK/Kontraktor/Pengawas</th>
				<th width=50% colspan="2">Pelaksana Monitoring Lapangan <br><?= 'Pemeriksa' ?></th>

			</tr>
			<tr>
				<th width=30%></th>
				<th width=30%>Nama</th>
				<th width=20%>Paraf / TTD</th>
			</tr>
			<tr>
				<td rowspan="2" style="text-align:center;">
					<br>
					<br>
					<br>

					..........................................

					<br>

				</td>

				<td>
					<ol>
						<?php
						// foreach ($petugas as $row) {
						// 	//echo "<li style='height:25px;'>".$row['tblpengguna_nama']."</li>";
						// 	echo "<li style='height:25px; text-align:center;'>".$row[0]." ________________</li>";
						// }
						foreach ($task->team->getUsersName() as $member) {
							echo "<li style='height:25px; text-align:center;'>" . $member . " </li>";
						}
						?>
					</ol>
				</td>
				<td style="text-align:center;">
					<ol>
						<?php
						foreach ($task->team->getUsersName() as $member) {
							echo '<li style="height:25px; text-align:center;list-style-type:none;">...............</li>';
						}
						?>
					</ol>
				</td>

			</tr>

		</table>
	</div>
</body>

</html>
