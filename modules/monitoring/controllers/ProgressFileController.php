<?php

namespace app\modules\monitoring\controllers;

use app\modules\monitoring\models\MonitoringProgress;
use app\modules\monitoring\models\MonitoringTask;
use app\modules\monitoring\models\MonitoringTaskLocations;
use app\modules\monitoring\models\MonitoringProgressPictures;
use app\modules\monitoring\models\MonitoringProgressPicturesSearch;
use Yii;
use Ramsey\Uuid\Uuid;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * ProgressFileController implements the CRUD actions for MonitoringProgressPictures model.
 */
class ProgressFileController extends Controller
{
    private $dropdown_data;
    private $sess;
    private $uploadPath;

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
                        'roles' => ['Administrator', 'Supervisor'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'reset_session'],
                        'roles' => ['Viewer', 'Eksekutif', 'Planner', 'Eksekutor', 'Supervisor',],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['image', 'picture', 'thumbnail'],
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
        $this->uploadPath = '@upload';
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
     * Lists all MonitoringProgressPictures models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['monitoring_task_pictures'];
            if (isset($this->sess['monitoring_task_pictures']['page']))
                $_GET['page'] = $this->sess['monitoring_task_pictures']['page'];
        } else {
            $this->sess['monitoring_task_pictures'] = $params;
        }

        $searchModel = new MonitoringProgressPicturesSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all MonitoringProgressPictures models.
     * @return mixed
     */
    public function actionIndexView()
    {
        if (Yii::$app->request->isPost) {
            $expandRowKey = Yii::$app->request->getBodyParam('expandRowKey');
            $params = Yii::$app->request->queryParams;

            $searchModel = new MonitoringProgressPicturesSearch();
            $dataProvider = $searchModel->search($params, $expandRowKey, null, null, false);

            Url::remember();

            return $this->renderAjax('_index_view', [
                'dataProvider' => $dataProvider,
                'monitoring_progress_id' => $expandRowKey,
            ]);
        }
        return null;
    }

    /**
     * Displays a single MonitoringProgressPictures model.
     * @param string $id
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
     * Creates a new MonitoringProgressPictures model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $progress = MonitoringProgress::findOne($id);
        $task = MonitoringTask::findOne(['id' => $progress->monitoring_task_id]);
        $model = new MonitoringProgressPictures();
        $model->monitoring_task_id = $task->id;
        $model->monitoring_progress_id = $progress->id;

        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'uploadimage');
            if (!$file) {
                $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> has not been created.', ['name' => $model->name]));
                return $this->redirect(['create', 'id' => Yii::$app->hasher->encode($id)]);
            }
            $fileextension = $file->extension;
            $filename = Uuid::uuid6()->toString() . '.' . $fileextension;
            $model->file_name = $filename;
            $model->file_extension = $fileextension;
            $model->file_size = $file->size;
            $model->file_path = $this->uploadPath . DIRECTORY_SEPARATOR . $filename;
            $filepath = Yii::getAlias($model->file_path);
            $model->file_url = Url::to(['picture', 'id' => $filename]);
            $model->file_thumbnail = Url::to(['thumbnail', 'id' => 'thumbnail-' . $filename]);
            if ($file->saveAs($filepath) && $model->save()) {
                //$this->generateThumbnail($this->uploadPath), $filename) && 
                $model->file_url = Url::to(['picture', 'id' => Yii::$app->hasher->encode($model->id)]);
                $model->file_thumbnail = Url::to(['thumbnail', 'id' => 'thumbnail-' . Yii::$app->hasher->encode($model->id)]);
                $model->save();
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->name]));
                return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
            } else {
                $model->validate();
                return $this->asJson(['model' => $model->errors, 'file' => $file->error]);
                $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> has not been created.', ['name' => $model->name]));
            }
        }

        if (!$model->name) {
            $model->name = '(' . date('Y-m-d') . ')';
        }

        $this->populate_dropdown();
        if (isset($task->locations) && ($task->locations != [])) {
            $this->dropdown_data['task_locations'] = ArrayHelper::map($task->locations, 'id', 'name');
        } else {
            $this->dropdown_data['task_locations'] = [];
        }

        return $this->render('create', [
            'task'          => $task,
            'progress'      => $progress,
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    // public function actionImage($filename = null)
    // {
    //     if (!$filename || !file_exists(Yii::getAlias($this->uploadPath . DIRECTORY_SEPARATOR . $filename))) {
    //         throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    //     }
    //     $response = Yii::$app->response;
    //     $filepath = Yii::getAlias($this->uploadPath . DIRECTORY_SEPARATOR . $filename);
    //     return $response->sendFile($filepath, null, ['inline' => true]);
    // }

    /**
     * Fetch a file of MonitoringProgressPictures model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionFetchFile($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);

        if (!$model->file_path) {
            return $this->actionPicture($id);
        }

        $filepath = Yii::getAlias($model->file_path);
        $filename = $model->name . '.' . $model->file_extension;

        // check filename for allowed chars (do not allow ../ to avoid security issue: downloading arbitrary files)
        if (!is_file($filepath)) {
            throw new \yii\web\NotFoundHttpException('The file does not exists.');
        }

        return Yii::$app->response->sendFile($filepath, str_replace(PHP_EOL, ' ', $filename), ['inline' => true]);
    }

    public function actionPicture($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);
        if (!file_exists(Yii::getAlias($this->uploadPath . DIRECTORY_SEPARATOR . $model->file_name))) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        $response = Yii::$app->response;
        $filepath = Yii::getAlias($this->uploadPath . DIRECTORY_SEPARATOR . $model->file_name);
        return $response->sendFile($filepath, str_replace(PHP_EOL, ' ', $model->name), ['inline' => true]);
    }

    public function actionThumbnail($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);
        if (!file_exists(Yii::getAlias($this->uploadPath . DIRECTORY_SEPARATOR . 'thumbnail-' . $model->file_name))) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
        $response = Yii::$app->response;
        $filepath = Yii::getAlias($this->uploadPath . DIRECTORY_SEPARATOR . 'thumbnail-' . $model->file_name);
        return $response->sendFile($filepath, str_replace(PHP_EOL, ' ', $model->name), ['inline' => true]);
    }

    /**
     * Deletes an existing MonitoringProgressPictures model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);
        $progressid = $model->monitoring_progress_id;
        $filepath = Yii::getAlias($this->uploadPath . DIRECTORY_SEPARATOR . $model->file_name);
        $thumbfilepath = Yii::getAlias($this->uploadPath . DIRECTORY_SEPARATOR . 'thumbnail-' . $model->file_name);
        if ($this->deleteFile($filepath) && $this->deleteFile($thumbfilepath) && $model->delete()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->name]));
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->name]));
        }

        return $this->redirect(['/monitoring/progress/view', 'id' => Yii::$app->hasher->encode($progressid)]);
        // return $this->redirect(['index']);
    }

    private function deleteFile($filepath)
    {
        $is_exist = file_exists($filepath);
        if ($is_exist) {
            return unlink($filepath);
        }
        return true;
    }

    /**
     * Finds the MonitoringProgressPictures model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MonitoringProgressPictures the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MonitoringProgressPictures::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionReset_session()
    {
        $this->sess->remove('monitoring_task_pictures');
        return $this->redirect(['index']);
    }
}
