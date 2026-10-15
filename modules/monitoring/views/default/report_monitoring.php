<?php

use app\modules\monitoring\models\MonitoringTask;
use yii\helpers\Html;
use yii\i18n\Formatter;

$formatter = isset(Yii::$app->formatter) ? Yii::$app->formatter : new Formatter();
$formatter->nullDisplay = '';

// $task = new MonitoringTask();
// $progress = new MonitoringProgress();
if (!($task instanceof MonitoringTask)) {
	$task = new MonitoringTask();
}
if (!isset($renderpdf)) {
	$renderpdf = null;
}
if (!isset($qrcode)) {
	$qrcode = null;
}
if (!isset($gmap)) {
	$gmap = null;
}
if (!isset($is_spec)) {
	$is_spec = false;
}

/**
 * jenis 1 = tanpa laporan bulanan
 * jenis 2 = laporan satu bulan
 * jenis 3 = laporan semua bulan
 */
if (!isset($jenis)) {
	$jenis = 1;
}

?>
<!DOCTYPE html>
<html>

<head>
	<?php if (!$renderpdf) : ?>
		<title>Laporan Monitoring</title>
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
				font: 10pt "Tahoma";
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
				border: 1px #D3D3D3 solid;
				border-radius: 5px;
				background: white;
				box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
			}

			.gbr {
				object-fit: contain;
				width: 100%;
				height: 250px;

				padding: 0mm;
				margin: 1mm auto;
				border: 1px #D3D3D3 solid;
				border-radius: 5px;
				background: white;
				box-shadow: 0 0 5px rgba(0, 0, 0, 0.4);
				page-break-before: auto;
				/* 'always,' 'avoid,' 'left,' 'inherit,' or 'right' */
				page-break-after: auto;
				/* 'always,' 'avoid,' 'left,' 'inherit,' or 'right' */
				page-break-inside: avoid;
				/* or 'auto' */

			}

			.caption {
				font: 8pt "Tahoma";
				font-style: italic;
			}

			.column {
				float: left;
				width: 50%;
				padding: 4px;

			}

			.widecolumn {
				float: left;
				width: 100%;
				padding: 4px;

			}

			/* Clearfix (clear floats) */
			.row::after {
				content: "";
				clear: both;
				display: table;
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

				.row,
				.monitoring {
					page-break-inside: avoid;
					page-break-before: auto;
				}

				thead {
					display: table-header-group
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

				.gbr {
					object-fit: contain;
					width: 100%;
					height: 225px;

					padding: 0mm;
					margin: 1mm auto;

					border-radius: 5px;
					background: white;
					box-shadow: 0 0 5px rgba(0, 0, 0, 0.4);
				}

				.column {
					float: left;
					width: 50%;
					padding: 4px;

				}

				.widecolumn {
					float: left;
					width: 100%;
					padding: 4px;

				}

				/* Clearfix (clear floats) */
				.row::after {
					content: "";
					clear: both;
					display: table;
				}
			}
		</style>
	<?php endif; ?>
</head>

<body>
	<div class="page">

		<h3 style="text-align: center;">
			<?php
			$title = 'LAPORAN MONITORING LAPANGAN KOTA SURAKARTA';
			if ($is_spec) {
				$title = 'LAPORAN MONITORING SPESIFIKASI TEKNIS KOTA SURAKARTA';
			}
			?>
			<?= $title ?>
			<br><?php echo strtoupper($task->rup->namasatker); ?>
		</h3>

		<table width="100%" border="1" style="border-collapse: collapse;">
			<tr>
				<td valign="top">
					<div style="text-align: center;"><b style="font-size: 12px;">DATA PERENCANAAN</b></div>
					<hr />
					<table width="100%">
						<tr>
							<td valign="top" width="30%">SKPD</td>
							<td valign="top">:</td>
							<td valign="top"><?= isset($task->unit->name) ? Html::encode($task->unit->namecode) : Html::encode($task->unit_name); ?></td>
						</tr>
						<!--<tr>
							<td valign="top">Program</td>
							<td valign="top">:</td>
							<td valign="top"><?php //echo $datalokasi['tbllokasi_program'] 
												?></td>
						</tr>
						<tr>
							<td valign="top">Kegiatan</td>
							<td valign="top">:</td>
							<td valign="top"><?php //echo $datalokasi['tbllokasi_kegiatan'] 
												?></td>
						</tr> -->
						<tr>
							<td valign="top">Nama Paket</td>
							<td valign="top">:</td>
							<td valign="top"><?= Html::encode($task->name); ?></td>
						</tr>
						<tr>
							<td valign="top">Sumber Dana</td>
							<td valign="top">:</td>
							<td valign="top"><?= Html::encode(isset($task->rup->sumberdana) ? $task->rup->sumberdana : '-'); ?></td>
						</tr>
						<tr>
							<td valign="top">Pagu Anggaran</td>
							<td valign="top">:</td>
							<td valign="top"><?= $formatter->asCurrency($task->budget, 'IDR'); ?></td>
						</tr>
						<tr>
							<td valign="top">Metode Pemilihan Penyedia</td>
							<td valign="top">:</td>
							<td valign="top"><?= Html::encode(isset($task->rup->metodepengadaan) ? $task->rup->metodepengadaan : '-'); ?></td>
						</tr>
						<tr>
							<td valign="top">Jenis Pengadaan</td>
							<td valign="top">:</td>
							<td valign="top"><?= Html::encode(isset($task->rup->jenispengadaan) ? $task->rup->jenispengadaan : '-'); ?></td>
						</tr>
						<tr>
							<td valign="top">Tgl Pemilihan Awal</td>
							<td valign="top">:</td>
							<td valign="top"><?= $formatter->asDate($task->provider_selection_start_date) ?></td>
						</tr>
						<tr>
							<td valign="top">Tgl Pemilihan Akhir</td>
							<td valign="top">:</td>
							<td valign="top"><?= $formatter->asDate($task->provider_selection_end_date) ?></td>
						</tr>
						<tr>
							<td valign="top">Tgl Pekerjaan Awal</td>
							<td valign="top">:</td>
							<td valign="top"><?= $formatter->asDate($task->start_date) ?></td>
						</tr>
						<tr>
							<td valign="top">Tgl Pekerjaan Akhir</td>
							<td valign="top">:</td>
							<td valign="top"><?= $formatter->asDate($task->end_date) ?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td valign="top">
					<div style="text-align: center;">
						<b style="font-size: 12px;">DATA PELAKSANAAN LAPANGAN</b>
						<hr />
					</div>
					<table width="100%">
						<tr>
							<td colspan="3" style="text-align: center;">
								<?php
								if ($gmap) {
									echo Html::img($gmap, [
										'style' => 'border:1px solid #000'
									]);
								}
								?>
								<br>
								<small><i>Scan untuk membuka Peta dan Pinpoint Lokasi</i></small>
								<br>
							</td>
						</tr>
						<tr>
							<td valign="top" width="30%">Koordinat Alamat</td>
							<td valign="top">:</td>
							<td valign="top"><?= $task->latlng ? Html::encode($task->latlng) : '-'; ?></td>
						</tr>
						<tr>
							<td valign="top">Alamat Lokasi</td>
							<td valign="top">:</td>
							<td valign="top"><?= $task->location_description ? Html::encode($task->location_description) : '-'; ?></td>
						</tr>
						<tr>
							<td valign="top">Nama Pemenang</td>
							<td valign="top">:</td>
							<td valign="top"><?= $task->contractor_name ? Html::encode($task->contractor_name) : '-'; ?></td>
						</tr>
						<tr>
							<td valign="top">Nilai Kontrak</td>
							<td valign="top">:</td>
							<td valign="top"><?= $task->final_price ? $formatter->asCurrency($task->final_price, 'IDR') : '-'; ?></td>
						</tr>
						<tr>
							<td valign="top">Tanggal & Nomor Kontrak</td>
							<td valign="top">:</td>
							<td valign="top"><?= $task->contract_date ? $formatter->asDate($task->contract_date) : '-' ?> - <?= $task->contract_number ? Html::encode($task->contract_number) : '-'; ?></td>
						</tr>
						<tr>
							<td valign="top">Tanggal & Nomor SPMK</td>
							<td valign="top">:</td>
							<td valign="top"><?= $task->spmk_date ? $formatter->asDate($task->spmk_date) : '-' ?> - <?= $task->spmk_number ? Html::encode($task->spmk_number) : '-'; ?></td>
						</tr>
						<?php if (($jenis != 1) && ($jenis != 5)) : ?>
							<tr>
								<td valign="top">Range Progress Pekerjaan</td>
								<td valign="top">:</td>
								<?php
								$minrange = 0;
								$maxrange = 0;
								if ($jenis == 2) {
									$minrange = $task->getProgressMinRange($month, 'month');
									$maxrange = $task->getProgressMaxRange($month, 'month');
								} else if ($jenis == 3) {
									$minrange = $task->getProgressMinRange($month, 'all');
									$maxrange = $task->getProgressMaxRange($month, 'all');
								} else if ($jenis == 4) {
									$minrange = $task->getProgressMinRange($week, 'week');
									$maxrange = $task->getProgressMaxRange($week, 'week');
								}
								?>
								<td valign="top"><?= $formatter->asDecimal($minrange, 2) . '%'; ?><?= ' - ' . $formatter->asDecimal($maxrange, 2) . '%'; ?></td>
							</tr>
						<?php endif; ?>
						<tr>
							<td valign="top">Tanggal Selesai Pekerjaan/PHO</td>
							<td valign="top">:</td>
							<td valign="top">
								<?php
								if ($task->finally_finished_date) {
									echo $formatter->asDate($task->spmk_date);
								} else {
									echo '-';
								}
								?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<?php if (($task->is_bpjstk == MonitoringTask::USE_BPJSTK) && !$is_spec) : ?>
				<tr>
					<td valign="top">
						<div style="text-align: center;">
							<b style="font-size: 12px;">DATA K3/BPJS TK</b>
							<hr />
						</div>
						<table width="100%">
							<tr>
								<td valign="top" width="30%">Nomor Registrasi BPJSTK</td>
								<td valign="top">:</td>
								<td valign="top"><?= $task->bpjstk_number ? Html::encode($task->bpjstk_number) : '-'; ?></td>
							</tr>
							<tr>
								<td valign="top">Besar Iuran</td>
								<td valign="top">:</td>
								<td valign="top"><?= $task->bpjstk_contrib_amount ? $formatter->asCurrency($task->bpjstk_contrib_amount, 'IDR') : '-'; ?></td>
							</tr>
							<tr>
								<td valign="top">Jumlah Tenaga Terlindungi BPJSTK</td>
								<td valign="top">:</td>
								<td valign="top"><?= $task->bpjstk_number_workers_protected ? $formatter->asDecimal($task->bpjstk_number_workers_protected, 0) : '-'; ?> orang</td>
							</tr>
						</table>
					</td>
				</tr>
			<?php endif; ?>
			<tr>
				<td valign="top">
					<div style="text-align: center;">
						<b style="font-size: 12px;">DATA PETUGAS</b>
					</div>
					<table width="100%" border="1" style="border-collapse: collapse;">
						<tr style="text-align: center;">
							<td><?= isset($task->team->name) ? Html::encode($task->team->name) : Html::encode($task->team_name) ?></td>
							<td>Perencana</td>
							<td>Pengawas</td>
						</tr>
						<tr>
							<td>
								<ul>
									<?php
									foreach ($task->team->getUsersName() as $member) {
										echo "<li style='height:25px;'>" . $member . "</li>";
									}
									?>
								</ul>
							</td>
							<td>
								<ul>
									<?php
									foreach ($task->childPlanner as $planner) {
										echo "<li style='height:25px;'>" . $planner->planner_name . "</li>";
									}
									?>
								</ul>
							</td>
							<td>
								<ul>
									<?php
									foreach ($task->childSupervisor as $supervisor) {
										echo "<li style='height:25px;'>" . $supervisor->supervisor_name . "</li>";
									}
									?>
								</ul>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<?php if ($jenis == 2) : ?>
			<?php
			echo $this->render('report_monitoring_month_progresses', [
				'task' => $task,
				'month' => $month,
				'week' => $week,
				'day' => $day,
				'is_spec' => $is_spec,
			]);
			?>
		<?php endif; ?>

		<?php if ($jenis == 3) : ?>
			<?php
			foreach ($task->getMonthMonitoringAvaiable() as $monthavailable) {
				echo $this->render('report_monitoring_month_progresses', [
					'task' => $task,
					'month' => $monthavailable,
					'week' => $week,
					'day' => $day,
					'is_spec' => $is_spec,
				]);
			}
			?>
		<?php endif; ?>

		<?php if ($jenis == 4) : ?>
			<?php
			echo $this->render('report_monitoring_week_progresses', [
				'task' => $task,
				'month' => $month,
				'week' => $week,
				'day' => $day,
				'is_spec' => $is_spec,
			]);
			?>
		<?php endif; ?>

		<?php if ($jenis == 5) : ?>
			<?php
			echo $this->render('report_monitoring_day_progresses', [
				'task' => $task,
				'month' => $month,
				'week' => $week,
				'day' => $day,
				'is_spec' => $is_spec,
			]);
			?>
		<?php endif; ?>
	</div>
</body>

</html>