<?php

namespace app\modules\technicalspec\controllers;

use app\modules\monitoring\models\MonitoringTask;
use app\modules\references\models\RefSatuan;
use Yii;

use app\modules\technicalspec\models\DedData;
use app\modules\technicalspec\models\DedDataSearch;
use app\modules\technicalspec\models\DedFiles;
use app\modules\technicalspec\models\DedFilesSearch;
use app\modules\technicalspec\models\McoCco;
use app\modules\technicalspec\models\RefComponent;
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
 * DefaultController implements the CRUD actions for DedData model.
 */
class DefaultController extends Controller
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
                        'roles' => ['Administrator', 'OPD', 'Planner'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'reset_session'],
                        'roles' => ['Viewer', 'Eksekutif', 'Planner', 'Eksekutor', 'Supervisor',],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete'],
                        'roles' => ['Administrator', 'OPD', 'Planner'],
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

        // $monitoring_task_id = MonitoringTask::find()->select(['id', 'name'])->where(['like', 'fiscal_year', $this->year])->orderBy('idrup')->all();
        // $this->dropdown_data['monitoring_task_id'] = ArrayHelper::map($monitoring_task_id, 'id', 'name');
        $ref_component_category_id = RefComponentCategory::find()->select(['id', 'name'])->orderBy('name')->all();
        $this->dropdown_data['ref_component_category_id'] = ArrayHelper::map($ref_component_category_id, 'id', 'name');
        // $ref_component_id = RefComponent::find()->orderBy('name')->all();
        // $this->dropdown_data['ref_component_id'] = ArrayHelper::map($ref_component_id, 'id', 'name');
        $ref_satuan_id = RefSatuan::find()->select(['id', 'name'])->orderBy('name')->all();
        $this->dropdown_data['ref_satuan_id'] = ArrayHelper::map($ref_satuan_id, 'id', 'name');
    }

    /**
     * Lists all DedData models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['ded_data'];
            if (isset($this->sess['ded_data']['page']))
                $_GET['page'] = $this->sess['ded_data']['page'];
        } else {
            $this->sess['ded_data'] = $params;
        }

        $searchModel = new DedDataSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        $this->populate_dropdown();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dropdown_data' => $this->dropdown_data,
        ]);
    }

    public function actionDepdropByComponentTask()
    {
        if (Yii::$app->request->post('depdrop_parents')) {
            // var_dump(Yii::$app->request->post('depdrop_parents'));
            // exit;
            $ids = Yii::$app->request->post('depdrop_parents');
            $id0 = $ids[0];
            $id1 = end($ids);
            $component = RefComponent::findOne(['id' => $id0]);
            $monitoring = MonitoringTask::findOne(['id' => $id1]);
            if ($component && $monitoring) {
                $list = DedData::find()
                    ->where('ref_component_id=:id0', [':id0' => $component->id])
                    ->andWhere('monitoring_task_id=:id1', [':id1' => $monitoring->id])
                    ->orderBy('name')->all();
                $list = ArrayHelper::map($list, 'id', 'name');
                $selected  = null;
                if ($id0 != null && count($list) > 0) {
                    $selected = '';
                    foreach ($list as $i => $data) {
                        $out[] = ['id' => $i, 'name' => $data];
                    }
                    if ($i == 0) {
                        $selected = $i;
                    }


                    return $this->asJson(['output' => $out, 'selected' => $selected]);
                }
            }
        }
        return $this->asJson(['output' => '', 'selected' => '']);
    }

    /**
     * Displays a single DedData model.
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

            $index = Yii::$app->request->post('editableIndex');
            $model = DedFiles::find()->where(['id' => Yii::$app->request->post('editableKey')])->one();

            //edit the description
            $oldDescription = $model->description;
            $model->description = Yii::$app->request->post('DedFiles')[$index]['description'];

            // validate if any errors
            if ($model->save()) {
                // return JSON encoded output in the below format on success with an empty `message`
                return $this->asJson(['output' => $model->description, 'message' => '']);
            } else {
                return $this->asJson(['output' => $oldDescription, 'message' => 'Something is wrong! Please reenter.']);
            }

            return ['output' => '', 'message' => ''];
        }

        $fileModel = new DedFilesSearch();
        $fileProvider = $fileModel->search($params, $model->id);

        return $this->render('view', [
            'model' => $model,
            'fileModel' => $fileModel,
            'fileProvider' => $fileProvider,
            'url_prev'  => Url::previous()
        ]);
    }

    /**
     * Creates a new DedData model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($taskid = null)
    {
        $model = new DedData();

        $taskid = Yii::$app->hasher->decode($taskid);
        $task = MonitoringTask::findOne(['id' => $taskid]);
        if (!$task) {
            throw new NotFoundHttpException('Monitoring Task Not Found');
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
                    throw new ServerErrorHttpException('Cannot save DED Data');
                }
                foreach ($files as $file) {
                    $dedfile = new DedFiles();
                    $dedfile->ded_data_id = $model->id;
                    if ($file) {
                        $dedfile->file_extension = $file->extension;
                        $dedfile->file_size = $file->size;
                        if (!$dedfile->name) {
                            $dedfile->name = $file->name;
                        }
                        $uuid = Uuid::uuid6();
                        $filename = $uuid->toString() . '.' . strtolower($dedfile->file_extension);
                        $folderpath = '@upload';
                        $dedfile->file_name = $filename;
                        $dedfile->file_path = $folderpath . '/' . $filename;
                        if ($file->saveAs(Yii::getAlias($dedfile->file_path))) {
                            if ($dedfile->save()) {
                                $successes[] = $dedfile->name;
                                // $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $dedfile->name]));
                                // return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($dedfile->id)]);
                            } else {
                                unlink(Yii::getAlias($dedfile->filepath));
                                $dedfile->validate();
                                $errors[$dedfile->name] = $dedfile->errors;
                                throw new ServerErrorHttpException('Cannot save DED File');
                                // var_dump($dedfile->errors);
                            }
                        } else {
                            $errors[$dedfile->name] = $file->error;
                            throw new ServerErrorHttpException('File Error : ' . $file->error);
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
            return $this->redirect(['/monitoring/default/view', 'id' => Yii::$app->hasher->encode($model->monitoring_task_id)]);
        }

        $this->populate_dropdown();
        $ccos = McoCco::find()->where(['monitoring_task_id' => $task->id])->orderBy(['cco' => SORT_DESC])->all();
        $this->dropdown_data['mco_cco_id'] = ArrayHelper::map($ccos, 'id', 'name');
        $ref_component_category_id = RefComponentCategory::find()->select(['id', 'name'])->where(['ref_objecttype_id' => $task->object_type_id])->orderBy('name')->all();
        $this->dropdown_data['ref_component_category_id'] = ArrayHelper::map($ref_component_category_id, 'id', 'name');

        return $this->render('create', [
            'task'          => $task,
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Updates an existing DedData model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);
        $task = MonitoringTask::findOne(['id' => $model->monitoring_task_id]);
        if (!$task) {
            throw new NotFoundHttpException('Monitoring Task Not Found');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been updated.', ['name' => $model->name]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($id)]);
        }

        $this->populate_dropdown();
        $ccos = McoCco::find()->where(['monitoring_task_id' => $model->monitoring_task_id])->orderBy(['cco' => SORT_DESC])->all();
        $this->dropdown_data['mco_cco_id'] = ArrayHelper::map($ccos, 'id', 'name');
        $ref_component_category_id = RefComponentCategory::find()->select(['id', 'name'])->where(['ref_objecttype_id' => $task->object_type_id])->orderBy('name')->all();
        $this->dropdown_data['ref_component_category_id'] = ArrayHelper::map($ref_component_category_id, 'id', 'name');

        return $this->render('update', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Deletes an existing DedData model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);
        if ($model->delete()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->name]));
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->name]));
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the DedData model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DedData the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DedData::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionReset_session()
    {
        $this->sess->remove('ded_data');
        return $this->redirect(['index']);
    }
}
