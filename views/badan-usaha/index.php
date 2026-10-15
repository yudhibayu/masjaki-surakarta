<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use yii\widgets\Breadcrumbs;

$this->title = 'Badan Usaha Konstruksi';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container mt-4">
  <div class="card shadow-sm border-0 overflow-hidden" style="border-radius: 12px;">
    <div class="card-header pt-5 px-5 py-3" style="border-radius: 12px 12px 0 0;">
      <h3 class="mb-3"><i class="menu-icon tf-icons bx bx-buildings"></i> <?= Yii::t('app', 'List of Construction Business Entities') ?></h3>
      <?= Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        'class' => 'breadcrumb mb-0',
        'itemTemplate' => "<li class=\"breadcrumb-item\">{link}</li>\n",
        'activeItemTemplate' => "<li class=\"breadcrumb-item active\" aria-current=\"page\">{link}</li>\n",
      ]); ?>
    </div>

    <?php Pjax::begin(); ?>
    <div class="table-responsive p-3 p-md-5">
      <!-- <h4 class="mb-3">Data Badan Usaha Konstruksi</h4> -->
      <?= GridView::widget([
        'dataProvider' => $dataProviderVerified,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-light table-striped table-borderless table-hover', 'style' => 'font-size:13px'],
        'columns' => [
          ['class' => 'yii\grid\SerialColumn', 'headerOptions' => ['style' => 'color: var(--accent-color);'],],
          'company_name',
          'phone_number',
          'province',
          'regency',
          'company_address',
          [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t('app', 'Action'),
            'headerOptions' => ['width' => '100', 'style' => 'color: var(--accent-color);'],
            'template' => '{view}',
            'buttons' => [
              'view' => function ($url, $model, $key) {
                return Html::a(
                  '<i class="bx bx-show"></i> Detail',
                  Url::toRoute(['view', 'id' => Yii::$app->hasher->encode($model->id)]),
                  ['class' => 'btn btn-sm btn-primary', 'style' => 'margin: 2px;']
                );
              },
            ],
          ],
        ],
        'options' => ['data-pjax' => true],
      ]); ?>
    </div>
    <?php Pjax::end(); ?>
  </div>
</div>

<style>
  .filters .help-block {
    display: none;
  }
  .table {
    margin: 0;
    font-size: 14px;
    color: #0022ff;
    border: transparent !important;
    border-collapse: separate;
    border-spacing: 0 10px;
  }
  .table th {
    background-color: #f8f9fc;
  }
  .table tbody tr td:first-child {
    border-top-left-radius: 8px;
    border-bottom-left-radius: 8px;
  }
  .table tbody tr td:last-child {
    border-top-right-radius: 8px;
    border-bottom-right-radius: 8px;
  }
</style>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var tables = document.querySelectorAll('.kv-table-wrap');
    tables.forEach(function(table) {
      table.classList.remove('kv-table-wrap');
    });
  });
</script>
