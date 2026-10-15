<?php

namespace app\modules\businessEntity\controllers;

use Yii;

use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\modules\businessEntity\models\Owner;
use app\modules\businessEntity\models\OwnerSearch;
use app\modules\referencesMasjaki\models\RefNegara;
use app\modules\referencesMasjaki\models\RefKabkota;
use app\modules\referencesMasjaki\models\RefProvinsi;
use app\modules\referencesMasjaki\models\RefKecamatan;
use app\modules\referencesMasjaki\models\RefKelurahan;
use app\modules\referencesMasjaki\models\OwnershipType;

/**
 * OwnerController implements the CRUD actions for Owner model.
 */
class OwnerController extends Controller
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


        $ownership_type = OwnershipType::find()->orderBy('name')->all();
        $this->dropdown_data['ownership_type'] = ArrayHelper::map($ownership_type, 'id', 'name');
        $ref_negara = RefNegara::find()->orderBy('name')->all();
        $this->dropdown_data['ref_negara'] = ArrayHelper::map($ref_negara, 'id', 'name');
        //monggo


    }

    /**
     * Lists all Owner models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['owner'];
            if (isset($this->sess['owner']['page']))
                $_GET['page'] = $this->sess['owner']['page'];
        } else {
            $this->sess['owner'] = $params;
        }

        $searchModel = new OwnerSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Owner model.
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
     * Creates a new Owner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model_owner = new Owner();
        $model_owner->id_business_entity = $id;

        if ($model_owner->load(Yii::$app->request->post()) && $model_owner->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been added.', ['name' => $model_owner->name]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model_owner->id)]);
        }

        $this->populate_dropdown();

        return $this->render('create', [
            'model_owner'         => $model_owner,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }


    /**
     * Updates an existing Owner model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model_owner = $this->findModel($id);

        if ($model_owner->load(Yii::$app->request->post()) && $model_owner->save()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been updated.', ['name' => $model_owner->name]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($id)]);
        }

        $this->populate_dropdown();

        return $this->render('update', [
            'model_owner'         => $model_owner,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Deletes an existing Owner model.
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
     * Finds the Owner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Owner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Owner::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionReset_session()
    {
        $this->sess->remove('owner');
        return $this->redirect(['index']);
    }
}
