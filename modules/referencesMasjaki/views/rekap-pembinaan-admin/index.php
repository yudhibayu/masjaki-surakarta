<?php

$this->title = 'Rekapitulasi Pembinaan';
$this->params['breadcrumbs'][] = ['label' => 'Dashboard Pembinaan', 'url' => ['/references-masjaki/pembinaan']];
$this->params['breadcrumbs'][] = $this->title;

echo $this->render('_report', [
    'selectedYear' => $selectedYear,
    'selectedType' => $selectedType,
    'typeOptions' => $typeOptions,
    'report' => $report,
    'filterRoute' => ['index'],
]);
