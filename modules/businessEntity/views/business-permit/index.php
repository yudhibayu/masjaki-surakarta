<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\businessEntity\models\BusinessPermitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Business Permits');
// $this->params['breadcrumbs'][] = $this->title;

$id = Yii::$app->request->get('id');
?>
<?= $this->registerJs(
    <<< EOT_JS_CODE
    
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
});

       

EOT_JS_CODE

);
?>
<section class="card">
    <div class="card-header mb-0">
        <h3><?= Html::encode($this->title) ?> - List Data</h3>
        <!-- <h3><i class="menu-icon tf-icons bx bx-buildings"></i> Badan Usaha Konstruksi - List Data</h3>
        <p>Manajemen Badan Usaha Konstruksi</p> -->
    </div>
    <div class="card-body">
        <div class="business-permit-index">
            <p>
                <?= Html::a('<i class="menu-icon tf-icons bx bx-plus fw-bold"></i> ' .  Yii::t('app', 'Create') . Yii::t('app', 'Business Permits'), ['business-permit/create', 'id' => $id], ['class' => 'btn btn-success fw-bold']) ?>
            </p>
            <br>
            <?php Pjax::begin(['id' => 'business-permit-yea-pjax']); ?>

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <div class="table-responsive">
                    <?= GridView::widget(
                        [
                            'dataProvider' => $businessPermitDataProvider,
                            'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover', 'style' => 'font-size:11px'],
                            'filterModel' => $businessPermitModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'attribute' => 'business_permit_type',
                                    'value' => function ($model) {
                                        return $model->typeOfBusinessLicense ? $model->typeOfBusinessLicense->name : null;
                                    },
                                ],
                                'reference_number',
                                'valid_until',
                                'business_qualification',
                                'grantor_institution',
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => Yii::t('app', 'Action'),
                                    'headerOptions' => ['width' => '100'],
                                    'template' => '{view} {update} {delete} {file} {kbli}',
                                    'buttons' => [
                                        'view' => function ($url, $model, $key) {
                                            return Html::a(
                                                '<span class="bx bx-show"></span>',
                                                [
                                                    Url::toRoute(['business-permit/view', 'id' => Yii::$app->hasher->encode($model->id)])
                                                ],
                                                [
                                                    'class' => 'btn btn-xs btn-primary',
                                                    'style' => 'margin: 2px; width: 35px;',
                                                ]
                                            );
                                        },
                                        'update' => function ($url, $model, $key) {
                                            return Html::a(
                                                '<span class="bx bx-edit"></span>',
                                                [
                                                    Url::toRoute(['business-permit/update', 'id' => Yii::$app->hasher->encode($model->id)])
                                                ],
                                                [
                                                    'class' => 'btn btn-xs btn-warning',
                                                    'style' => 'margin: 2px; width: 35px;',
                                                ]
                                            );
                                        },
                                        'delete' => function ($action, $model, $key) {
                                            return Html::a(
                                                '<span class="bx bx-trash"></span>',
                                                [
                                                    Url::toRoute(['business-permit/delete', 'id' => Yii::$app->hasher->encode($model->id), 'parent' => Yii::$app->hasher->encode($model->id_business_entity)])
                                                ],
                                                [
                                                    'data' => [
                                                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                        'method' => 'post',
                                                        'params' => [],
                                                    ],
                                                    'class' => 'btn btn-xs btn-danger',
                                                    'style' => 'margin: 2px; width: 35px;',
                                                ]
                                            );
                                        },
                                        'file' => function ($url, $model, $key) {
                                            return Html::a(
                                                '<span class="bx bx-file"></span>',
                                                [
                                                    Url::toRoute(['./photo-business-permit/create', 'id' => Yii::$app->hasher->encode($model->id)])
                                                ],
                                                [
                                                    'data-toggle' => 'tooltip',
                                                    'title' => Yii::t('app', 'Create') . '' . Yii::t('app', 'Business Permits'),
                                                    'class' => 'btn btn-xs btn-info',
                                                    'style' => 'margin: 2px; width: 35px;',
                                                ]
                                            );
                                        },
                                        'kbli' => function ($url, $model, $key) {
                                            return Html::a(
                                                '<span class="bx bx-code-block"></span>',
                                                [
                                                    Url::toRoute(['./business-classification/create', 'id' => Yii::$app->hasher->encode($model->id)])
                                                ],
                                                [
                                                    'data-toggle' => 'tooltip',
                                                    'title' => Yii::t('app', 'Create') . '' . Yii::t('app', 'Business Classification'),
                                                    'class' => 'btn btn-xs btn-success',
                                                    'style' => 'margin: 2px; width: 35px;',
                                                ]
                                            );
                                        },
                                    ],
                                ],
                            ],
                        ]
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