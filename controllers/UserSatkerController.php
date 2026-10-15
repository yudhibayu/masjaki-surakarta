<?php

namespace app\controllers;

use app\components\Theming;
use app\models\Satker;
use Yii;

use app\models\UserSatker;
use app\models\UserSatkerSearch;
use app\modules\references\models\Year_model;
use app\overrides\user\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/**
 * UserSatkerController implements the CRUD actions for UserSatker model.
 */
class UserSatkerController extends Controller
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
                        'roles' => ['Team'],
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
     * {@inheritdoc}
     */
    public function actions()
    {
        Theming::setThemes(Yii::$app->params['backEndTheme']);
        return parent::actions();
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

        $user_id = User::find()->select('id,username')->orderBy('username')->where(['>', 'id', 2])->all();
        $this->dropdown_data['user_id'] = ArrayHelper::map($user_id, 'id', 'username');
        $satker_id = Satker::find()->select('id,name')->where(['ref_sot_id' => $this->sess['milestone']['ref_sot_id']])->orderBy('name')->all();
        $this->dropdown_data['satker_id'] = ArrayHelper::map($satker_id, 'id', 'name');
        $fiscal_year = Year_model::find()->select('name')->orderBy('name')->all();
        $this->dropdown_data['fiscal_year'] = ArrayHelper::map($fiscal_year, 'name', 'name');
    //monggo

        
    }

    /**
     * Lists all UserSatker models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['user_satker'];
            if(isset($this->sess['user_satker']['page']))
            $_GET['page'] = $this->sess['user_satker']['page'];
        } else {
            $this->sess['user_satker'] = $params;
        }

        $searchModel = new UserSatkerSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember(); 

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserSatker model.
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
     * Creates a new UserSatker model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserSatker();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->user_name]));
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
     * Updates an existing UserSatker model.
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
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been updated.', ['name' => $model->user_name]));
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
     * Deletes an existing UserSatker model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = Yii::$app->hasher->decode($id);
        
        $model = $this->findModel($id);
           if($model->delete()){
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->user_name]));
        } else{
            $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->user_name]));
        }
        
        return $this->redirect(['index']);
    }

    /**
     * Get data OPD by year
     * @param integer $id
     * @return array
     * 
     */
    public function actionUnit()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];

        if (Yii::$app->request->post('depdrop_parents')) {
            $id0 = Yii::$app->request->post('depdrop_parents');
            $id = end($id0);
            $getsot = '';
            if($id){
                $getsot = Year_model::find()->where(['name' => $id])->one()->ref_sot_id;
            }
            $list = Satker::find()->select('id,name')->where('ref_sot_id=:ref_sot_id', [':ref_sot_id' => $getsot])->orderBy('name')->asArray()->all();
            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $i => $data) {
                    $out[] = ['id' => $data['id'], 'name' => $data['name']];
                }
                if ($i == 0) {
                    $selected = $data['id'];
                }
                return ['output' => $out, 'selected' => $selected];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    /**
     * Finds the UserSatker model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserSatker the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserSatker::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionReset_session(){
        $this->sess->remove('user_satker');
        return $this->redirect(['index']);
    }
}
