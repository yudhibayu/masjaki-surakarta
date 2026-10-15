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
<div class="col-md-12">
	<h4>Monitoring Spesifikasi</h4>
	<div style="text-align: center;">
		<div class="row" style="padding: 5px;">
			<?php foreach ($progress->monitoringSpecs as $specs) : ?>
				<div class="col-xl-12">
					<div class="row">
						<div class="col-md-6">
							<?= DetailView::widget([
								'model' => $specs,
								// 'options' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:13px'],
								'attributes' => [
									'name',
									// 'ded_data_id',
									'ded_data_name',
									// 'monitoring_progress_id',
									// 'monitoring_progress_name',
									// 'monitoring_task_id',
									// 'monitoring_task_name',
									'description:ntext',
									// 'created_at:datetime',
									// 'updated_at:datetime',
									// [
									//     'attribute' => 'created_by',
									//     'value' => $model->maker
									// ],
									// [
									//     'attribute' => 'updated_by',
									//     'value' => $model->updater
									// ],
									// [
									// 	'label' => Yii::t('app', 'Action'),
									// 	'format' => 'raw',
									// 	'value' => Html::a(Yii::t('app', 'View'), ['/technicalspec/monitoring-spec/view', 'id' => Yii::$app->hasher->encode($specs->id)], ['class' => 'btn btn-primary', 'data-pjax' => 0, 'target' => '_blank'])
									// ],
								],
							]) ?>
						</div>
						<?php foreach ($specs->files as $file) : ?>
							<div class="col-md-3 text-center">
								<div class="card" style="width: 10rem;">
									<div class="card-body">
										<?php
										if (
											(strtolower($file->file_extension) == 'jpg') ||
											(strtolower($file->file_extension) == 'jpeg') ||
											(strtolower($file->file_extension) == 'png') ||
											(strtolower($file->file_extension) == 'gif') ||
											(strtolower($file->file_extension) == 'webp')
										) {
											echo Html::a(
												Html::img(Url::to(['/technicalspec/monitoring-spec-files/fetch-file', 'id' => Yii::$app->hasher->encode($file->id)], true), ['width' => '200px', 'alt' => $file->name, 'class' => 'card-img-top',]),
												Url::to(['/technicalspec/monitoring-spec-files/fetch-file', 'id' => Yii::$app->hasher->encode($file->id)], true),
												[
													'data' => [
														'pjax' => 0,
														'fancybox' => "monitoring",
													],
												]
											);
										} else {
											echo Html::a(Yii::t('app', 'Download') . ' <span class="fas fa-download"></span>', Url::to(['/technicalspec/monitoring-spec-files/fetch-file', 'id' => Yii::$app->hasher->encode($file->id)]), ['class' => 'btn btn-primary card-img-top']);
										}
										echo '<hr>';
										echo Html::tag('h5', Html::encode($file->name), [
											'class' => 'card-title'
										]);
										$description = $file->description;
										if (strlen($file->description) > 40) {
											$description = substr($file->description, 0, 37) . '...';
										}
										echo Html::tag('p', $description, [
											'class' => 'card-text'
										]);
										?>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endforeach ?>
		</div>
	</div>
</div>