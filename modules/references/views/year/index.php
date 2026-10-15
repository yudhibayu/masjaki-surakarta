<?php

use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\bootstrap5\Html;
use app\modules\references\References;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = References::t('year_lang', 'Year Setting');
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-body">
        <div class="reftahun-index">

            <h3><?= Html::encode($this->title) ?></h3>

            <p>
                <?= Html::a(References::t('year_lang', 'Create Year'), ['create'], ['class' => 'btn btn-success']) ?>
            </p>

            <?php Pjax::begin(); ?>

            <?= GridView::widget([
                'pager' => [
                    'class' => 'yii\bootstrap5\LinkPager'
                ],
                'dataProvider' => $dataProvider,
                'tableOptions' => [
                    'class' => 'table table-responsive table-bordered'
                ],
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'header' => 'No.'

                    ],

                    'name',
                    'active',
                    'active_login',
                    'ref_sot_name',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => References::t('app', 'Actions')
                    ],
                ],
            ]); ?>

            <?php Pjax::end(); ?>

        </div>
    </div>
</section>