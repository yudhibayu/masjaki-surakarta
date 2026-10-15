<?php

use app\modules\construction\models\ConstructionAccidentFile;
use yii\widgets\Pjax;
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;
use app\modules\manage_transaction\models\TblJournal;
use kartik\export\ExportMenu;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
// use Yii;


/* @var $this yii\web\View */
/* @var $searchModel app\modules\report\models\TblJournalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Report K3');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs('

$(document).on("pjax:success", function() {
    gridMod();
});
    function gridMod() {    
        var gridview_id = "#journal"; // specific gridview
        var columns = [1,2]; // index column that will grouping, start 1

        var column_data = [];
            column_start = [];
            rowspan = [];

        for (var i = 0; i < columns.length; i++) {
            column = columns[i];
            column_data[column] = "";
            column_start[column] = null;
            rowspan[column] = 1;
        }

        var row = 1;
        $(gridview_id+" table > tbody  > tr").each(function() {
            var col = 1;
            $(this).find("td").each(function(){
                for (var i = 0; i < columns.length; i++) {
                    if(col==columns[i]){
                        if(column_data[columns[i]] == $(this).html() && $(this).html() != "Ya" && $(this).html() != "Tidak"){
                            $(this).remove();
                            rowspan[columns[i]]++;
                            $(column_start[columns[i]]).attr("rowspan",rowspan[columns[i]]);
                        }
                        else{
                            column_data[columns[i]] = $(this).html();
                            rowspan[columns[i]] = 1;
                            column_start[columns[i]] = $(this);
                        }
                    }
                }
                col++;
            })
            row++;
        });
    }

    gridMod();
', View::POS_READY);
?>

<section class="card">
    <div class="card-header">
        <h3> <?= Html::encode($this->title) ?></h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <?php Pjax::begin(); ?>
                <?php echo $this->render('_search', ['model' => $searchModel, 'dropdown_data' => $dropdown_data, 'queryString' => Yii::$app->request->queryString]); ?>

                <div class="table-responsive">
                    <?= GridView::widget([
                        'id' => 'journal',
                        'dataProvider' => $dataProvider,
                        'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
                        'tableOptions' => ['class' => 'table table-bordered'],
                        // 'filterModel' => $searchModel,
                        'columns' => [

                            [
                                'attribute' => 'pbj_name',
                                'value' => 'constructionWork.name',
                                'contentOptions' => ['style' => 'white-space: normal'], // For TD
                            ],
                            'accident:boolean',
                            [
                                'attribute' => 'date',
                                'format' => 'date'
                            ],
                            'accident_category',
                            [
                                'attribute' => 'amount_victims_minor_injuries',
                                'value' => function ($model) {
                                    if ($model->amount_victims_minor_injuries) {
                                        return $model->amount_victims_minor_injuries . ' orang';
                                    } else {
                                        return '';
                                    }
                                },
                            ],
                            [
                                'attribute' => 'amount_victims_moderate_injuries',
                                'value' => function ($model) {
                                    if ($model->amount_victims_moderate_injuries) {
                                        return $model->amount_victims_moderate_injuries . ' orang';
                                    } else {
                                        return '';
                                    }
                                },
                            ],
                            [
                                'attribute' => 'amount_victims_serious_injuries',
                                'value' => function ($model) {
                                    if ($model->amount_victims_serious_injuries) {
                                        return $model->amount_victims_serious_injuries . ' orang';
                                    } else {
                                        return '';
                                    }
                                },
                            ],
                            [
                                'attribute' => 'amount_victims_die',
                                'value' => function ($model) {
                                    if ($model->amount_victims_die) {
                                        return $model->amount_victims_die . ' orang';
                                    } else {
                                        return '';
                                    }
                                },
                            ],
                            'description',
                            'bpjs:boolean',
                            'no_bpjs',
                            'total_pekerja',
                            [
                                'attribute' => 'iuran_pekerja',
                                'format' => 'currency',
                            ],
                            [
                                'attribute' => 'file_accident',
                                'contentOptions' => ['style' => 'white-space: normal'], // For TD
                                'format' => 'raw',
                                'value' => function ($model) {
                                   return Html::a(
                                        Yii::t('app', 'Detail'),
                                        Url::base(true) . '/construction/construction-accident/view?id=' . Yii::$app->hasher->encode($model->id),
                                        [
                                            'class' => 'btn btn-xs btn-primary',
                                            'data' => [
                                                'pjax' => 0,
                                                // 'fancybox' => "accident",
                                            ],
                                            'target' => '_blank'
                                        ]
                                    );
                                }
                                // 'value' => function ($model) {
                                //    $file = ConstructionAccidentFile::find()->select('id')->where(['construction_accident_id' => $model->id])->all();
                                //    $arr_file = [];
                                //    foreach($file as $f){
                                //         array_push($arr_file, Html::a(
                                //             Url::base(true) . '/construction/construction-accident-file/fetch-file?id=' . Yii::$app->hasher->encode($f['id']),
                                //             Url::base(true) . '/construction/construction-accident-file/fetch-file?id=' . Yii::$app->hasher->encode($f['id']),
                                //             [
                                //                 // 'class' => 'btn btn-xs btn-success',
                                //                 'data' => [
                                //                     'pjax' => 0,
                                //                     // 'fancybox' => "accident",
                                //                 ],
                                //                 'target' => '_blank'
                                //             ]
                                //         ));                           
                                //    }
                                //    return implode("<br><br>", $arr_file);
                                // }
                            ],
                        ],
                    ]); ?>
                </div>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</section>