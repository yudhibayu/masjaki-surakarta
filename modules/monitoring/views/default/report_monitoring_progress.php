<?php

use app\modules\monitoring\models\MonitoringProgress;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\i18n\Formatter;

if (!($progress instanceof MonitoringProgress)) {
	$progress = new MonitoringProgress();
}
$task = $progress->monitoringTask;

$formatter = isset(Yii::$app->formatter) ? Yii::$app->formatter : new Formatter();
if (isset(Yii::$app->indodatetime)) {
	$indodatetime = Yii::$app->indodatetime;
	$monthname = $indodatetime->getBulan($progress->date_month);
} else {
	$indodatetime = null;
	$monthname = date('F');
}

?>
<br>
<table class="monitoring-progress" width="100%" border="1" style="border-collapse: collapse;">
	<tr>
		<td colspan="2">
			<div style="text-align: center;"><b style="font-size: 12px;">MONITORING TANGGAL <?= strtoupper(Yii::$app->formatter->asDate($progress->progress_date)); ?> (MINGGU KE <?= Html::encode($progress->order_no); ?>)</b></div>
		</td>
	</tr>
	<tr>
		<td width="50%" valign="top">
			<div style="text-align: center;">
				<b style="font-size: 12px;">REALISASI FISIK DAN KEUANGAN</b>
			</div>
			<hr />
			<?php
			$harga_kontrak = $task->final_price;
			$targetkeu_uang = $progress->planned_financial_budget;
			$realisasikeu_uang = $progress->financial_budget;
			$percenttargetkeu = 0;
			$percentrealisasikeu = 0;
			if ((float)$harga_kontrak > 0) {
				$percenttargetkeu = (float)$targetkeu_uang / (float)$harga_kontrak * 100;
				$percentrealisasikeu = (float)$realisasikeu_uang / (float)$harga_kontrak * 100;
			}
			?>
			<table width="100%">
				<tr>
					<td valign="top" width="45%">Minggu ke</td>
					<td valign="top">:</td>
					<td valign="top"><?= Html::encode($progress->order_no); ?> </td>
				</tr>
				<tr>
					<td valign="top">Status Progres Keuangan</td>
					<td valign="top">:</td>
					<td valign="top">
						<?php
						if ((float)$targetkeu_uang > (float)$realisasikeu_uang) {
							echo "Target Belum Tercapai";
						} elseif ((float)$targetkeu_uang < (float)$realisasikeu_uang) {
							echo "Realisasi Melebihi Target";
						} elseif ((float)$targetkeu_uang == 0 && ((float)$realisasikeu_uang == 0)) {
							echo "Belum Ada Progress";
						} else {
							echo "Tercapai Sesuai Target";
						}
						?>
					</td>
				</tr>
				<tr>
					<td valign="top">Status Progres Fisik</td>
					<td valign="top">:</td>
					<td valign="top">
						<?php
						if ((float)$progress->planned_physical_percent > (float)$progress->physical_percent) {
							echo "Target Belum Tercapai";
						} elseif ((float)$progress->planned_physical_percent < (float)$progress->physical_percent) {
							echo "Realisasi Melebihi Target";
						} elseif ((float)$progress->planned_physical_percent == 0 && (float)$progress->physical_percent == 0) {
							echo "Belum Ada Progress";
						} else {
							echo "Tercapai Sesuai Target";
						}
						?>
					</td>
				</tr>
				<tr>
					<td valign="top" width="35%">Target Keuangan</td>
					<td valign="top">:</td>
					<td valign="top"><?= $formatter->asDecimal($percenttargetkeu, 2); ?> % (<?= $formatter->asCurrency($targetkeu_uang, 'IDR'); ?>)</td>
				</tr>
				<tr>
					<td valign="top">Realisasi Keuangan</td>
					<td valign="top">:</td>
					<td valign="top"><?= $formatter->asDecimal($percentrealisasikeu, 2); ?> % (<?= $formatter->asCurrency($realisasikeu_uang, 'IDR'); ?>)</td>
				</tr>
				<tr>
					<td valign="top">Target Fisik</td>
					<td valign="top">:</td>
					<td valign="top"><?= $formatter->asDecimal($progress->planned_physical_percent, 2); ?> %</td>
				</tr>
				<tr>
					<td valign="top">Realisasi Fisik</td>
					<td valign="top">:</td>
					<td valign="top"><?= $formatter->asDecimal($progress->physical_percent, 2); ?> %</td>
				</tr>
			</table>
		</td>
		<td width="50%" valign="top">
			<div style="text-align: center;">
				<b style="font-size: 12px;">KONDISI PEKERJAAN</b>
			</div>
			<hr />
			<table width="100%">
				<tr>
					<td valign="top" width="30%">Jam Kerja</td>
					<td valign="top">:</td>
					<td valign="top"><?= $progress->working_hours_start ? $formatter->asDatetime($progress->working_hours_start) : '-'; ?> s/d <?= $progress->working_hours_end ? $formatter->asDatetime($progress->working_hours_end) : '-'; ?></td>
				</tr>
				<tr>
					<td valign="top" width="30%">Jumlah Pekerja</td>
					<td valign="top">:</td>
					<td valign="top"><?= $progress->number_of_workers ? $formatter->asDecimal($progress->number_of_workers, 0) : '-'; ?></td>
				</tr>
				<tr>
					<td valign="top" width="30%">Kendala/Hambatan</td>
					<td valign="top">:</td>
					<td valign="top"><?= $progress->problem ? $formatter->asNtext($progress->problem) : '-'; ?></td>
				</tr>
				<tr>
					<td valign="top" width="30%">Solusi</td>
					<td valign="top">:</td>
					<td valign="top"><?= $progress->solution ? $formatter->asNtext($progress->solution) : '-'; ?></td>
				</tr>
				<tr>
					<td valign="top" width="30%">Catatan</td>
					<td valign="top">:</td>
					<td valign="top"><?= $progress->notes ? $formatter->asNtext($progress->notes) : '-'; ?></td>
				</tr>
				<tr>
					<td valign="top" width="30%">Kesimpulan</td>
					<td valign="top">:</td>
					<td valign="top"><?= $progress->description ? $formatter->asNtext($progress->description) : '-'; ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="100%" valign="top" colspan="2">
			<div style="text-align: center;">
				<b style="font-size: 12px;"><?= strtoupper('Pekerjaan yang sedang dilakukan') ?></b>
			</div>
			<hr />
			<table width="100%">
				<tr>
					<td valign="top"><?= $progress->work_being_done ? $formatter->asNtext($progress->work_being_done) : '-'; ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="100%" valign="top" colspan="2">
			<div style="text-align: center;">
				<b style="font-size: 12px;"><?= strtoupper('Cuaca') ?></b>
			</div>
			<hr />
			<table width="100%">
				<?php if (sizeof($progress->weathers) > 0) : ?>
					<?php foreach ($progress->weathers as $weather) : ?>
						<tr>
							<td valign="top" width="20%"><?= $weather->name ? $formatter->asText($weather->name) : '-' ?></td>
							<td valign="top">:</td>
							<td valign="top"><?= $weather->time_from ? $formatter->asDatetime($weather->time_from) : '-'; ?></td>
							<td valign="top">s/d</td>
							<td valign="top"><?= $weather->time_to ? $formatter->asDatetime($weather->time_to) : '-'; ?></td>
							<td valign="top"><?= $weather->note ? $formatter->asNtext($weather->note) : '-'; ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					-
				<?php endif; ?>
			</table>
		</td>
	</tr>
	<tr>
		<td width="100%" valign="top" colspan="2">
			<div style="text-align: center;">
				<b style="font-size: 12px;">MONITOR K3</b>
			</div>
			<hr />
			<table width="100%">
				<tr>
					<td valign="top" width="55%">Identifikasi Item Pekerjaan yang berpotensi Kecelakaan kerja</td>
					<td valign="top">:</td>
					<td valign="top"><?= $progress->work_items_of_potential_accident ? $formatter->asNtext($progress->work_items_of_potential_accident) : '-'; ?></td>
				</tr>
				<tr>
					<td valign="top" width="55%">Item Pekerjaan yang terjadi kecelakaan kerja</td>
					<td valign="top">:</td>
					<td valign="top"><?= $progress->work_items_of_occured_accident ? $formatter->asNtext($progress->work_items_of_occured_accident) : '-'; ?></td>
				</tr>
				<tr>
					<td valign="top" width="55%">Kejadian Kecelakaan Ringan</td>
					<td valign="top">:</td>
					<td valign="top"><?= $progress->num_minor_accident ? $formatter->asDecimal($progress->num_minor_accident, 0) : '-'; ?></td>
				</tr>
				<tr>
					<td valign="top" width="55%">Kejadian Kecelakaan Sedang</td>
					<td valign="top">:</td>
					<td valign="top"><?= $progress->num_moderate_accident ? $formatter->asDecimal($progress->num_moderate_accident, 0) : '-'; ?></td>
				</tr>
				<tr>
					<td valign="top" width="55%">Kejadian Kecelakaan Berat/MD</td>
					<td valign="top">:</td>
					<td valign="top"><?= $progress->num_serious_accident ? $formatter->asDecimal($progress->num_serious_accident, 0) : '-'; ?></td>
				</tr>
				<?php

				//$total_bulan_lalu = $row['tblmonitoring_she_ringan']+$row['tblmonitoring_she_sedang']+$row['tblmonitoring_she_berat']+$row['tblmonitoring_she_md'];
				$total_bulan_ini = (int)$progress->num_minor_accident + (int)$progress->num_moderate_accident + (int)$progress->num_serious_accident;
				// $total_sd_ini = $total_bulan_ini + $total_bulan_lalu;

				?>
				<tfoot>
					<tr>
						<td valign="top" width="55%" align="right">Total Minggu ini</td>
						<td valign="top">:</td>
						<td valign="top"><?= $formatter->asDecimal($total_bulan_ini, 0); ?></td>
					</tr>
					<tr>
						<td valign="top" width="55%" align="right">Total s/d saat ini</td>
						<td valign="top">:</td>
						<td valign="top"><?= $formatter->asDecimal($progress->getTotalAccidentToThisWeek(), 0); ?></td>
					</tr>
				</tfoot>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<?php foreach (array_chunk($progress->progressPictures, 2, true) as $images) : ?>

				<div width="100%">
					<div class="row">
						<?php foreach ($images as $image) : ?>
							<div class="column">
								<div style="text-align: center;">
									<span class="title"><?= Html::encode($image->name) ?></span>
									<?php
									if (pathinfo($image->file_name, PATHINFO_EXTENSION) == 'mp4') {
										$imagedata = Builder::create()
											->writer(new PngWriter())
											->writerOptions([])
											->data(Url::to($image->picture_url, true))
											->encoding(new Encoding('UTF-8'))
											->errorCorrectionLevel(ErrorCorrectionLevel::High)
											->size(220)
											->margin(0)
											->roundBlockSizeMode(RoundBlockSizeMode::Margin)
											->labelText(false)
											// ->labelFont(new NotoSans(20))
											->labelAlignment(LabelAlignment::Center)
											->validateResult(false)
											// ->backgroundColor(new Color(188, 194, 199))
											->build();

										$imagedata = $imagedata->getDataUri();
										echo Html::img(
											$imagedata,
											[
												'class' => 'gbr'
											]
										);
									} else {
										echo Html::img($image->picture_url, ['class' => 'gbr']);
									}
									?><br />
									<span class="caption"><?= Html::encode($image->description) ?></span>
								</div>
							</div>
						<?php endforeach ?>
					</div>
				</div>

			<?php endforeach; ?>
		</td>
	</tr>
</table>