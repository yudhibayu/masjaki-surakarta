<?php

namespace app\modules\monitoring\controllers;

use app\models\Satker;
use app\models\Teamlist;
use app\modules\monitoring\models\MonitoringRecapSearch;
use app\modules\monitoring\models\MonitoringTask;
use app\modules\references\models\ObjectType;
use app\modules\references\models\RefCriticalCondition;
use app\modules\references\models\RefKecamatan;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * RecapController implements the Read actions for MonitoringTaskRecap model.
 */
class RecapController extends Controller
{
    private $dropdown_data;
    private $sess;
    private $year;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['Administrator', 'OPD', 'Team'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'reset_session'],
                        'roles' => ['Viewer'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['map'],
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
        if (isset(Yii::$app->session['satker']['satker_id'])) {
            $unit = Satker::find()->where(['ref_sot_id' => Yii::$app->session['milestone']['ref_sot_id'], 'id' => Yii::$app->session['satker']['satker_id']])->all();
        } else {
            $unit = Satker::find()->where(['ref_sot_id' => Yii::$app->session['milestone']['ref_sot_id']])->all();
        }


        if (isset(Yii::$app->session['team']['team_id'])) {
            $teams = Teamlist::find()->where(['id' => Yii::$app->session['team']['team_id']])->all();
        } else {
            $teams = Teamlist::find()->all();
        }

        $objecttype = ObjectType::find()->orderBy(['name' => SORT_ASC])->all();
        $sdbulan = [];
        $indodatetime = null;
        if (isset(Yii::$app->indodatetime)) {
            $indodatetime = Yii::$app->indodatetime;
        }
        for ($i = 1; $i <= 12; $i++) {
            if ($indodatetime) {
                $bulan = $indodatetime->getBulan($i);
            } else {
                $bulan = date('F');
            }
            $sdbulan[$i] = $bulan;
        }

        $this->dropdown_data['unit_id'] = ArrayHelper::map($unit, 'id', 'namecode');
        $this->dropdown_data['team_id'] = ArrayHelper::map($teams, 'id', 'name');
        $this->dropdown_data['object_type_id'] = ArrayHelper::map($objecttype, 'id', 'name');
        $this->dropdown_data['sd_bulan'] = $sdbulan;
        $this->dropdown_data['implementation_status'] = MonitoringTask::implementationStatusNames();
    }

    /**
     * Lists all MonitoringTask models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        // if (count($params) <= 1) {
        //     $params = $this->sess['monitoring_task'];
        //     if (isset($this->sess['monitoring_task']['page']))
        //         $_GET['page'] = $this->sess['monitoring_task']['page'];
        // } else {
        //     $this->sess['monitoring_task'] = $params;
        // }

        $searchModel = new MonitoringRecapSearch();
        $dataProvider = $searchModel->search($params);
        // return $this->asJson($dataProvider);

        Url::remember();

        $this->populate_dropdown();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dropdown_data' => $this->dropdown_data,
        ]);
    }

    /**
     * Finds the MonitoringTask model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MonitoringTask the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MonitoringRecapSearch::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionReset_session()
    {
        $this->sess->remove('monitoring_task');
        return $this->redirect(['index']);
    }
}
