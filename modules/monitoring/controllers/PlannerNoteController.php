<?php

namespace app\modules\monitoring\controllers;

use Yii;

use app\modules\monitoring\models\MonitoringPlannerNote;
use app\modules\monitoring\models\MonitoringPlannerNoteFile;
use app\modules\monitoring\models\MonitoringPlannerNoteFileSearch;
use app\modules\monitoring\models\MonitoringPlannerNoteSearch;
use app\modules\monitoring\models\MonitoringTask;
use Ramsey\Uuid\Uuid;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

/**
 * PlannerNoteController implements the CRUD actions for MonitoringPlannerNote model.
 */
class PlannerNoteController extends Controller
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
                        'roles' => ['Administrator', 'OPD', 'Planner'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'reset_session'],
                        'roles' => ['Viewer'],
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


        //monggo


    }

    /**
     * Lists all MonitoringPlannerNote models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['monitoring_planner_note'];
            if (isset($this->sess['monitoring_planner_note']['page']))
                $_GET['page'] = $this->sess['monitoring_planner_note']['page'];
        } else {
            $this->sess['monitoring_planner_note'] = $params;
        }

        $searchModel = new MonitoringPlannerNoteSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MonitoringPlannerNote model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $params = Yii::$app->request->queryParams;
        if (isset($_POST['hasEditable'])) {
            // use Yii's response format to encode output as JSON
            // \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            // var_dump(Yii::$app->request->post('DedFiles'));exit;
            // var_dump(Yii::$app->request->post('editableKey'));exit;
            // var_dump(Yii::$app->request->post());exit;

            $index = Yii::$app->request->post('editableIndex');
            $model = MonitoringPlannerNoteFile::find()->where(['id' => Yii::$app->request->post('editableKey')])->one();

            //edit the description
            $oldDescription = $model->description;
            $model->description = Yii::$app->request->post('MonitoringPlannerNoteFile')[$index]['description'];

            // validate if any errors
            if ($model->save()) {
                // return JSON encoded output in the below format on success with an empty `message`
                return $this->asJson(['output' => $model->description, 'message' => '']);
            } else {
                return $this->asJson(['output' => $oldDescription, 'message' => 'Something is wrong! Please reenter.']);
            }

            return ['output' => '', 'message' => ''];
        }

        $plannerNoteFileModel = new MonitoringPlannerNoteFileSearch();
        $plannerNoteFileDataProvider = $plannerNoteFileModel->search($params, $id);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'url_prev'  => Url::previous(),
            'plannerNoteFileModel' => $plannerNoteFileModel,
            'plannerNoteFileDataProvider' => $plannerNoteFileDataProvider,
        ]);
    }

    /**
     * Creates a new MonitoringPlannerNote model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = new MonitoringPlannerNote();
        $task = MonitoringTask::findOne(['id' => $id]);
        if (!$task) {
            throw new NotFoundHttpException('Task not found');
        }
        $model->monitoring_task_id = $task->id;

        if ($model->load(Yii::$app->request->post())) {
            $files = UploadedFile::getInstances($model, 'file_uploads');
            $successes = [];
            $errors = [];
            $db = $model->getDb();
            $transaction = $db->beginTransaction();
            try {
                if (!$model->save()) {
                    throw new ServerErrorHttpException('Cannot save Planner Note Data');
                }
                foreach ($files as $file) {
                    $notefile = new MonitoringPlannerNoteFile();
                    $notefile->monitoring_planner_note_id = $model->id;
                    if ($file) {
                        $notefile->file_extension = $file->extension;
                        $notefile->file_size = $file->size;
                        if (!$notefile->name) {
                            $notefile->name = $file->name;
                        }
                        $uuid = Uuid::uuid6();
                        $filename = $uuid->toString() . '.' . strtolower($notefile->file_extension);
                        $folderpath = '@upload';
                        $notefile->file_name = $filename;
                        $notefile->file_path = $folderpath . '/' . $filename;
                        if ($file->saveAs(Yii::getAlias($notefile->file_path))) {
                            if ($notefile->save()) {
                                $successes[] = $notefile->name;
                            } else {
                                unlink(Yii::getAlias($notefile->filepath));
                                $notefile->validate();
                                $errors[$notefile->name] = $notefile->errors;
                                throw new ServerErrorHttpException('Cannot save Planner Note File');
                            }
                        } else {
                            $errors[$notefile->name] = $file->error;
                            throw new ServerErrorHttpException('File Error : ' . $file->error);
                        }
                    }
                }
                $transaction->commit();
            } catch (\Throwable $th) {
                $transaction->rollBack();
                throw $th;
            }
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->name]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
        }

        $this->populate_dropdown();
        if (!$model->order_no) {
            if ((int)$model->maxOrder > 0) {
                $model->order_no = (int)$model->maxOrder;
            } else {
                $model->order_no = 1;
            }
        }
        if (!$model->note_date) {
            $model->note_date = date('d-m-Y');
        }
        if (!$model->name) {
            $model->name = '(' . Yii::$app->formatter->asDate(time()) . ')';
        }

        return $this->render('create', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Updates an existing MonitoringPlannerNote model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been updated.', ['name' => $model->name]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($id)]);
        }

        $this->populate_dropdown();

        return $this->render('update', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Deletes an existing MonitoringPlannerNote model.
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
        $notefiles = MonitoringPlannerNoteFile::find()->where(['monitoring_planner_note_id' => $model->id])->all();
        $filepaths = [];
        $db = $model->getDb();
        $transaction = $db->beginTransaction();
        try {
            if (!$model->delete()) {
                throw new ServerErrorHttpException('Planner note not deleted');
            }
            foreach ($notefiles as $notefile) {
                $filepaths[] = $notefile->file_path;
                if (!$notefile->delete()) {
                    throw new ServerErrorHttpException('Planner note file not deleted');
                }
            }
            foreach ($filepaths as $filepath) {
                if (!unlink(Yii::getAlias($filepath))) {
                    throw new ServerErrorHttpException('Planner note file not deleted');
                }
            }
            $transaction->commit();
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->name]));
        } catch (\Throwable $th) {
            $transaction->rollBack();
            throw $th;
        }

        return $this->redirect(['default/view', 'id' => Yii::$app->hasher->encode($task->id)]);
    }

    /**
     * Finds the MonitoringPlannerNote model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MonitoringPlannerNote the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MonitoringPlannerNote::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionReset_session()
    {
        $this->sess->remove('monitoring_planner_note');
        return $this->redirect(['index']);
    }
}
