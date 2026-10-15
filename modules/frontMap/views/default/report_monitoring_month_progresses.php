<?php

use app\modules\monitoring\models\MonitoringTask;
use yii\bootstrap5\Html;
use yii\i18n\Formatter;

if (!($task instanceof MonitoringTask)) {
	$task = new MonitoringTask();
}

$formatter = isset(Yii::$app->formatter) ? Yii::$app->formatter : new Formatter();
if (isset(Yii::$app->indodatetime)) {
	$indodatetime = Yii::$app->indodatetime;
	$monthname = $indodatetime->getBulan($month);
} else {
	$indodatetime = null;
	$monthname = date('F');
}

if ($iterate == 0) {
	$btnconfig = [
		'type' => 'button',
		'data-bs-toggle' => 'collapse',
		'data-bs-target' => '#collapse' . $monthname,
		'aria-expanded' => "true",
		'aria-controls' => "collapse" . $monthname,
		'class' => 'accordion-button',
	];
	$show = 'show';
} else {
	$btnconfig = [
		'type' => 'button',
		'data-bs-toggle' => 'collapse',
		'data-bs-target' => '#collapse' . $monthname,
		'aria-expanded' => "false",
		'aria-controls' => "collapse" . $monthname,
		'class' => 'accordion-button collapsed',
	];
	$show = '';
}

?>
<div class="accordion-item">
	<?= Html::tag(
		'h2',
		Html::button(strtoupper($monthname), $btnconfig),
		[
			'class' => 'accordion-header',
			'id' => 'header' . $monthname
		]
	) ?>
	<div id="collapse<?= $monthname ?>" class="accordion-collapse collapse <?= $show ?>" aria-labelledby="header<?= $monthname ?>" data-bs-parent="#monthsaccordion">
		<div class="accordion-body">
			<?php
			// foreach ($task->getMonitoringByMonth($month) as $pkey => $pvalue) {
			// 	echo $this->render('report_monitoring_progress', [
			// 		'progress' => $pvalue,
			// 	]);
			// }
			?>
			<?php
			foreach ([0 => $task->getLatestMonitoringByMonth($month)] as $pkey => $pvalue) {
				echo $this->render('report_monitoring_progress_extended', [
					'progress' => $pvalue,
				]);
			}
			?>
		</div>
	</div>
</div>