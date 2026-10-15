<?php

use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\manage_sot\models\RefSotSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('manage_sot', 'Sot');
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="card">
    <div class="card-body">
        <div class="ref-sot-index">

            <h3><?= Html::encode($this->title) ?></h3>

            <p>
                <?= Html::a(Yii::t('manage_sot', 'Create Sot'), ['create'], ['class' => 'btn btn-success']) ?>
            </p>

            <div class="table-responsive">
                <?php Pjax::begin(); ?>
                <?php // echo $this->render('_search', ['model' => $searchModel]); 
                ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'name',
                        'note',
                        'active',


                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => Yii::t('app', 'Action'),
                            'headerOptions' => ['width' => '80'],
                            'template' => '{view} {update} {delete}',
                        ],
                    ],
                ]); ?>

                <?php Pjax::end(); ?>
            </div>

        </div>
    </div>
</section>