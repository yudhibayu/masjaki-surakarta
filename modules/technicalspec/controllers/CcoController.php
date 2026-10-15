<?php

namespace app\modules\technicalspec\controllers;

use app\modules\monitoring\models\MonitoringTask;
use app\modules\technicalspec\models\DedData;
use Yii;

use app\modules\technicalspec\models\McoCco;
use app\modules\technicalspec\models\McoCcoFile;
use app\modules\technicalspec\models\McoCcoFileSearch;
use app\modules\technicalspec\models\McoCcoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yii\base\UserException;
use Ramsey\Uuid\Uuid;
use yii\web\ServerErrorHttpException;

/**
 * CcoController implements the CRUD actions for McoCco model.
 */
class CcoController extends Controller
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
                    'create-cco' => ['POST'],
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
                        'roles' => ['Administrator'],
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
        $monitoring_task_id = MonitoringTask::find()->select(['id', 'name'])->orderBy('idrup')->all();
        $this->dropdown_data['monitoring_task_id'] = ArrayHelper::map($monitoring_task_id, 'id', 'name');
    }

    /**
     * Lists all McoCco models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->redirect(['/monitoring/default/index']);
        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['mco_cco'];
            if (isset($this->sess['mco_cco']['page']))
                $_GET['page'] = $this->sess['mco_cco']['page'];
        } else {
            $this->sess['mco_cco'] = $params;
        }

        $searchModel = new McoCcoSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single McoCco model.
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
     * Creates a new McoCco model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new McoCco();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->name]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
        }

        $this->populate_dropdown();

        return $this->render('create', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    public function actionCreateCco($taskid = null)
    {
        if (!$taskid) {
            throw new NotFoundHttpException('Task not found');
        }
        $taskid = Yii::$app->hasher->decode($taskid);
        $task = MonitoringTask::findOne(['id' => $taskid]);
        if (!$task) {
            throw new NotFoundHttpException('Task not found');
        }
        $newcco = new McoCco();
        $transaction = $newcco->getDb()->beginTransaction();
        try {
            $checklastcco = McoCco::find()->where(['monitoring_task_id' => $taskid])->orderBy(['cco' => SORT_DESC])->one();
            if (!$checklastcco) {
                $newcco->cco = 0;
                $newcco->monitoring_task_id = $task->id;
                $newcco->name = 'MCO';
            } else {
                $newcco->cco = (int)$checklastcco->cco + 1;
                $newcco->monitoring_task_id = $task->id;
                $newcco->name = 'CCO ' . $newcco->cco;
            }
            if (!$newcco->save()) {
                $newcco->validate();
                throw new ServerErrorHttpException('Error while saving data : ' . json_encode($newcco->errors));
            }
            $transaction->commit();
            return $this->redirect(['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($taskid)]);
        } catch (\Throwable $th) {
            $transaction->rollBack();
            throw $th;
        }
    }

    /**
     * Updates an existing McoCco model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionUpdate($id)
    // {
    //     $id = Yii::$app->hasher->decode($id);

    //     $model = $this->findModel($id);

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been updated.', ['name' => $model->name]));
    //         return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($id)]);
    //     }

    //     $this->populate_dropdown();

    //     return $this->render('update', [
    //         'model'         => $model,
    //         'dropdown_data' => $this->dropdown_data,
    //         'url_prev'      => Url::previous()
    //     ]);
    // }

    /**
     * Deletes an existing McoCco model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);
        $monitoring_task_id = $model->monitoring_task_id;

        $deddata = DedData::find()->where(['mco_cco_id' => $model->id])->one();
        $mcoccofile = McoCcoFile::find()->where(['mco_cco_id' => $model->id])->all();
        if (!$deddata && $model->delete()) {
            if ($mcoccofile) {
                foreach ($mcoccofile as $mcfile) {
                    $mcfile->delete();
                    $mcfile->removeFile();
                }
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->name]));
            }
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->name]));
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->name]));
        }

        return $this->redirect(['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($monitoring_task_id)]);
    }

    /**
     * Lists all McoCcoFile models.
     * @return mixed
     */
    public function actionIndexView()
    {
        if (Yii::$app->request->isPost) {
            $expandRowKey = Yii::$app->request->getBodyParam('expandRowKey');
            if ($expandRowKey) {
                $searchModel = new McoCcoFileSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $expandRowKey, false);

                return $this->renderPartial('_index_view', [
                    // 'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'mco_cco_id' => $expandRowKey,
                ]);
            }
        }
        return null;
    }

    /**
     * show file upload from McoCcoFile model.
     * If show is successful, the browser will be show file upload.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionFileItem($id = null)
    {
        $path = McoCcoFile::filePath();
        $this->showfile($path, $id);
    }

    private function showfile($path, $id = null)
    {
        if (!$id) {
            throw new NotFoundHttpException('file not found');
        } else {
            $response = Yii::$app->response;
            $filepath = $path . $id;
            if (!is_file($filepath) || !preg_match('/^[a-zA-Z0-9_.-]*$/i', $id)) {
                throw new NotFoundHttpException('file not found');
            } else {
                $response->sendFile($filepath, null, ['inline' => true]);
            }
        }
    }

    /**
     * Add an existing McoCcoFile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAddFile($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = new McoCcoFile();
        $id_monitoring_task = McoCco::findOne($id)->monitoring_task_id;

        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'file_name');
            if ($file) {
                $uuid = Uuid::uuid6();
                $newname = $uuid->toString() . '.' . $file->extension;
                $model->file_name = $newname;
                $file->saveAs($model->filePath() . $newname);
                $model->mco_cco_id = $id;
                $model->monitoring_task_id = McoCco::findOne($id)->monitoring_task_id;
                if (!$model->save()) {
                    $model->validate();
                    throw new UserException(json_encode($model->errors));
                }

                Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been created'));
                return $this->redirect(['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]);
            } else {
                $model->monitoring_task_id = $id;
                $model->save();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been created but your file is not uploaded'));
                return $this->redirect(['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]);
            }
        }

        return $this->render('add-file', [
            'model'         => $model,
            'id_monitoring_task' =>  $id_monitoring_task
        ]);
    }

    /**
     * Updates an existing McoCcoFile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateFile($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = McoCcoFile::findOne($id);
        $id_monitoring_task = $model->monitoring_task_id;
        $olddraft = $model->file_name;

        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'file_name');
            if (!empty($file)) {
                $uuid = Uuid::uuid6();
                $newname = $uuid->toString() . '.' . $file->extension;
                $model->mco_cco_id = $model->mco_cco_id;
                $model->monitoring_task_id = McoCco::findOne($model->mco_cco_id)->monitoring_task_id;
                $model->file_name = $newname;
                $file->saveAs($model->filePath() . $newname);
                if (!$model->save()) {
                    $model->validate();
                    throw new UserException(json_encode($model->errors));
                }
                if ($olddraft != NULL) {
                    if (file_exists(Yii::getAlias('@upload') . '/'  . $olddraft)) {
                        unlink(Yii::getAlias('@upload') . '/' . $olddraft);
                    }
                }
            } else {
                $model->file_name = $olddraft;
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Record has been updated.'));
                return $this->redirect(['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]);
            }
        }

        $this->populate_dropdown();

        return $this->render('update-file', [
            'model'         => $model,
            'id_monitoring_task' =>  $id_monitoring_task
        ]);
    }

    /**
     * Deletes an existing McoCcoFile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteFile($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = McoCcoFile::findOne($id);
        if ($model->delete()) {
            if ($model->removeFile()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Record successfully deleted.'));
            } else {
            }
            Yii::$app->session->setFlash('success', Yii::t('app', 'Record successfully deleted.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Record can not be deleted.'));
        }

        return $this->redirect(['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]);
    }

    /**
     * Finds the McoCco model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return McoCco the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = McoCco::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionReset_session()
    {
        $this->sess->remove('mco_cco');
        return $this->redirect(['index']);
    }
}
