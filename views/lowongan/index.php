<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\Breadcrumbs;
use yii\widgets\ListView;

$this->title = 'Daftar Lowongan Konstruksi';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="container mt-4">
  <div class="card shadow-sm border-0 overflow-hidden" style="border-radius: 12px;">
    <div class="card-header pt-5 px-5 py-3" style="border-radius: 12px 12px 0 0;">
      <h3 class="mb-3"><i class="menu-icon tf-icons bx bx-buildings"></i> <?= Yii::t('app', 'View Job Vacancy') ?></h3>
      <?php echo Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        'class' => 'breadcrumb mb-0',
        'itemTemplate' => "<li class=\"breadcrumb-item\">{link}</li>\n",
        'activeItemTemplate' => "<li class=\"breadcrumb-item active\" aria-current=\"page\">{link}</li>\n",
      ]);
      ?>
    </div>

    <?php Pjax::begin(); ?>
    <div class="list-view p-5">
      <?= ListView::widget([
        'dataProvider' => $dataProviderPublicated,
        'itemView' => '_job_item',
        'layout' => "{items}\n{pager}",
        'options' => ['class' => 'row'],
        'itemOptions' => ['class' => 'col-md-4 mb-4'],
      ]); ?>
    </div>
    <?php Pjax::end(); ?>
  </div>
</div>

<style>
  .list-view {
    margin: 0;
  }

  .list-view .item {
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
    padding: 15px;
    transition: box-shadow 0.3s;
  }

  .list-view .item:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .btn-primary {
    background-color: #003A98;
    border-color: #003A98;
  }

  .btn-primary:hover {
    background-color: #002a74;
    border-color: #002a74;
  }
</style>