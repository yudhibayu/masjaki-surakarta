<?php

namespace app\modules\monitoring\controllers;

use Yii;

use app\modules\monitoring\models\MonitoringProgress;
use app\modules\monitoring\models\MonitoringProgressPictures;
use app\modules\monitoring\models\MonitoringProgressSearch;
use app\modules\monitoring\models\MonitoringTask;
use app\modules\monitoring\models\MonitoringProgressPicturesSearch;
use app\modules\monitoring\models\MonitoringWeatherSearch;
use app\modules\technicalspec\models\MonitoringSpecSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\ServerErrorHttpException;

/**
 * ProgressController implements the CRUD actions for MonitoringProgress model.
 */
class ProgressController extends Controller
{
    private $dropdown_data;
    private $sess;

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
                    'verify-progress' => ['POST'],
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
                        'roles' => ['Administrator', 'OPD', 'Team',],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete',],
                        'roles' => ['Supervisor',],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'reset_session'],
                        'roles' => ['Viewer', 'Eksekutif', 'Planner', 'Eksekutor', 'Supervisor',],
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
    }

    /**
     * Dropdowns Populate
     *
     * @return void
     */
    public function populate_dropdown()
    {
        $this->dropdown_data = array();

        // $monitoring_task_id = MonitoringTask::find()->orderBy('name')->all();
        // $this->dropdown_data['monitoring_task_id'] = ArrayHelper::map($monitoring_task_id,'id','name');

        //monggo


    }

    /**
     * Lists all MonitoringProgress models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['monitoring_progress'];
            if (isset($this->sess['monitoring_progress']['page']))
                $_GET['page'] = $this->sess['monitoring_progress']['page'];
        } else {
            $this->sess['monitoring_progress'] = $params;
        }

        $searchModel = new MonitoringProgressSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MonitoringProgress model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $params = Yii::$app->request->queryParams;
        $id = Yii::$app->hasher->decode($id);

        $picturesModel = new MonitoringProgressPicturesSearch();
        $picturesDataProvider = $picturesModel->search($params, $id, null, null);
        $specModel = new MonitoringSpecSearch();
        $specProvider = $specModel->search($params, $id);
        $weatherModel = new MonitoringWeatherSearch();
        $weatherDataProvider = $weatherModel->search($params, $id);

        if (isset($_POST['hasEditable'])) {
            // use Yii's response format to encode output as JSON
            // \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            // var_dump(Yii::$app->request->post('DedFiles'));exit;
            // var_dump(Yii::$app->request->post('editableKey'));exit;
            // var_dump(Yii::$app->request->post());exit;

            $index = Yii::$app->request->post('editableIndex');
            $model = MonitoringProgressPictures::find()->where(['id' => Yii::$app->request->post('editableKey')])->one();

            //edit the description
            $oldDescription = $model->description;
            $model->description = Yii::$app->request->post('MonitoringProgressPictures')[$index]['description'];

            // validate if any errors
            if ($model->save()) {
                // return JSON encoded output in the below format on success with an empty `message`
                return $this->asJson(['output' => $model->description, 'message' => '']);
            } else {
                return $this->asJson(['output' => $oldDescription, 'message' => 'Something is wrong! Please reenter.']);
            }

            return ['output' => '', 'message' => ''];
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
            'picturesModel' => $picturesModel,
            'picturesDataProvider' => $picturesDataProvider,
            'url_prev'  => Url::previous(),
            'specModel' => $specModel,
            'specProvider' => $specProvider,
            'weatherModel' => $weatherModel,
            'weatherDataProvider' => $weatherDataProvider,
        ]);
    }

    public function actionVerifyProgress($progressid)
    {
        $id = Yii::$app->hasher->decode($progressid);
        $model = $this->findModel($id);
        $user = Yii::$app->user;
        try {
            if ($user->can('Administrator') || $user->can('Super Administrator')) {
                if (!$model->verified) {
                    $model->verified = 1;
                } else {
                    $model->verified = 0;
                }
                if (!$model->save()) {
                    $model->validate();
                    throw new ServerErrorHttpException("Cannot save data : " . json_encode($model->errors));
                }
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been verified.', ['name' => $model->name]));
                return $this->redirect(['default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]);
            } else if ($user->can('OPD')) {
                if (!isset(Yii::$app->session['satker']['satker_id'])) {
                    $this->sess->setFlash('success', Yii::t('app', 'Unverified Access Not OPD'));
                    return $this->redirect(['default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]);
                }
                if (Yii::$app->session['satker']['satker_id'] != $model->monitoringTask->unit->id) {
                    $this->sess->setFlash('success', Yii::t('app', 'Unverified Access Satker'));
                    return $this->redirect(['default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]);
                }
                if (!$model->verified) {
                    $model->verified = 1;
                } else {
                    $model->verified = 0;
                }
                if (!$model->save()) {
                    $model->validate();
                    throw new ServerErrorHttpException("Cannot save data : " . json_encode($model->errors));
                }
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been verified.', ['name' => $model->name]));
                return $this->redirect(['default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            $this->sess->setFlash('error', $th->getMessage());
        }
        $this->sess->setFlash('success', Yii::t('app', 'Unverified Access'));
        return $this->redirect(['default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]);
    }

    /**
     * Creates a new MonitoringProgress model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = new MonitoringProgress();
        $task = MonitoringTask::findOne(['id' => $id]);
        if (!$task) {
            throw new NotFoundHttpException('Task not found');
        }
        $model->monitoring_task_id = $task->id;
        if (!Yii::$app->request->isPost) {
            $model->planned_financial_budget = (float)$task->latest_plan_financial_budget;
            $model->planned_physical_percent = (float)$task->latest_plan_physical_percent;
            $model->financial_budget = (float)$task->latest_monitored_financial_budget;
            $model->physical_percent = (float)$task->latest_monitored_physical_percent;
        }

        if ($model->load(Yii::$app->request->post())) {
            if (!$model->save()) {
                // $model->validate();
                // var_dump($model->errors);exit;
            } else {
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->name]));
                return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
            }
        }

        $this->populate_dropdown();
        if (!$model->order_no) {
            if ((int)$model->maxOrder > 0) {
                $model->order_no = (int)$model->maxOrder;
            } else {
                $model->order_no = 1;
            }
        }
        if (!$model->progress_date) {
            $model->progress_date = date('Y-m-d');
        }
        if (!$model->name) {
            $model->name = '(' . Yii::$app->formatter->asDate(time()) . ')';
        }
        if (!$model->working_hours_start) {
            $model->working_hours_start = date('Y-m-d 07:00:00');
        }
        if (!$model->working_hours_end) {
            $model->working_hours_end = date('Y-m-d 16:00:00');;
        }

        return $this->render('create', [
            'model'         => $model,
            'task'          => $task,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Updates an existing MonitoringProgress model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->verified) {
                $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> alredy verified. cannot update data.', ['name' => $model->name]));
            } else if ($model->save()) {
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been updated.', ['name' => $model->name]));
                return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($id)]);
            }
        }
        // else if(!$model->save()){
        //     $model->validate();
        //     return $this->asJson($model->errors);
        // }

        $this->populate_dropdown();

        return $this->render('update', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Deletes an existing MonitoringProgress model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);
        $task = $model->monitoringTask;
        $taskid = $task->id;
        if ($model->verified) {
            $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> alredy verified. cannot delete data.', ['name' => $model->name]));
        } else if ($model->delete()) {
            $latest = $task->latestMonitoring;
            if ($latest) {
                $task->latest_plan_financial_budget = $latest->planned_financial_budget;
                $task->latest_plan_physical_percent = $latest->planned_physical_percent;
                $task->latest_monitored_financial_budget = $latest->financial_budget;
                $task->latest_monitored_physical_percent = $latest->physical_percent;
                $task->save(false);
            } else {
                $task->latest_plan_financial_budget = 0;
                $task->latest_plan_physical_percent = 0;
                $task->latest_monitored_financial_budget = 0;
                $task->latest_monitored_physical_percent = 0;
                $task->save(false);
            }
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->name]));
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->name]));
        }

        return $this->redirect(['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($taskid)]);
    }

    /**
     * Finds the MonitoringProgress model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MonitoringProgress the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MonitoringProgress::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionReset_session()
    {
        $this->sess->remove('monitoring_progress');
        return $this->redirect(['index']);
    }
}
