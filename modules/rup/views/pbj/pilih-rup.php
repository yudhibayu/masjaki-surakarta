<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\construction\models\ConstructionMarketSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Construction Market');
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="card">
    <div class="card-header">
        <h3><i class="menu-icon tf-icons bx bx-store"></i> <?= Html::encode($this->title) ?> - List Data</h3>
        <p>Manajemen Pasar Konstruksi</p>
    </div>
    <div class="card-body">
        <div class="construction-market-index">

            <?php Pjax::begin(['id' => 'rup-yea-pjax']); ?>

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>
            <br>

            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <div class="table-responsive">
                    <?= GridView::widget(
                        [
                            'dataProvider' => $dataProvider,
                            'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover', 'style' => 'font-size:11px'],
                            'filterModel' => $searchModel,
                            'pager' => [
                                'options' => ['class' => 'pagination justify-content-center'],
                                'linkContainerOptions' => ['class' => 'page-item'],
                                'linkOptions' => ['class' => 'page-link'],
                                'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
                                'activePageCssClass' => 'active',
                                'disabledPageCssClass' => 'disabled',
                                'firstPageLabel' => Yii::t('app', 'First'),
                                'lastPageLabel' => Yii::t('app', 'Last'),
                            ],
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                'idrup',
                                [
                                    'attribute' => 'statusumumkan',
                                    'filter' => $statusumumkanfilter,
                                ],
                                'namapaket:ntext',
                                'tahunanggaran',
                                'metodepengadaan',
                                [
                                    'attribute' => 'tipe_rup',
                                    'value' => function ($model) {
                                        $data = [
                                            0 => Yii::t('rup', 'RUP Penyedia'),
                                            1 => Yii::t('rup', 'RUP Swakelola'),
                                        ];
                                        return $data[$model->tipe_rup];
                                    },
                                    'filter' => [
                                        0 => Yii::t('rup', 'RUP Penyedia'),
                                        1 => Yii::t('rup', 'RUP Swakelola'),
                                    ],
                                ],
                                // 'klpd',
                                [
                                    'attribute' => 'jumlahpagu',
                                    'format' => ['decimal', 2],
                                ],
                                [
                                    'attribute' => 'sumberdana',
                                    'filter' => $sumberdanafilter,
                                ],
                                //'tahunanggaran',
                                //'namasatker:ntext',
                                //'kodesatker',
                                //'metodepengadaan',
                                // 'idmetodepengadaan',
                                [
                                    'attribute' => 'jenispengadaan',
                                    'value' => 'jenispengadaan',
                                    'filter' => $jenispengadaan,
                                ],

                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => Yii::t('app', 'Action'),
                                    'headerOptions' => ['width' => '80'],
                                    'template' => '{select}',
                                    'buttons' => [
                                        'select' => function ($url, $model, $key) {

                                            return Html::a(
                                                '<span class="fas fa-arrow-right"></span>',
                                                [
                                                    Url::toRoute(['select', 'id' => Yii::$app->hasher->encode($model->id)])
                                                ],
                                                [
                                                    'class' => 'btn btn-xs btn-primary',
                                                    'style' => 'margin: 2px;width: 35px;',
                                                ]
                                            );
                                        },

                                    ],
                                ],
                            ],
                        ],
                    ); ?>
                </div>
            </div>
            <?php Pjax::end(); ?>

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