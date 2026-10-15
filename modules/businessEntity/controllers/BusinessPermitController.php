<?php

namespace app\modules\businessEntity\controllers;

use app\modules\businessEntity\models\BusinessClassification;
use app\modules\businessEntity\models\BusinessClassificationSearch;
use Yii;

use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\modules\businessEntity\models\BusinessPermit;
use app\modules\businessEntity\models\PhotoBusinessPermit;
use app\modules\businessEntity\models\BusinessPermitSearch;
use app\modules\businessEntity\models\PhotoBusinessPermitSearch;
use app\modules\referencesMasjaki\models\ConstructionSbu;
use app\modules\referencesMasjaki\models\TypeOfBusinessLicense;

/**
 * BusinessPermitController implements the CRUD actions for BusinessPermit model.
 */
class BusinessPermitController extends Controller
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

        $business_permit_type = TypeOfBusinessLicense::find()->orderBy('name')->all();
        $this->dropdown_data['business_permit_type'] = ArrayHelper::map($business_permit_type, 'id', 'name');

        $construction_sbu = ConstructionSbu::find()->orderBy('name')->all();
        $this->dropdown_data['construction_sbu'] = ArrayHelper::map($construction_sbu, 'id', 'name');

        //monggo


    }

    /**
     * Lists all BusinessPermit models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['business_permit'];
            if (isset($this->sess['business_permit']['page']))
                $_GET['page'] = $this->sess['business_permit']['page'];
        } else {
            $this->sess['business_permit'] = $params;
        }

        $searchModel = new BusinessPermitSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BusinessPermit model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $params = Yii::$app->request->queryParams;
        $photoBusinessPermitModel = new PhotoBusinessPermitSearch();
        $photoBusinessPermitDataProvider = $photoBusinessPermitModel->search($params, $id);
        $businessClassificationModel = new BusinessClassificationSearch();
        $businessClassificationDataProvider = $businessClassificationModel->search($params, $id);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'url_prev'  => Url::previous(),
            'photoBusinessPermitModel' => $photoBusinessPermitModel,
            'photoBusinessPermitDataProvider' => $photoBusinessPermitDataProvider,
            'businessClassificationModel' => $businessClassificationModel,
            'businessClassificationDataProvider' => $businessClassificationDataProvider,
        ]);
    }

    /**
     * Creates a new BusinessPermit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model_business_permit = new BusinessPermit();
        $model_business_permit->id_business_entity = $id;
        $model_business_permit->valid_until = date('Y-m-d');

        if ($model_business_permit->load(Yii::$app->request->post()) && $model_business_permit->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been added.', ['name' => $model_business_permit->reference_number]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model_business_permit->id)]);
        }

        $this->populate_dropdown();

        return $this->render('create', [
            'model_business_permit'         => $model_business_permit,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Updates an existing BusinessPermit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model_business_permit = $this->findModel($id);

        if ($model_business_permit->load(Yii::$app->request->post()) && $model_business_permit->save()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been updated.', ['name' => $model_business_permit->reference_number]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($id)]);
        }

        $this->populate_dropdown();

        return $this->render('update', [
            'model_business_permit'         => $model_business_permit,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Deletes an existing BusinessPermit model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $parent)
    {
        $parent = Yii::$app->hasher->decode($parent);
        $id = Yii::$app->hasher->decode($id);

        $photoBusinessPermit = PhotoBusinessPermit::find()->where(['id_business_permit' => $id])->all();

        $model = $this->findModel($id);

        if (!empty($photoBusinessPermit)) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Data cannot be deleted because there is child data inside.'));
        } else {
            if ($model->delete()) {
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->reference_number]));
            } else {
                $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->reference_number]));
            }
        }

        return $this->redirect(['business-entity/view', 'id' => Yii::$app->hasher->encode($parent)]);
    }

    /**
     * Finds the BusinessPermit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BusinessPermit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BusinessPermit::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionReset_session()
    {
        $this->sess->remove('business_permit');
        return $this->redirect(['index']);
    }
}
