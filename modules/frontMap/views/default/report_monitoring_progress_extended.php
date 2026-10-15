<?php

use app\modules\monitoring\models\MonitoringProgress;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\i18n\Formatter;
use yii\widgets\DetailView;

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
<div class="col-md-12">
	<div class="monitoring-progress row">
		<!-- <div class="col-md-12">
			<h4>MINGGU KE <?= Html::encode($progress->order_no); ?></h4>
		</div> -->
		<div class="col-md-12">
			<table class="table table-secondary">
				<tr>
					<td width="30%">Realisasi Fisik </td>
					<td width="1%">: </td>
					<td class="text-wrap"><?= $formatter->asDecimal($progress->physical_percent, 2) ?>%</td>
				</tr>
				<tr>
					<td width="30%">Deskripsi & Kesimpulan </td>
					<td width="1%">: </td>
					<td class="text-wrap"><?= Html::encode($progress->description) ?></td>
				</tr>
			</table>
		</div>
		<div class="col-md-12">
			<h4><?= Yii::t('monitoring', 'Monitoring Progress Pictures') ?></h4>
			<div style="text-align: center;">
				<?php foreach ($task->getMonitoringByMonth($progress->date_month) as $progressmonth) : ?>
					<?php if (sizeof($progressmonth->progressPictures) >= 1) : ?>
						<br>
						<h5><?= Yii::$app->formatter->asDate($progressmonth->progress_date); ?></h5>
					<?php endif; ?>
					<div class="row" style="padding: 5px;">
						<?php foreach ($progressmonth->progressPictures as $image) : ?>
							<div class="col-md-6 col-lg-4" style="text-align: left;">
								<div class="card">
									<?php
									if (pathinfo($image->file_name, PATHINFO_EXTENSION) == 'mp4') {
										echo '<video width="320" height="240" controls>
                                    <source src="' . $image->picture_url . '" type="video/mp4">
                                    Your browser does not support the video tag.
                                    ' . Html::a(
											'Download',
											$image->picture_url,
											[
												'data' => [
													'pjax' => 0,
													'fancybox' => "monitoring",
												]
											]
										) . '
                                    </video>';
									} else {
										echo Html::a(
											Html::img(($image->picture_url), ["class" => "card-img-top img-thumbnail", 'alt' => $image->name]),
											$image->picture_url,
											[
												'data' => [
													'pjax' => 0,
													'fancybox' => "monitoring",
												],
												'class' => 'fancyboxitem',
											]
										);
									}
									?>
									<div class="card-body">
										<h5 class="card-title"><?= Html::encode($image->name) ?></h5>
										<p class="card-text"><?= ($image->description != '') ? Html::encode($image->description) : '-' ?></p>
									</div>
								</div>
								<br>
							</div>
						<?php endforeach ?>
					</div>
				<?php endforeach ?>
			</div>
		</div>
		<?php
		// echo $this->render('report_monitoring_spesifikasi', [
		// 	'progress' => $progress,
		// ]);
		?>
	</div>
	<br>
</div>