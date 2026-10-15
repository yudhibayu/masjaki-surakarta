<?php

namespace app\modules\construction\controllers;

use Yii;
use yii\helpers\Url;

use yii\web\Controller;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use app\modules\rup\models\Pbj;
use yii\web\NotFoundHttpException;
use app\overrides\user\models\User;
use app\overrides\user\models\User as ModelsUser;
use app\modules\businessEntity\models\BusinessEntity;
use app\modules\construction\models\PbjBusinessEntity;
use app\modules\construction\models\PbjBusinessEntitySearch;

/**
 * PbjBusinessEntityController implements the CRUD actions for PbjBusinessEntity model.
 */
class PbjBusinessEntityController extends Controller
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
                        'roles' => ['Administrator'],
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


        $id_business = BusinessEntity::find()->where(['account_verification' => BusinessEntity::VERIFICATION])->orderBy('company_name')->all();
        $this->dropdown_data['id_business_entity'] = ArrayHelper::map($id_business, 'id', 'company_name');

        //monggo


    }

    /**
     * Lists all PbjBusinessEntity models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['pbj_business_entity'];
            if (isset($this->sess['pbj_business_entity']['page']))
                $_GET['page'] = $this->sess['pbj_business_entity']['page'];
        } else {
            $this->sess['pbj_business_entity'] = $params;
        }

        $searchModel = new PbjBusinessEntitySearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PbjBusinessEntity model.
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
     * Creates a new PbjBusinessEntity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $existingRecord = PbjBusinessEntity::findOne(['kode_pbj' => $id]);
        $model = new PbjBusinessEntity();
        $pbj = Pbj::findOne(['kodelelang' => $id]);
        $model->kode_pbj = $id;
        $model->kode_rup = $pbj->rup_code;
        $model->nama_paket = $pbj->name;
        if ($model->load(Yii::$app->request->post())) {
            $businessEntity = BusinessEntity::findOne(['id' => $model->id_business_entity]);
            $user = User::findOne(['id_business_entity' => $model->id_business_entity]);
            $model->name_business_entity = $businessEntity->company_name;
            $model->user_id = $user->id;

            if ($model->save()) {
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->name_business_entity]));
                return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
            }
        }

        $this->populate_dropdown();
        if ($existingRecord) {
            $this->sess->setFlash('error', Yii::t('app', 'Badan usaha sudah dipilih'));
            return $this->redirect(['/rup/pbj/view', 'id' => Yii::$app->hasher->encode($model->kode_pbj)]);
        } else {
            return $this->render('create', [
                'model'         => $model,
                'dropdown_data' => $this->dropdown_data,
                'url_prev'      => Url::previous()
            ]);
        }
    }

    /**
     * Updates an existing PbjBusinessEntity model.
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
     * Deletes an existing PbjBusinessEntity model.
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
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->name_business_entity]));
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->name_business_entity]));
        }

        return $this->redirect(['/rup/pbj/view', 'id' => Yii::$app->hasher->encode($model->kode_pbj)]);
    }

    /**
     * Finds the PbjBusinessEntity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PbjBusinessEntity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PbjBusinessEntity::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionReset_session()
    {
        $this->sess->remove('pbj_business_entity');
        return $this->redirect(['index']);
    }
    // Peningkatan Kualitas Jalan Lingkungan Paket IV	
    // public function actionDepdropByPbj()
    // {
    //     Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    //     $out = [];

    //     if (Yii::$app->request->post('depdrop_parents')) {
    //         $id0 = Yii::$app->request->post('depdrop_parents');
    //         $id = end($id0);
    //         $pbj = Pbj::findOne(['kodelelang' => $id]);
    //         if ($pbj) {
    //             $list = BusinessEntity::find()->where()->orderBy('company_name')->all();
    //             $list = ArrayHelper::map($list, 'id', 'company_name');
    //             $selected  = null;
    //             if ($id != null && count($list) > 0) {
    //                 $selected = '';
    //                 foreach ($list as $i => $data) {
    //                     $out[] = ['id' => $i, 'company_name' => $data];
    //                 }
    //                 if ($i == 0) {
    //                     $selected = $i;
    //                 }
    //                 return ['output' => $out, 'selected' => $selected];
    //             }
    //         }
    //     }
    //     return ['output' => '', 'selected' => ''];
    // }
    // $list = ambilkan data BusinessEntity yang idnya ada di tabel PbjBusinessEntity id_business_entity yang kode_pbjnya $pbj
    // public function actionDepdropByPbj()
    // {
    //     Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    //     $out = [];

    //     if (Yii::$app->request->post('depdrop_parents')) {
    //         $id0 = Yii::$app->request->post('depdrop_parents');
    //         $id = end($id0); // Get the last element which is the `kodelelang`

    //         // Find the Pbj model
    //         $pbj = Pbj::findOne(['kodelelang' => $id]);
    //         if ($pbj) {
    //             // Retrieve BusinessEntity IDs related to the Pbj
    //             $businessEntityIds = PbjBusinessEntity::find()
    //                 ->select('id_business_entity')
    //                 ->where(['kode_pbj' => $id])
    //                 ->column();

    //             if (!empty($businessEntityIds)) {
    //                 // Get BusinessEntity records filtered by the retrieved IDs
    //                 $list = BusinessEntity::find()
    //                     ->where(['id' => $businessEntityIds])
    //                     ->orderBy('company_name')
    //                     ->all();

    //                 // Map the BusinessEntity records to the desired format
    //                 $list = ArrayHelper::map($list, 'id', 'company_name');

    //                 $selected  = null;
    //                 if ($id != null && count($list) > 0) {
    //                     $selected = '';
    //                     foreach ($list as $i => $data) {
    //                         $out[] = ['id' => $i, 'company_name' => $data];
    //                     }
    //                     if ($i == 0) {
    //                         $selected = $i;
    //                     }
    //                     return ['output' => $out, 'selected' => $selected];
    //                 }
    //                 // foreach ($list as $i => $data) {
    //                 //     $out[] = ['id' => $i, 'company_name' => $data];
    //                 // }

    //                 // // Select the first item by default
    //                 // $selected = !empty($list) ? array_key_first($list) : null;

    //                 // return ['output' => $out, 'selected' => $selected];
    //             }
    //         }
    //     }
    //     return ['output' => '', 'selected' => ''];
    // }

    // public function actionDepdropByPbj()
    // {
    //     Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    //     $out = [];

    //     if (Yii::$app->request->post('depdrop_parents')) {
    //         $id0 = Yii::$app->request->post('depdrop_parents');
    //         $id = end($id0);
    //         $pbj = Pbj::findOne(['kodelelang' => $id]);
    //         if ($pbj) {
    //             $list = PbjBusinessEntity::find()->where('kode_pbj=:id', [':id' => $pbj->kodelelang])->orderBy('name_business_entity')->all();
    //             $list = ArrayHelper::map($list, 'kode_pbj', 'name_business_entity');
    //             $selected  = null;
    //             if ($id != null && count($list) > 0) {
    //                 $selected = '';
    //                 foreach ($list as $i => $data) {
    //                     $out[] = ['kode_pbj' => $i, 'name_business_entity' => $data];
    //                 }
    //                 if ($i == 0) {
    //                     $selected = $i;
    //                 }
    //                 return ['output' => $out, 'selected' => $selected];
    //             }
    //         }
    //     }
    //     return ['output' => '', 'selected' => ''];
    // }
    public function actionDepdropByPbj()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        $selected = '';
        if (Yii::$app->request->post('depdrop_parents')) {
            $id0 = Yii::$app->request->post('depdrop_parents');
            $id = end($id0);
            $pbj = Pbj::findOne(['kodelelang' => $id]);
            if ($pbj) {
                $list = PbjBusinessEntity::find()
                    ->where(['kode_pbj' => $pbj->kodelelang])
                    ->orderBy('name_business_entity')
                    ->all();
                foreach ($list as $item) {
                    $out[] = ['id' => $item->id_business_entity, 'name' => $item->name_business_entity];
                }
                if (!empty($out)) {
                    $selected = $out[0]['id'];
                }
            }
        }
        return ['output' => $out, 'selected' => $selected];
    }
}