<?php

namespace app\modules\businessEntity\controllers;

use Yii;

use DateTime;
use DateInterval;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\modules\referencesMasjaki\models\RefNegara;
use app\modules\businessEntity\models\Administrator;
use app\modules\referencesMasjaki\models\RefKabkota;
use app\modules\referencesMasjaki\models\RefProvinsi;
use app\modules\referencesMasjaki\models\RefKecamatan;
use app\modules\referencesMasjaki\models\RefKelurahan;
use app\modules\businessEntity\models\AdministratorSearch;
use app\modules\referencesMasjaki\models\TypeOfManagement;

/**
 * AdministratorController implements the CRUD actions for Administrator model.
 */
class AdministratorController extends Controller
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
                        'roles' => ['Badan Usaha'],
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


        $type_of_management = TypeOfManagement::find()->orderBy('name')->all();
        $this->dropdown_data['type_of_management'] = ArrayHelper::map($type_of_management, 'id', 'name');
        $ref_negara = RefNegara::find()->orderBy('name')->all();
        $this->dropdown_data['ref_negara'] = ArrayHelper::map($ref_negara, 'id', 'name');
        //monggo


    }

    /**
     * Lists all Administrator models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['administrator'];
            if (isset($this->sess['administrator']['page']))
                $_GET['page'] = $this->sess['administrator']['page'];
        } else {
            $this->sess['administrator'] = $params;
        }

        $searchModel = new AdministratorSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Administrator model.
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
     * Creates a new Administrator model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model_administrator = new Administrator();
        $model_administrator->id_business_entity = $id;
        $model_administrator->served_since = date('d-m-Y');

        $date = new DateTime();
        $date->add(new DateInterval('P30D'));
        $model_administrator->served_arrived = $date->format('d-m-Y');

        if ($model_administrator->load(Yii::$app->request->post()) && $model_administrator->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been added.', ['name' => $model_administrator->name]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model_administrator->id)]);
        }

        $this->populate_dropdown();

        return $this->render('create', [
            'model_administrator'         => $model_administrator,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Updates an existing Administrator model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model_administrator = $this->findModel($id);

        if ($model_administrator->load(Yii::$app->request->post()) && $model_administrator->save()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been updated.', ['name' => $model_administrator->name]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($id)]);
        }

        $this->populate_dropdown();

        return $this->render('update', [
            'model_administrator'         => $model_administrator,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Deletes an existing Administrator model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $parent)
    {
        $parent = Yii::$app->hasher->decode($parent);

        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);
        if ($model->delete()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->name]));
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->name]));
        }

        return $this->redirect(['business-entity/view', 'id' => Yii::$app->hasher->encode($parent)]);
    }

    /**
     * Finds the Administrator model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Administrator the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Administrator::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionReset_session()
    {
        $this->sess->remove('administrator');
        return $this->redirect(['index']);
    }
}
