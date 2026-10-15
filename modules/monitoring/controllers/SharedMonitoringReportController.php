<?php

namespace app\modules\monitoring\controllers;

use app\modules\monitoring\models\MonitoringTask;
use Yii;

use app\modules\monitoring\models\SharedMonitoringReport;
use app\modules\monitoring\models\SharedMonitoringReportSearch;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/**
 * SharedMonitoringReportController implements the CRUD actions for SharedMonitoringReport model.
 */
class SharedMonitoringReportController extends Controller
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
                        'roles' => ['Administrator', 'OPD', 'Team', 'Eksekutif', 'Planner', 'Eksekutor', 'Supervisor'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'reset_session'],
                        'roles' => ['Viewer'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['data'],
                        'roles' => ['@', '?'],
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


    }

    /**
     * Lists all SharedMonitoringReport models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        $searchModel = new SharedMonitoringReportSearch();

        if (Yii::$app->user->can('Administrator')) {
            $dataProvider = $searchModel->search($params, $this->year);
        } else {
            $dataProvider = $searchModel->search($params, $this->year, Yii::$app->user->id);
        }

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SharedMonitoringReport model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $id = Yii::$app->hasher->decode($id);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'url_prev'  => Url::previous()
        ]);
    }

    /**
     * Displays a single SharedMonitoringReport model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionData($code)
    {
        $id = Yii::$app->hasher->decode($code);
        $model = $this->findModel($id);
        if (strtotime($model->expired_at) <= time()) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        $id = $model->task_id;
        $type = $model->type;
        $month = $model->month;
        $week = $model->week;
        $day = $model->day;
        $is_spec = $model->is_spec;

        $task = $model->monitoringTask;

        if (!$id) {
            return $this->redirect(['index']);
        }
        if ($is_spec && ($is_spec != 0)) {
            $is_spec = true;
        }

        //jenis 1 = tanpa laporan bulanan & mingguan
        //jenis 2 = laporan satu bulan
        //jenis 3 = laporan semua bulan
        //jenis 4 = laporan satu minggu
        //jenis 5 = laporan satu hari
        if (($type == 'day') && $day) {
            $jenis = 5;
        } else if (($type == 'week') && $week) {
            $jenis = 4;
        } else {
            if ($month == 0) {
                $jenis = 1;
            } else if (($month < 0) || ($month > 12)) {
                $jenis = 3;
            } else {
                $jenis = 2;
            }
        }

        $gmap = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data("https://www.google.com/maps/search/?api=1&query=" . $task->latlng)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(220)
            ->margin(0)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->logoPath(Yii::getAlias('@app/web/images/logo-ska.png'))
            ->logoResizeToWidth(45)
            ->logoPunchoutBackground(true)
            ->labelText(false)
            // ->labelFont(new NotoSans(20))
            ->labelAlignment(LabelAlignment::Center)
            ->validateResult(false)
            // ->backgroundColor(new Color(188, 194, 199))
            ->build();

        // $qrcodestring = $qrcode->getDataUri();
        $gmapstring = $gmap->getDataUri();

        $content = $this->renderPartial('@app/modules/monitoring/views/default/report_monitoring', [
            'task' => $task,
            'jenis' => $jenis,
            'gmap' => $gmapstring,
            'month' => $month,
            'week' => $week,
            'day' => $day,
            'is_spec' => $is_spec,
        ]);

        $content = preg_replace('/\>\s+\</m', '><', $content);
        $content = preg_replace('/<!--(.|\s)*?-->/', '', $content);
        return $content;
    }

    /**
     * Creates a new SharedMonitoringReport model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public static function actionCreate()
    {
        $model = new SharedMonitoringReport();
        $task_id = Yii::$app->request->post('task_id', null);
        if (!$task_id) {
            $task_id = Yii::$app->request->post('id', null);
        }
        $task_id = Yii::$app->hasher->decode($task_id);
        $type = Yii::$app->request->post('type', 'month');
        $month = Yii::$app->request->post('month', 0);
        $week = Yii::$app->request->post('week', null);
        $day = Yii::$app->request->post('day', null);
        $is_spec = Yii::$app->request->post('is_spec', false);
        $task = MonitoringTask::findOne(['id' => $task_id]);
        if (!$task) {
            throw new NotFoundHttpException('Task not found');
        }
        if ($is_spec && ($is_spec != 0)) {
            $is_spec = true;
        } else {
            $is_spec = false;
        }

        $model->task_id = $task_id;
        $model->type = $type;
        $model->month = $month;
        $model->week = $week;
        $model->day = $day;
        $model->is_spec = (int)$is_spec;
        $expired_at_plus_hour = Yii::$app->appsetting->get('report_timeout') ? (int)Yii::$app->appsetting->get('report_timeout') : 24;
        $expired_at = time() + ($expired_at_plus_hour * 3600);
        $model->expired_at = date('Y-m-d H:i:s', $expired_at);

        $response = Yii::$app->response;

        if ($model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->monitoringTask->name]));
            return $response->redirect(['/monitoring/shared-monitoring-report/view', 'id' => Yii::$app->hasher->encode($model->id)]);
        }

        return $response->redirect(['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($task_id)]);
    }

    /**
     * Deletes an existing SharedMonitoringReport model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);
        $model->expired_at = date('Y-m-d H:i:s');
        if ($model->save()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->monitoringTask->name]));
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->monitoringTask->name]));
        }

        return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($id)]);
    }

    /**
     * Finds the SharedMonitoringReport model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SharedMonitoringReport the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SharedMonitoringReport::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionReset_session()
    {
        $this->sess->remove('shared_monitoring_report');
        return $this->redirect(['index']);
    }
}
