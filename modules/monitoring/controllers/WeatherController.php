<?php

namespace app\modules\monitoring\controllers;

use app\modules\monitoring\models\MonitoringProgress;
use Yii;

use app\modules\monitoring\models\MonitoringWeather;
use app\modules\monitoring\models\MonitoringWeatherSearch;
use app\modules\references\models\RefCuaca;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/**
 * WeatherController implements the CRUD actions for MonitoringWeather model.
 */
class WeatherController extends Controller
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
                        'roles' => ['Administrator', 'OPD', 'Team', 'Supervisor',],
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
        $ref_cuaca_id = RefCuaca::find()->select(['id', 'name'])->all();
        $this->dropdown_data['ref_cuaca_id'] = ArrayHelper::map($ref_cuaca_id, 'id', 'name');
    }

    /**
     * Lists all MonitoringWeather models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['monitoring_weather'];
            if (isset($this->sess['monitoring_weather']['page']))
                $_GET['page'] = $this->sess['monitoring_weather']['page'];
        } else {
            $this->sess['monitoring_weather'] = $params;
        }

        $searchModel = new MonitoringWeatherSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all MonitoringWeather models.
     * @return mixed
     */
    public function actionIndexView()
    {
        if (Yii::$app->request->isPost) {
            $expandRowKey = Yii::$app->request->getBodyParam('expandRowKey');
            $params = Yii::$app->request->queryParams;

            $searchModel = new MonitoringWeatherSearch();
            $dataProvider = $searchModel->search($params, $expandRowKey, false);

            Url::remember();

            return $this->renderAjax('_index_view', [
                'dataProvider' => $dataProvider,
                'monitoring_progress_id' => $expandRowKey,
            ]);
        }
        return null;
    }

    /**
     * Displays a single MonitoringWeather model.
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
     * Creates a new MonitoringWeather model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = new MonitoringWeather();
        $progress = MonitoringProgress::findOne(['id' => $id]);
        if (!$progress) {
            throw new NotFoundHttpException('Monitoring Progress is not valid');
        }
        $model->monitoring_progress_id = $progress->id;

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

    /**
     * Updates an existing MonitoringWeather model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
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
     * Deletes an existing MonitoringWeather model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);
        $progress = $model->monitoring_progress_id;
        if ($model->delete()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->name]));
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->name]));
        }

        return $this->redirect(['progress/view', 'id' => Yii::$app->hasher->encode($progress)]);
    }

    /**
     * Finds the MonitoringWeather model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MonitoringWeather the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MonitoringWeather::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionReset_session()
    {
        $this->sess->remove('monitoring_weather');
        return $this->redirect(['index']);
    }
}
