<?php

namespace app\modules\technicalspec\controllers;

use app\modules\monitoring\models\MonitoringProgress;
use app\modules\monitoring\models\MonitoringTask;
use app\modules\technicalspec\models\DedData;
use app\modules\technicalspec\models\DedFiles;
use Yii;

use app\modules\technicalspec\models\MonitoringSpec;
use app\modules\technicalspec\models\MonitoringSpecFiles;
use app\modules\technicalspec\models\MonitoringSpecFilesSearch;
use app\modules\technicalspec\models\MonitoringSpecSearch;
use app\modules\technicalspec\models\RefComponentCategory;
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
 * MonitoringSpecController implements the CRUD actions for MonitoringSpec model.
 */
class MonitoringSpecController extends Controller
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
                        'roles' => ['Administrator', 'OPD', 'Supervisor', 'Planner'],
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
     * Lists all MonitoringSpec models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['monitoring_spec'];
            if (isset($this->sess['monitoring_spec']['page']))
                $_GET['page'] = $this->sess['monitoring_spec']['page'];
        } else {
            $this->sess['monitoring_spec'] = $params;
        }

        $searchModel = new MonitoringSpecSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all PdnRealisasi models.
     * @return mixed
     */
    public function actionIndexView()
    {
        if (Yii::$app->request->isPost) {
            $expandRowKey = Yii::$app->request->getBodyParam('expandRowKey');
            if ($expandRowKey) {
                $searchModel = new MonitoringSpecSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $expandRowKey, false);

                return $this->renderAjax('_index_view', [
                    // 'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'monitoring_progress_id' => $expandRowKey,
                ]);
            }
        }
        return null;
    }

    /**
     * Displays a single MonitoringSpec model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);
        $params = Yii::$app->request->queryParams;

        if (isset($_POST['hasEditable'])) {
            // use Yii's response format to encode output as JSON
            // \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            // var_dump(Yii::$app->request->post('MonitoringSpecFiles'));exit;
            // var_dump(Yii::$app->request->post('editableKey'));exit;
            // var_dump(Yii::$app->request->post());exit;

            $index = Yii::$app->request->post('editableIndex');
            $model = MonitoringSpecFiles::find()->where(['id' => Yii::$app->request->post('editableKey')])->one();

            //edit the description
            $oldDescription = $model->description;
            $model->description = Yii::$app->request->post('MonitoringSpecFiles')[$index]['description'];

            // validate if any errors
            if ($model->save()) {
                // return JSON encoded output in the below format on success with an empty `message`
                return $this->asJson(['output' => $model->description, 'message' => '']);
            } else {
                return $this->asJson(['output' => $oldDescription, 'message' => 'Something is wrong! Please reenter.']);
            }

            return ['output' => '', 'message' => ''];
        }

        $specfileModel = new MonitoringSpecFilesSearch();
        $specfileProvider = $specfileModel->search($params, $id);

        return $this->render('view', [
            'model' => $model,
            'url_prev'  => Url::previous(),
            'specfileModel' => $specfileModel,
            'specfileProvider' => $specfileProvider,
        ]);
    }

    /**
     * Creates a new MonitoringSpec model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($progressid)
    {
        $progressid = Yii::$app->hasher->decode($progressid);
        $model = new MonitoringSpec();
        $progress = MonitoringProgress::findOne(['id' => $progressid]);
        if (!$progress) {
            throw new NotFoundHttpException('Progress Not Found');
        }
        $monitoringtask = MonitoringTask::findOne(['id' => $progress->monitoring_task_id]);

        $model->monitoring_progress_id = $progress->id;
        $model->monitoring_task_id = $progress->monitoring_task_id;

        if ($model->load(Yii::$app->request->post())) {
            $files = UploadedFile::getInstances($model, 'file_uploads');
            $successes = [];
            $errors = [];
            $db = $model->getDb();
            $transaction = $db->beginTransaction();
            try {
                if (!$model->save()) {
                    throw new ServerErrorHttpException('Cannot save Monitoring Spec');
                }
                foreach ($files as $file) {
                    $specfile = new MonitoringSpecFiles();
                    $specfile->monitoring_spec_id = $model->id;
                    if ($file) {
                        $specfile->file_extension = $file->extension;
                        $specfile->file_size = $file->size;
                        if (!$specfile->name) {
                            $specfile->name = $file->name;
                        }
                        $uuid = Uuid::uuid6();
                        $filename = $uuid->toString() . '.' . strtolower($specfile->file_extension);
                        $folderpath = '@upload';
                        $specfile->file_name = $filename;
                        $specfile->file_path = $folderpath . '/' . $filename;
                        if ($file->saveAs(Yii::getAlias($specfile->file_path))) {
                            if ($specfile->save()) {
                                $successes[] = $specfile->name;
                                // $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $dedfile->name]));
                                // return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($dedfile->id)]);
                            } else {
                                unlink(Yii::getAlias($specfile->filepath));
                                $specfile->validate();
                                $errors[$specfile->name] = $specfile->errors;
                                throw new ServerErrorHttpException('Cannot save Monitoring Spec File');
                                // var_dump($dedfile->errors);
                            }
                        } else {
                            $errors[$specfile->name] = $file->error;
                            throw new ServerErrorHttpException('Cannot save Monitoring Spec File');
                            // var_dump($file->error);
                            // exit;
                        }
                    }
                }
                $transaction->commit();
            } catch (\Throwable $th) {
                $transaction->rollBack();
                throw $th;
            }
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->name]));
            // return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
        }

        $this->populate_dropdown();

        $ref_component_category_id = RefComponentCategory::find()->select(['id', 'name'])->where(['ref_objecttype_id' => $monitoringtask->object_type_id])->orderBy('name')->all();
        $this->dropdown_data['ref_component_category_id'] = ArrayHelper::map($ref_component_category_id, 'id', 'name');

        $modelded = null;

        if (!$model->name) {
            $model->name = '(' . Yii::$app->formatter->asDate(time()) . ') Spesifikasi Teknis';
        }

        if (Yii::$app->request->isAjax) {
            $modelded = $this->actionDataDed(Yii::$app->request->post('id')); //untuk mengakomodasi request change baru
        }

        return $this->render('create', [
            'model'         => $model,
            'modelded'      => $modelded,
            'monitoringtask' => $monitoringtask,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous(),
            'progress'      => $progress,
        ]);
    }

    /**
     * Creates a new MonitoringSpec model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAddFiles($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);

        $progress = MonitoringProgress::findOne(['id' => $model->monitoring_progress_id]);
        if (!$progress) {
            throw new NotFoundHttpException('Progress Not Found');
        }
        $monitoringtask = MonitoringTask::findOne(['id' => $progress->monitoring_task_id]);

        $model->monitoring_progress_id = $progress->id;
        $model->monitoring_task_id = $progress->monitoring_task_id;

        if ($model->load(Yii::$app->request->post())) {
            $files = UploadedFile::getInstances($model, 'file_uploads');
            $successes = [];
            $errors = [];
            $db = $model->getDb();
            $transaction = $db->beginTransaction();
            try {
                foreach ($files as $file) {
                    $specfile = new MonitoringSpecFiles();
                    $specfile->monitoring_spec_id = $model->id;
                    $specfile->description = $model->description;
                    if ($file) {
                        $specfile->file_extension = $file->extension;
                        $specfile->file_size = $file->size;
                        if (!$specfile->name) {
                            $specfile->name = $file->name;
                        }
                        $uuid = Uuid::uuid6();
                        $filename = $uuid->toString() . '.' . strtolower($specfile->file_extension);
                        $folderpath = '@upload';
                        $specfile->file_name = $filename;
                        $specfile->file_path = $folderpath . '/' . $filename;
                        if ($file->saveAs(Yii::getAlias($specfile->file_path))) {
                            if ($specfile->save()) {
                                $successes[] = $specfile->name;
                                // $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $dedfile->name]));
                                // return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($dedfile->id)]);
                            } else {
                                unlink(Yii::getAlias($specfile->filepath));
                                $specfile->validate();
                                $errors[$specfile->name] = $specfile->errors;
                                // var_dump($dedfile->errors);
                            }
                        } else {
                            $errors[$specfile->name] = $file->error;
                            // var_dump($file->error);
                            // exit;
                        }
                    }
                }
                $transaction->commit();
            } catch (\Throwable $th) {
                $transaction->rollBack();
                throw $th;
            }
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->name]));
            // return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
        }

        $this->populate_dropdown();

        return $this->render('add_files', [
            'model'         => $model,
            'monitoringtask' => $monitoringtask,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous(),
            'progress'      => $progress,
        ]);
    }

    /**
     * Updates an existing MonitoringSpec model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);

        $progress = MonitoringProgress::findOne(['id' => $model->monitoring_progress_id]);
        if (!$progress) {
            throw new NotFoundHttpException('Progress Not Found');
        }
        $monitoringtask = MonitoringTask::findOne(['id' => $progress->monitoring_task_id]);

        $model->monitoring_progress_id = $progress->id;
        $model->monitoring_task_id = $progress->monitoring_task_id;
        $model->ref_component_category_id = $model->dedData->ref_component_category_id;
        $model->ref_component_id = $model->dedData->ref_component_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been updated.', ['name' => $model->name]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($id)]);
        }

        $this->populate_dropdown();

        $ref_component_category_id = RefComponentCategory::find()->select(['id', 'name'])->where(['ref_objecttype_id' => $monitoringtask->object_type_id])->orderBy('name')->all();
        $this->dropdown_data['ref_component_category_id'] = ArrayHelper::map($ref_component_category_id, 'id', 'name');

        $modelded = $this->actionDataDed($model->ded_data_id); //set id berdasar data awal

        if (Yii::$app->request->isAjax) {
            $modelded = $this->actionDataDed(Yii::$app->request->post('id')); //untuk mengakomodasi request change baru
        }

        return $this->render('update', [
            'model'         => $model,
            'modelded'      => $modelded,
            'monitoringtask' => $monitoringtask,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous(),
            'progress'      => $progress,
        ]);
    }

    /**
     * Deletes an existing MonitoringSpec model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);
        $monitoring_progress_id = $model->monitoring_progress_id;
        // Cek kalau file masih ada.

        if ($model->delete()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->name]));
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->name]));
        }

        return $this->redirect(['/monitoring/progress/view', 'id' => Yii::$app->hasher->encode($monitoring_progress_id)]);
    }

    /**
     * Get Data by Pjax for DED
     * @return mixed
     */
    public function actionDataDed($id)
    {
        $model_a = null;
        if ($id) {
            $model_a = DedData::find()->where('id = :id', [':id' => $id])->one();
        }
        return $model_a;
    }

    /**
     * Finds the MonitoringSpec model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MonitoringSpec the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MonitoringSpec::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionReset_session()
    {
        $this->sess->remove('monitoring_spec');
        return $this->redirect(['index']);
    }
}
