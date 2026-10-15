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
	<?php foreach ($progress->monitoringSpecs as $monspec) : ?>
		<tr colspan="2">
			<td valign="top">
				<?php $ded = $monspec->dedData; ?>
				<table width="100%" style="border-collapse: collapse;" valign="top">
					<tr>
						<td valign="top">
							<div style="text-align: center;">
								<b style="font-size: 12px;">DED</b><br>
								<b style="font-size: 12px;"><?= $ded->name; ?></b>
							</div>
							<hr />
							<table width="100%">
								<tr>
									<td valign="top" width="25%">Nama DED</td>
									<td valign="top">:</td>
									<td valign="top"><?= ucwords(Html::encode($ded->name)); ?></td>
								</tr>
								<tr>
									<td valign="top" width="25%">Kategori Komponen</td>
									<td valign="top">:</td>
									<td valign="top"><?= ucwords(Html::encode($ded->ref_component_category_name)); ?></td>
								</tr>
								<tr>
									<td valign="top" width="25%">Komponen</td>
									<td valign="top">:</td>
									<td valign="top"><?= ucwords(Html::encode($ded->ref_component_name)); ?></td>
								</tr>
								<tr>
									<td valign="top" width="25%">Kuantitas</td>
									<td valign="top">:</td>
									<td valign="top"><?= ucwords(Html::encode(isset($ded->kuantitasSatuan) ? $ded->kuantitasSatuan : '-')); ?></td>
								</tr>
								<tr>
									<td valign="top" width="25%">Spesifikasi</td>
									<td valign="top">:</td>
									<td valign="top"><?= ucwords(Html::encode(isset($ded->specification) ? $ded->specification : '-')); ?></td>
								</tr>
								<tr>
									<td valign="top" width="25%">MCO / CCO</td>
									<td valign="top">:</td>
									<td valign="top">
										<?php
										if ($ded->cco == 0) {
											echo Html::encode('MCO');
										} else {
											echo Html::encode('CCO ' . $ded->cco);
										}
										?>
									</td>
								</tr>
								<tr>
									<td valign="top" width="25%">Deskripsi</td>
									<td valign="top">:</td>
									<td valign="top"><?= ucwords(Html::encode($ded->notes)); ?></td>
								</tr>
							</table>
							<hr />
						</td>
					</tr>
					<tr>
						<td>
							<?php foreach (array_chunk($ded->files, 1, true) as $files) : ?>
								<div width="100%">
									<div class="row">
										<?php foreach ($files as $image) : ?>
											<div class="widecolumn">
												<div style="text-align: center;">
													<span class="title"><?= Html::encode($image->name) ?></span>
													<?php
													if (pathinfo($image->file_name, PATHINFO_EXTENSION) == 'mp4') {
														$imagedata = Builder::create()
															->writer(new PngWriter())
															->writerOptions([])
															->data(Url::to(['/technicalspec/ded-files/fetch-file', 'id' => Yii::$app->hasher->encode($image->id)], true))
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
														echo Html::img(['/technicalspec/ded-files/fetch-file', 'id' => Yii::$app->hasher->encode($image->id)], ['class' => 'gbr']);
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
			</td>
			<td valign="top">
				<table width="100%" style="border-collapse: collapse;" valign="top">
					<tr valign="top">
						<td valign="top">
							<div style="text-align: center;">
								<b style="font-size: 12px;">MONITORING SPESIFIKASI</b><br>
								<b style="font-size: 12px;"><?= $monspec->name; ?></b>
							</div>
							<hr />
							<table width="100%">
								<tr>
									<td valign="top" width="25%">Item Pekerjaan DED</td>
									<td valign="top">:</td>
									<td valign="top"><?= ucwords(Html::encode($monspec->ded_data_name)); ?></td>
								</tr>
								<tr>
									<td valign="top" width="25%">Deskripsi</td>
									<td valign="top">:</td>
									<td valign="top"><?= ucwords(Html::encode($monspec->description)); ?></td>
								</tr>
							</table>
							<hr />
						</td>
					</tr>
					<tr>
						<td>
							<?php foreach (array_chunk($monspec->files, 1, true) as $files) : ?>
								<div width="100%">
									<div class="row">
										<?php foreach ($files as $image) : ?>
											<div class="widecolumn">
												<div style="text-align: center;">
													<span class="title"><?= Html::encode($image->name) ?></span>
													<?php
													if (pathinfo($image->file_name, PATHINFO_EXTENSION) == 'mp4') {
														$imagedata = Builder::create()
															->writer(new PngWriter())
															->writerOptions([])
															->data(Url::to(['/technicalspec/monitoring-spec-files/fetch-file', 'id' => Yii::$app->hasher->encode($image->id)], true))
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
														echo Html::img(['/technicalspec/monitoring-spec-files/fetch-file', 'id' => Yii::$app->hasher->encode($image->id)], ['class' => 'gbr']);
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
			</td>
		<?php endforeach; ?>
		</tr>
</table>