<?php

use app\modules\monitoring\models\MonitoringProgress;
use yii\helpers\Html;
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
<br />

<table class="monitoring" width="100%" border="1" style="border-collapse: collapse;">
	<thead>
		<tr style="background: #d8d8d8;">

			<td colspan="2">
				<center><b style="font-size: 12px;">MONITORING BULAN <?= strtoupper($monthname); ?> MINGGU KE <?= Html::encode($progress->order_no); ?></b></center>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<center><small><b><?= ucwords(strtolower(Html::encode($task->name))); ?></b></small></center>
			</td>
		</tr>
	</thead>
	<tr>
		<td width="50%" valign="top">
			<center>
				<b style="font-size: 12px;">REALISASI FISIK DAN KEUANGAN</b>
			</center>
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
			<center>
				<b style="font-size: 12px;">KONDISI PEKERJAAN</b>
			</center>
			<hr />
			<table width="100%">
				<tr>
					<td valign="top" width="25%">Kendala/Hambatan</td>
					<td valign="top">:</td>
					<td valign="top"><?= ucwords(Html::encode($progress->problem)); ?></td>
				</tr>
				<tr>
					<td valign="top" width="25%">Solusi</td>
					<td valign="top">:</td>
					<td valign="top"><?= ucwords(Html::encode($progress->solution)); ?></td>
				</tr>
				<tr>
					<td valign="top" width="25%">Kesimpulan</td>
					<td valign="top">:</td>
					<td valign="top"><?= ucwords(Html::encode($progress->description)); ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<?php //echo var_dump($row); 
		?>
		<td width="100%" valign="top" colspan="2">
			<center>
				<b style="font-size: 12px;">MONITOR K3</b>
			</center>
			<hr />
			<table width="100%">
				<tr>
					<td valign="top" width="55%">Identifikasi Item Pekerjaan yang berpotensi Kecelakaan kerja</td>
					<td valign="top">:</td>
					<td valign="top"><?= ucwords(Html::encode($progress->work_items_of_potential_accident)); ?></td>
				</tr>
				<tr>
					<td valign="top" width="55%">Item Pekerjaan yang terjadi kecelakaan kerja</td>
					<td valign="top">:</td>
					<td valign="top"><?= ucwords(Html::encode($progress->work_items_of_occured_accident)); ?></td>
				</tr>
				<tr>
					<td valign="top" width="55%">Kejadian Kecelakaan Ringan</td>
					<td valign="top">:</td>
					<td valign="top"><?= $formatter->asDecimal($progress->num_minor_accident, 0); ?></td>
				</tr>
				<tr>
					<td valign="top" width="55%">Kejadian Kecelakaan Sedang</td>
					<td valign="top">:</td>
					<td valign="top"><?= $formatter->asDecimal($progress->num_moderate_accident, 0); ?></td>
				</tr>
				<tr>
					<td valign="top" width="55%">Kejadian Kecelakaan Berat/MD</td>
					<td valign="top">:</td>
					<td valign="top"><?= $formatter->asDecimal($progress->num_serious_accident, 0); ?></td>
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
					<!-- <tr>
						<td valign="top" width="55%" align="right">Total s/d saat ini</td>
						<td valign="top">:</td>
						<td valign="top"><?= $formatter->asDecimal($total_bulan_ini, 0); ?></td>
					</tr> -->
				</tfoot>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<?php // foreach (array_chunk($fotolokasi, 2, true) as $array_kelompokdata) : 
			?>

			<div width="100%">
				<div class="row">
					<?php // foreach ($array_kelompokdata as $list_kelompok): 
					?>
					<!-- <div class="column">
								<center>
									<img class="gbr" src="<?php // echo Yii::app()->getBaseUrl(1)
															?>/uploads/file/fotosurvey/<?php // echo $list_kelompok['tblfotolokasi_file'] 
																						?>" ><br />
									<span class="caption"><?php // echo $list_kelompok['tblfotolokasi_caption'] 
															?></span>
								</center>
							</div> -->
					<?php // endforeach 
					?>
				</div>
			</div>

			<?php // endforeach; 
			?>
		</td>
	</tr>


</table>