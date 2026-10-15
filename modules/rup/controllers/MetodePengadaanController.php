<?php

namespace app\modules\rup\controllers;
use Yii;

use app\modules\rup\models\Rupmetodepengadaan;
use app\modules\rup\models\RupmetodepengadaanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/**
 * MetodePengadaanController implements the CRUD actions for Rupmetodepengadaan model.
 */
class MetodePengadaanController extends Controller
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

        
    }

    /**
     * Lists all Rupmetodepengadaan models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['rupmetodepengadaan'];
            if(isset($this->sess['rupmetodepengadaan']['page']))
            $_GET['page'] = $this->sess['rupmetodepengadaan']['page'];
        } else {
            $this->sess['rupmetodepengadaan'] = $params;
        }

        $searchModel = new RupmetodepengadaanSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember(); 

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Rupmetodepengadaan model.
     * @param integer $id
     * @param string $name
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $name)
    {
        $id = Yii::$app->hasher->decode($id);
        
        return $this->render('view', [
            'model' => $this->findModel($id, $name),
            'url_prev'  => Url::previous()
        ]);
    }

    /**
     * Creates a new Rupmetodepengadaan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Rupmetodepengadaan();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->name]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id), 'name' => $model->name]);
        }

        $this->populate_dropdown();

        return $this->render('create', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Updates an existing Rupmetodepengadaan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string $name
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $name)
    {
        $id = Yii::$app->hasher->decode($id);
        
        $model = $this->findModel($id, $name);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been updated.', ['name' => $model->name]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($id), 'name' => $model->name]);
        }

        $this->populate_dropdown();

        return $this->render('update', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Deletes an existing Rupmetodepengadaan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param string $name
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $name)
    {
        $id = Yii::$app->hasher->decode($id);
        
        $model = $this->findModel($id, $name);
           if($model->delete()){
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->name]));
        } else{
            $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->name]));
        }
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the Rupmetodepengadaan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $name
     * @return Rupmetodepengadaan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $name)
    {
        if (($model = Rupmetodepengadaan::findOne(['id' => $id, 'name' => $name])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionReset_session(){
        $this->sess->remove('rupmetodepengadaan');
        return $this->redirect(['index']);
    }
}
