<?php

namespace app\modules\construction\controllers;

use Yii;

use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use app\queues\QueueSyncRup;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use app\modules\rup\models\Rup;
use yii\web\NotFoundHttpException;
use app\queues\QueueSyncRupPenyedia;
use app\modules\rup\models\RupSearch;
use app\modules\construction\models\ConstructionMarket;
use app\modules\construction\models\ConstructionMarketSearch;
use app\modules\construction\models\ConstructionSafetySearch;
use app\modules\construction\models\ConstructionAccidentSearch;

/**
 * ConstructionMarketController implements the CRUD actions for ConstructionMarket model.
 */
class ConstructionMarketController extends Controller
{
    private $dropdown_data;
    private $sess;
    // private $kode_kota  = 'D147';

    public $url = 'https://isb.lkpp.go.id/isb-2/api/4eb7e158-259e-4a6a-9e20-fdf43ca9f678/json/15105/RUP-PaketPenyedia-Terumumkan/tipe/4:12/parameter/';
    public $year = 2024;
    public $klpd_code = 'D147';

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
                        'roles' => ['Administrator', 'Badan Usaha'],
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
     * Lists all ConstructionMarket models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['rup'];
            if (isset($this->sess['rup']['page']))
                $_GET['page'] = $this->sess['rup']['page'];
        } else {
            $this->sess['rup'] = $params;
        }

        $searchModel = new RupSearch();
        $dataProvider = $searchModel->search($params, true);

        $sumberdanafilter = Rup::find()->select('sumberdana')->distinct()->all();
        $sumberdanafilter = ArrayHelper::map($sumberdanafilter, 'sumberdana', 'sumberdana');
        $statusumumkanfilter = Rup::find()->select('statusumumkan')->distinct()->all();
        $statusumumkanfilter = ArrayHelper::map($statusumumkanfilter, 'statusumumkan', 'statusumumkan');
        $jenispengadaan = Rup::find()->select(['idjenispengadaan', 'jenispengadaan'])->distinct()->all();
        $jenispengadaan = ArrayHelper::map($jenispengadaan, 'idjenispengadaan', 'jenispengadaan');
        if (isset($sumberdanafilter[""])) {
            unset($sumberdanafilter[""]);
        };
        if (isset($statusumumkanfilter[""])) {
            unset($statusumumkanfilter[""]);
        };

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sumberdanafilter' => $sumberdanafilter,
            'statusumumkanfilter' => $statusumumkanfilter,
            'jenispengadaan' => $jenispengadaan,
        ]);
    }
    // public function actionIndex()
    // {

    //     $params = Yii::$app->request->queryParams;

    //     if (count($params) <= 1) {
    //         $params = $this->sess['construction_market'];
    //         if (isset($this->sess['construction_market']['page']))
    //             $_GET['page'] = $this->sess['construction_market']['page'];
    //     } else {
    //         $this->sess['construction_market'] = $params;
    //     }

    //     $searchModel = new ConstructionMarketSearch();
    //     $dataProvider = $searchModel->search($params);

    //     Url::remember();

    //     return $this->render('index', [
    //         'searchModel' => $searchModel,
    //         'dataProvider' => $dataProvider,
    //     ]);
    // }

    /**
     * Displays a single ConstructionMarket model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $params = Yii::$app->request->queryParams;

        $constructionAccidentModel = new ConstructionAccidentSearch();
        $constructionAccidentDataProvider = $constructionAccidentModel->search($params, $id);

        $constructionSafetyModel = new ConstructionSafetySearch();
        $constructionSafetyDataProvider = $constructionSafetyModel->search($params, $id);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'url_prev'  => Url::previous(),
            'constructionAccidentModel' => $constructionAccidentModel,
            'constructionAccidentDataProvider' => $constructionAccidentDataProvider,

            'constructionSafetyModel' => $constructionSafetyModel,
            'constructionSafetyDataProvider' => $constructionSafetyDataProvider,
        ]);
    }

    /**
     * Creates a new ConstructionMarket model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // public function actionCreate()
    // {
    //     $model = new ConstructionMarket();

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->package_name]));
    //         return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
    //     }

    //     $this->populate_dropdown();

    //     return $this->render('create', [
    //         'model'         => $model,
    //         'dropdown_data' => $this->dropdown_data,
    //         'url_prev'      => Url::previous()
    //     ]);
    // }

    /**
     * Updates an existing ConstructionMarket model.
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
    //         $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been updated.', ['name' => $model->package_name]));
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
     * Deletes an existing ConstructionMarket model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionDelete($id)
    // {
    //     $id = Yii::$app->hasher->decode($id);

    //     $model = $this->findModel($id);
    //     if ($model->delete()) {
    //         $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->package_name]));
    //     } else {
    //         $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->package_name]));
    //     }

    //     return $this->redirect(['index']);
    // }

    /**
     * Finds the ConstructionMarket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ConstructionMarket the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Rup::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    // public function actionReset_session()
    // {
    //     $this->sess->remove('construction_market');
    //     return $this->redirect(['index']);
    // }
}