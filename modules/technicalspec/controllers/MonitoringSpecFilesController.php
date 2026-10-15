<?php

namespace app\modules\technicalspec\controllers;

use app\modules\technicalspec\models\MonitoringSpec;
use Yii;

use app\modules\technicalspec\models\MonitoringSpecFiles;
use app\modules\technicalspec\models\MonitoringSpecFilesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/**
 * MonitoringSpecFilesController implements the CRUD actions for MonitoringSpecFiles model.
 */
class MonitoringSpecFilesController extends Controller
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
                        'roles' => ['Administrator', 'OPD', 'Planner', 'Supervisor'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'reset_session'],
                        'roles' => ['Viewer'],
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


    }

    /**
     * Lists all MonitoringSpecFiles models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['monitoring_spec_files'];
            if (isset($this->sess['monitoring_spec_files']['page']))
                $_GET['page'] = $this->sess['monitoring_spec_files']['page'];
        } else {
            $this->sess['monitoring_spec_files'] = $params;
        }

        $searchModel = new MonitoringSpecFilesSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MonitoringSpecFiles model.
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
     * Fetch a file of MonitoringSpecFiles model.
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
     * Creates a new MonitoringSpecFiles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($specid)
    {
        $specid = Yii::$app->hasher->decode($specid);
        $model = new MonitoringSpecFiles();
        $spec = MonitoringSpec::findOne(['id' => $specid]);
        if (!$spec) {
            throw new NotFoundHttpException('Monitoring Spec Not Found');
        }
        $model->monitoring_spec_id = $spec->id;

        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->name]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
        }

        $this->populate_dropdown();

        return $this->render('create', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous(),
            'spec'          => $spec,
        ]);
    }

    /**
     * Updates an existing MonitoringSpecFiles model.
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
     * Deletes an existing MonitoringSpecFiles model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);
        $monitoring_spec_id = $model->monitoring_spec_id;

        try {
            if (unlink(Yii::getAlias($model->file_path)) && $model->delete()) {
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->name]));
            } else {
                $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->name]));
            }
        } catch (\Throwable $th) {
            throw $th;
        }

        return $this->redirect(['monitoring-spec/view', 'id' => Yii::$app->hasher->encode($monitoring_spec_id)]);
    }

    /**
     * Finds the MonitoringSpecFiles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MonitoringSpecFiles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MonitoringSpecFiles::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionReset_session()
    {
        $this->sess->remove('monitoring_spec_files');
        return $this->redirect(['index']);
    }
}
