<?php

use app\modules\monitoring\models\MonitoringProgress;
use yii\bootstrap5\Html;
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
<div class="col-md-12">
	<div class="monitoring-progress row">
		<div class="col-md-12">
			<h4>MINGGU KE <?= Html::encode($progress->order_no); ?></h4>
		</div>
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
			<div style="text-align: center;">
				<div class="row" style="padding: 5px;">
					<?php foreach ($progress->progressPictures as $image) : ?>
						<div class="col-md-6 col-lg-4" style="text-align: left;">
							<div class="card">
								<?=
								Html::a(
									Html::img(($image->file_thumbnail ? $image->thumbnail_url : $image->picture_url), ["class" => "card-img-top img-thumbnail", 'alt' => $image->name]),
									$image->picture_url,
									[
										'data' => [
											'pjax' => 0,
											'fancybox' => "monitoring",
										],
										'class' => 'fancyboxitem',
									]
								);
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
			</div>
		</div>
	</div>
	<br>
</div>