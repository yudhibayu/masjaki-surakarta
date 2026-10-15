<?php

use app\modules\monitoring\models\MonitoringTask;
use yii\helpers\Html;
use yii\i18n\Formatter;

if (!($task instanceof MonitoringTask)) {
	$task = new MonitoringTask();
}
if (!isset($is_spec)) {
	$is_spec = false;
}

$formatter = isset(Yii::$app->formatter) ? Yii::$app->formatter : new Formatter();
if (isset(Yii::$app->indodatetime)) {
	$indodatetime = Yii::$app->indodatetime;
	$monthname = $indodatetime->getBulan($month);
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
				<div style="text-align: center;"><b style="font-size: 12px;">MONITORING BULAN <?= strtoupper($monthname); ?></b></div>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div style="text-align: center;"><b><?= ucwords(strtolower(Html::encode($task->name))); ?></b></div>
			</td>
		</tr>
	</thead>
	<?php
	foreach ($task->getMonitoringByMonth($month) as $pkey => $pvalue) {
		echo '<tr><td>';
		if ($is_spec) {
			echo $this->render('report_monitoring_progress_spec', [
				'progress' => $pvalue,
			]);
		} else {
			echo $this->render('report_monitoring_progress', [
				'progress' => $pvalue,
			]);
		}
		echo '</td></tr>';
	}
	?>
</table>