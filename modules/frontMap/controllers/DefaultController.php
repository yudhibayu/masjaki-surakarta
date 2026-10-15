<?php

namespace app\modules\frontMap\controllers;

use app\models\Satker;
use app\models\Teamlist;
use app\modules\frontMap\models\MonitoringLocationTaskSearch;
use app\modules\frontMap\models\MonitoringTaskFrontTableSearch;
use app\modules\monitoring\models\MonitoringProgressSearch;
use app\modules\monitoring\models\MonitoringTask;
use app\modules\monitoring\models\MonitoringTaskLocations;
use app\modules\monitoring\models\MonitoringTaskLocationsSearch;
use app\modules\monitoring\models\MonitoringTaskSearch;
use app\modules\monitoring\models\MonitoringProgressPicturesSearch;
use app\modules\references\models\ObjectType;
use app\modules\references\models\RefKecamatan;
use app\modules\rup\models\Rup;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `setting` module
 */
class DefaultController extends Controller
{
    public $dropdown_data;
    private $sess;
    private $year;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'filter' => ['POST'],
                    'show' => ['POST'],
                    'task-data' => ['POST'],
                    'new-show' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'filter', 'show', 'all-map', 'task-data', 'new-show', 'table-index'],
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Init Object Controller
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->sess = Yii::$app->session;
        //monggo
        if (isset($this->sess['tahun'])) {
            $this->year = $this->sess['tahun'];
        } else if (isset($this->sess['year'])) {
            if (is_array($this->sess['year']) && isset($this->sess['year']['name'])) {
                $this->year = $this->sess['year'];
            } else {
                $this->year = $this->sess['year'];
            }
        } else if (isset($this->sess['fiscal_year'])) {
            $this->year = $this->sess['fiscal_year'];
        } else {
            $this->year = (int)date('Y');
        }
    }

    /**
     * Dropdowns Populate
     *
     * @return void
     */
    public function populate_dropdown()
    {
        $this->dropdown_data = array();

        //monggo
        $unit = Satker::find()->where(['fiscal_year' => $this->year])->all();
        $this->dropdown_data['unit_id'] = ArrayHelper::map($unit, 'id', 'namecode');
        $teams = Teamlist::find()->all();
        $this->dropdown_data['teams'] = ArrayHelper::map($teams, 'id', 'name');
        $objecttype = ObjectType::find()->orderBy(['name' => SORT_ASC])->all();
        $this->dropdown_data['object_type'] = ArrayHelper::map($objecttype, 'id', 'name');
        $this->dropdown_data['progress'] = MonitoringTask::implementationStatusNames();
        $this->dropdown_data['is_bast_done'] = MonitoringTask::bastDoneStatusName();
        $this->dropdown_data['is_active'] = MonitoringTask::taskActiveStatusName();
        $this->dropdown_data['percent_progress'] = MonitoringLocationTaskSearch::percentLabels();
        $kecamatans = RefKecamatan::find()->orderBy(['code' => SORT_ASC])->all();
        $this->dropdown_data['kecamatan_id'] = ArrayHelper::map($kecamatans, 'id', 'name');
        $sumberdana = Rup::find()->select('sumberdana')->distinct()->column();
        $sumberdanaarr = [];
        foreach ($sumberdana as $sbd) {
            if ($sbd != '') {
                $sumberdanaarr[$sbd] = $sbd;
            }
        }
        $this->dropdown_data['sumberdana'] = $sumberdanaarr;
        $fiscal_year = MonitoringTask::find()->select('fiscal_year')->distinct()->orderBy(['fiscal_year' => SORT_ASC])->all();
        $this->dropdown_data['fiscal_year'] = ArrayHelper::map($fiscal_year, 'fiscal_year', 'fiscal_year');
        // $this->dropdown_data['task_active'] = MonitoringTask::taskActiveStatusName();
        // $this->dropdown_data['bast_done'] = MonitoringTask::bastDoneStatusName();

    }

    /**
     * Renders the index view for the module
     * @return html
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->bodyParams;
        // return $this->asJson($params);
        $model = new MonitoringLocationTaskSearch();
        if (Yii::$app->request->isPost) {
            $taskdata = $model->search($params);
            return $this->asJson($taskdata);
        }
        $this->populate_dropdown();
        return $this->render('index', [
            'model' => $model,
            'dropdown_data' => $this->dropdown_data,
        ]);
    }

    public function actionTableIndex()
    {
        $params = Yii::$app->request->queryParams;

        $searchModel = new MonitoringTaskFrontTableSearch();
        $dataProvider = $searchModel->search($params);

        $this->populate_dropdown();
        $unit = MonitoringTask::find()->select(['unit_id', 'unit_name', 'fiscal_year'])->distinct()->orderBy('fiscal_year')->asArray()->all();
        $this->dropdown_data['unit_id'] = ArrayHelper::map($unit, 'unit_id', 'unit_name', 'fiscal_year');

        Url::remember();

        return $this->render('index_table_view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dropdown_data' => $this->dropdown_data,
        ]);
    }

    public function actionView($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $params = Yii::$app->request->queryParams;

        $model = MonitoringTask::findOne(['id' => $id]);
        if (!$model) {
            throw new NotFoundHttpException('Task / Paket tidak ditemukan');
        }

        // $planModel = new MonitoringPlanSearch();
        // $planDataProvider = $planModel->search($params,$id);
        // $implementationModel = new MonitoringImplementationSearch();
        // $implementationDataProvider = $implementationModel->search($params,$id);
        $progressModel = new MonitoringProgressSearch();
        $progressDataProvider = $progressModel->search($params, $id);
        $locationsModel = new MonitoringTaskLocationsSearch();
        $locationsDataProvider = $locationsModel->search($params, $id);
        $picturesModel = new MonitoringProgressPicturesSearch();
        $picturesDataProvider = $picturesModel->search($params, null, null, $id);


        $weeks = $model->getOrderMonitoringAvaiable();
        $weeks_monitorings = [];
        $plans = [];
        $realisations = [];
        sort($weeks, SORT_NUMERIC);
        foreach ($weeks as $week) {
            $thisweek = $model->getLatestMonitoringByOrder($week);
            $weeks_monitorings[$week] = [
                'planned_physical_percent' => (float)$thisweek->planned_physical_percent,
                'physical_percent' => (float)$thisweek->physical_percent,
            ];
        }
        ksort($weeks_monitorings, SORT_NUMERIC);
        foreach ($weeks_monitorings as $weekm) {
            $plans[] = $weekm['planned_physical_percent'];
            $realisations[] = $weekm['physical_percent'];
        }

        return $this->render('view', [
            'model' => $model,
            'url_prev'  => Url::previous(),
            'progressModel' => $progressModel,
            'progressDataProvider' => $progressDataProvider,
            'locationsModel' => $locationsModel,
            'locationsDataProvider' => $locationsDataProvider,
            'picturesModel' => $picturesModel,
            'picturesDataProvider' => $picturesDataProvider,
            'weeks_monitorings' => $weeks_monitorings,
            'weeks' => $weeks,
            'plans' => $plans,
            'realisations' => $realisations,
        ]);
    }

    /**
     * Filter Marker Layer
     * 
     * @param array $data
     * @return String
     * 
     */
    public function actionFilter($data)
    {

        if (Yii::$app->request->ajax()) {
        } else {
            return false;
        }
    }

    /**
     * Filter Marker Layer
     * 
     * @param array $data
     * @return String
     * 
     */
    public function actionOldShow()
    {
        $params = Yii::$app->request->bodyParams;
        $model = new MonitoringLocationTaskSearch();
        $taskdatas = $model->search($params);
        $mapdatas = [];
        foreach ($taskdatas as $task) {
            // var_dump($task->locations);exit;
            foreach ($task->locations as $map) {
                $mapattributes = $map->attributes;
                $mapattributes['monitoring_task_id'] = Yii::$app->hasher->encode($task->id);
                $mapattributes['color'] = $task->objectType->map_color;
                $mapattributes['icon'] = $task->objectType->icon;
                $mapattributes['iconimage'] = $task->objectType->iconurl;
                $mapdatas[] = $mapattributes;
            }
        }
        return $this->asJson($mapdatas);
    }

    public function actionShow()
    {
        $params = Yii::$app->request->bodyParams;
        $model = new MonitoringLocationTaskSearch();
        $taskdatas = $model->search($params);
        $mapdatas = [
            'type' => 'FeatureCollection',
            'features' => []
        ];
        foreach ($taskdatas as $task) {
            // var_dump($task->locations);exit;
            // $task = new MonitoringTask();
            $dataArr = [
                'type' => 'Feature',
                'properties' => [
                    'color' => isset($task->objectType->map_color) ? $task->objectType->map_color : "#3388ff",
                    'iconimage' => isset($task->objectType->iconurl) ? $task->objectType->iconurl : "/images/icons/marker-icon.png",
                    'icon' => isset($task->objectType->icon) ? $task->objectType->icon : "marker-icon.png",
                    'taskid' => Yii::$app->hasher->encode($task->id),
                    'taskname' => $task->name,
                ],
                'geometry' => [
                    'type' => 'GeometryCollection',
                    'geometries' => []
                ]
            ];
            if (isset($task->default_lat) && isset($task->default_lng) && $task->default_lat && $task->default_lng) {
                $dataArr['geometry']['geometries'][] = [
                    'coordinates' => [
                        $task->default_lng,
                        $task->default_lat,
                    ],
                    'type' => 'Point',
                ];
            }
            foreach ($task->locations as $map) {
                $geojson = json_decode($map->geojson);
                foreach ($geojson->features as $feature) {
                    $dataArr['geometry']['geometries'][] = $feature->geometry;
                }
            }
            $mapdatas['features'][] = $dataArr;
        }
        return $this->asJson($mapdatas);
    }

    // public function actionAllMap()
    // {
    //     $year = date('Y');
    //     $mapdatas = MonitoringTaskLocations::find()->joinWith('task')->where([MonitoringTask::tableName() . '.fiscal_year' => $year])->asArray()->all();
    //     return $this->asJson($mapdatas);
    // }

    public function actionTaskData()
    {
        $taskid = Yii::$app->request->post('taskid', null);
        if (!$taskid) {
            $taskid = Yii::$app->request->get('taskid', null);
        }
        $taskid = Yii::$app->hasher->decode($taskid);
        // var_dump($taskid);exit;
        $task = MonitoringTask::find()->where(['id' => $taskid])->one();
        if (!$task) {
            return $this->redirect(['index']);
        }
        $weeks = $task->getOrderMonitoringAvaiable();
        $weeks_monitorings = [];
        $plans = [];
        $realisations = [];
        sort($weeks, SORT_NUMERIC);
        foreach ($weeks as $week) {
            $thisweek = $task->getLatestMonitoringByOrder($week);
            $weeks_monitorings[$week] = [
                'planned_physical_percent' => (float)$thisweek->planned_physical_percent,
                'physical_percent' => (float)$thisweek->physical_percent,
            ];
        }
        ksort($weeks_monitorings, SORT_NUMERIC);
        foreach ($weeks_monitorings as $weekm) {
            $plans[] = $weekm['planned_physical_percent'];
            $realisations[] = $weekm['physical_percent'];
        }
        // var_dump([$plans,$realisations,$weeks]);exit;
        return $this->renderAjax('view_ajax', [
            'model' => $task,
            'weeks_monitorings' => $weeks_monitorings,
            'weeks' => $weeks,
            'plans' => $plans,
            'realisations' => $realisations,
        ]);
    }
}
