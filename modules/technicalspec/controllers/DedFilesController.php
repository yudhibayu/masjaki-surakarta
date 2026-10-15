<?php

namespace app\modules\technicalspec\controllers;

use app\modules\monitoring\models\MonitoringTask;
use app\modules\technicalspec\models\DedData;
use Yii;

use app\modules\technicalspec\models\DedFiles;
use app\modules\technicalspec\models\DedFilesSearch;
use app\modules\technicalspec\models\RefComponent;
use app\modules\technicalspec\models\RefComponentCategory;
use Ramsey\Uuid\Uuid;
use yii\base\DynamicModel;
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
 * DedFilesController implements the CRUD actions for DedFiles model.
 */
class DedFilesController extends Controller
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
                    'index-view' => ['POST'],
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
                        'roles' => ['Viewer', 'Planner', 'Supervisor',],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'bulk-create', 'update', 'delete', 'index-view'],
                        'roles' => ['Planner'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['fetch-file'],
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
        $ref_component_category_id = RefComponentCategory::find()->select(['id', 'name'])->orderBy('name')->all();
        $this->dropdown_data['ref_component_category_id'] = ArrayHelper::map($ref_component_category_id, 'id', 'name');
        $ref_component_id = RefComponent::find()->orderBy('name')->all();
        $this->dropdown_data['ref_component_id'] = ArrayHelper::map($ref_component_id, 'id', 'name');
        $ded_data_id = DedData::find()->orderBy('name')->all();
        $this->dropdown_data['ded_data_id'] = ArrayHelper::map($ded_data_id, 'id', 'name');
    }

    /**
     * Lists all DedFiles models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['ded_files'];
            if (isset($this->sess['ded_files']['page']))
                $_GET['page'] = $this->sess['ded_files']['page'];
        } else {
            $this->sess['ded_files'] = $params;
        }

        $searchModel = new DedFilesSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DedFiles model.
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
     * Fetch a file of DedFiles model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionFetchFile($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);

        $filepath = Yii::getAlias($model->file_path);
        $filename = $model->name . '.' . $model->file_extension;

        // check filename for allowed chars (do not allow ../ to avoid security issue: downloading arbitrary files)
        if (!is_file($filepath)) {
            throw new \yii\web\NotFoundHttpException('The file does not exists.');
        }

        return Yii::$app->response->sendFile($filepath, $filename, ['inline' => true]);
    }

    /**
     * Creates a new DedFiles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($dedid = null)
    {
        $model = new DedFiles();
        $dedid = Yii::$app->hasher->decode($dedid);
        $ded = DedData::findOne(['id' => $dedid]);
        if (!$ded) {
            throw new NotFoundHttpException('DED not Found');
        }
        $model->ded_data_id = $ded->id;

        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'file_upload');
            if ($file) {
                $model->file_extension = $file->extension;
                $model->file_size = $file->size;
                if (!$model->name) {
                    $model->name = $file->name;
                }
                $uuid = Uuid::uuid6();
                $filename = $uuid->toString() . '.' . strtolower($model->file_extension);
                $folderpath = '@upload';
                $model->file_path = $folderpath . '/' . $filename;
                $model->file_name = $filename;
                if ($file->saveAs(Yii::getAlias($model->file_path))) {
                    if ($model->save()) {
                        $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->name]));
                        return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
                    } else {
                        unlink(Yii::getAlias($model->filepath));
                        $model->validate();
                        var_dump($model->errors);
                    }
                } else {
                    var_dump($file->error);
                    exit;
                }
            }
        }

        $this->populate_dropdown();

        return $this->render('create', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'ded'           => $ded,
            'url_prev'      => Url::previous()
        ]);
    }

    public function actionBulkCreate($dedid = null)
    {
        $dedid = Yii::$app->hasher->decode($dedid);
        $ded = DedData::findOne(['id' => $dedid]);
        if (!$ded) {
            throw new NotFoundHttpException('DED not Found');
        }

        $model = new DynamicModel([
            'id',
            'name',
            'file_uploads',
            'description',
        ]);

        $model->setAttributeLabels([
            'id' => Yii::t('app', 'DED ID'),
            'name' => Yii::t('technicalspec', 'DED Name'),
            'file_uploads' => Yii::t('app', 'File Uploads'),
            'description' => Yii::t('technicalspec', 'Description'),
        ]);

        $model->addRule(['id'], 'number');
        $model->addRule(['description'], 'trim');
        $model->addRule(['name', 'description'], 'string');
        $model->id = $ded->id;
        $model->name = $ded->name;

        if ($model->load(Yii::$app->request->post())) {
            $files = UploadedFile::getInstances($model, 'file_uploads');
            $successes = [];
            $errors = [];
            $db = DedFiles::getDb();
            $transaction = $db->beginTransaction();
            try {
                foreach ($files as $file) {
                    $dedfile = new DedFiles();
                    $dedfile->ded_data_id = $model->id;
                    $dedfile->description = $model->description;
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
                            } else {
                                unlink(Yii::getAlias($dedfile->filepath));
                                $dedfile->validate();
                                $errors[$dedfile->name] = $dedfile->errors;
                                throw new ServerErrorHttpException('Cannot save DED File');
                            }
                        } else {
                            $errors[$dedfile->name] = $file->error;
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
            return $this->redirect(['default/view', 'id' => Yii::$app->hasher->encode($model->id)]);
        }

        $this->populate_dropdown();

        return $this->render('create_bulk', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'ded'           => $ded,
            'task'          => $ded->monitoringTask,
            'url_prev'      => Url::previous()
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
                $searchModel = new DedFilesSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $expandRowKey, false);

                return $this->renderPartial('_index_view', [
                    // 'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'ded_data_id' => $expandRowKey,
                ]);
            }
        }
        return null;
    }

    /**
     * Updates an existing DedFiles model.
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
     * Deletes an existing DedFiles model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);
        $ded = $model->ded_data_id;
        try {
            if (unlink(Yii::getAlias($model->file_path)) && $model->delete()) {
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->name]));
            } else {
                $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->name]));
            }
        } catch (\Throwable $th) {
            throw $th;
        }

        return $this->redirect(['default/view', 'id' => Yii::$app->hasher->encode($ded)]);
    }

    /**
     * Finds the DedFiles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DedFiles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DedFiles::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionReset_session()
    {
        $this->sess->remove('ded_files');
        return $this->redirect(['index']);
    }
}
