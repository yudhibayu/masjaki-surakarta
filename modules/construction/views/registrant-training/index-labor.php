<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\construction\models\RegistrantTrainingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Currently Registering for Training');
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="registrant-training-index">

            <!-- <php Pjax::begin(['id' => 'registrant-training-yea-pjax']); ?> -->

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <div class="table-responsive">
                <?= GridView::widget(
                    [
                        'dataProvider' => $dataProviderTraining,
                        'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'font-size:11px'],
                        'filterModel' => $searchModelTraining,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => Yii::t('app', 'Action'),
                                'headerOptions' => ['width' => '150'],
                                'template' => '{view}',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {
                                        return Html::a(
                                            '<span class="bx bx-show"></span>',
                                            [
                                                Url::toRoute(['work-training/view', 'id' => Yii::$app->hasher->encode($model->id_training)])
                                            ],
                                            [
                                                'class' => 'btn btn-xs btn-primary',
                                                'style' => 'margin: 2px;width: 35px;',
                                                'data-toggle' => 'tooltip',
                                                'title' => Yii::t('app', 'View'),
                                            ]
                                        );
                                    },
                                ]
                                ],
                            // 'id_training',
                            'name_training',
                            // 'id_labor',
                            // 'nik',
                            // 'name_labor',
                            // 'subdistrict',
                            // 'ward',
                            [
                                'attribute' => 'status',
                                'value' => 'statusSelected',
                            ],
                            // 'created_at:datetime',
                            //'updated_at:datetime',
                            /*[
                    'attribute' => 'created_by', 
                    'value' => 'maker',
                    'filter' => false
                    ],*/
                            /*[
                    'attribute' => 'updated_by', 
                    'value' => 'updater',
                    'filter' => false
                    ],*/
                        ],
                    ]
                ); ?>
                </div>
            </div>
            <!-- <php Pjax::end(); ?> -->

        </div>
    </div>

</section>

<?= $this->registerJs(
    <<< EOT_JS_CODE

(function($){
    $(document).on('ajaxError', function(event, xhr) {
      if (xhr.status === 401 || xhr.status === 403) {
        window.location.reload();
      }
    });
})(jQuery);

EOT_JS_CODE

);
?>