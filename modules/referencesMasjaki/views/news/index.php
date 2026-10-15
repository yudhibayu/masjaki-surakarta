<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\referencesMasjaki\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Data of News');
$this->params['breadcrumbs'][] = $this->title;

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
?>

<section class="card">
    <div class="card-header">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="news-index">
            <p>
                <?= Html::a('<i class="menu-icon tf-icons bx bx-plus fw-bold"></i> ' . Yii::t('app', 'Create News'), ['create'], ['class' => 'btn btn-success fw-bold']) ?>
            </p>
            <br>

            <?php Pjax::begin(['id' => 'news-yea-pjax']); ?>

            <?php // echo $this->render('_search', ['model' => $searchModel]); 
            ?>

            <div class="card shadow-sm overflow-hidden" style="border-radius: 12px;">
                <div class="table-responsive">
                    <?= GridView::widget(
                        [
                            'dataProvider' => $dataProvider,
                            'tableOptions' => ['class' => 'table table-light table-striped table-bordered table-hover'],
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                // 'thumbnail',

                                [
                                    'attribute' => 'thumbnail',
                                    'filter' => false,
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        $photoPath = '@web/photo/' . $model->thumbnail;
                                        $fileExtension = strtolower(pathinfo($photoPath, PATHINFO_EXTENSION));
                                        $imageExtensions = ['jpg', 'jpeg', 'png'];

                                        if (in_array($fileExtension, $imageExtensions)) {
                                            $photoUrl = Url::to($photoPath);
                                            return Html::a(
                                                Html::img($photoUrl, ['width' => '100px']),
                                                $photoUrl,
                                                [
                                                    'class' => 'badge',
                                                    'data' => [
                                                        'pjax' => 0,
                                                        'fancybox' => "image-fancybox",
                                                    ],
                                                ]
                                            );
                                        }
                                    },
                                ],
                                'title',
                                'date',
                                [
                                    'attribute' => 'content',
                                    'format' => 'html', // Menampilkan sebagai HTML
                                    'value' => function ($model) use ($purifier) {
                                        return $purifier->purify($model->content); // Sanitasi dan mengembalikan HTML yang aman
                                    }
                                ],
                                // 'created_at:datetime',
                                // 'updated_at:datetime',
                                //'deleted_at',
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
                                //'deleted_by',

                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => Yii::t('app', 'Action'),
                                    'headerOptions' => ['width' => '150'],
                                    'template' => '{view} {update} {delete}',
                                    'buttons' => [
                                        'view' => function ($url, $model, $key) {

                                            return Html::a(
                                                '<span class="bx bx-show"></span>',
                                                [
                                                    Url::toRoute(['view', 'id' => Yii::$app->hasher->encode($model->id)])
                                                ],
                                                [
                                                    'class' => 'btn btn-xs btn-primary',
                                                    'style' => 'margin: 2px;width: 35px;',
                                                ]
                                            );
                                        },
                                        'update' => function ($url, $model, $key) {

                                            return Html::a(
                                                '<span class="bx bx-edit"></span>',
                                                [
                                                    Url::toRoute(['update', 'id' => Yii::$app->hasher->encode($model->id)])
                                                ],
                                                [
                                                    'class' => 'btn btn-xs btn-warning',
                                                    'style' => 'margin: 2px;width: 35px;',
                                                ]
                                            );
                                        },
                                        'delete' => function ($action, $model, $key) {

                                            return Html::a(
                                                '<span class="bx bx-trash"></span>',
                                                [
                                                    Url::toRoute(['delete', 'id' => Yii::$app->hasher->encode($model->id)])
                                                ],
                                                [
                                                    'data' => [
                                                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                        'method' => 'post',
                                                        'params' => [], // <- extra level,
                                                    ],
                                                    'class' => 'btn btn-xs btn-danger',
                                                    'style' => 'margin: 2px;width: 35px;',
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