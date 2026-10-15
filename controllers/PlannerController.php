<?php

namespace app\controllers;

use app\components\Theming;
use Yii;

use app\models\Plannerlist;
use app\models\PlannerlistSearch;
use app\models\Planneruser;
use app\overrides\user\models\Assignment;
use app\overrides\user\models\AuthAssignment;
use app\overrides\user\models\User;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/**
 * PlannerController implements the CRUD actions for Plannerlist model.
 */
class PlannerController extends Controller
{
    private $dropdown_data;
    private $sess;
    private $year;

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
                        'roles' => ['Administrator', 'Team'],
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
        if (isset(Yii::$app->session['year']['name']) || isset(Yii::$app->session['tahun'])) {
            $this->year = isset(Yii::$app->session['year']['name']) ? Yii::$app->session['year']['name'] : Yii::$app->session['tahun'];
        } else {
            $this->year = date('Y');
        }
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
     * Lists all Plannerlist models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['plannerlist'];
            if (isset($this->sess['plannerlist']['page']))
                $_GET['page'] = $this->sess['plannerlist']['page'];
        } else {
            $this->sess['plannerlist'] = $params;
        }

        $searchModel = new PlannerlistSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Plannerlist model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);

        // $users = $model->getUsers();
        // $usersarr = [];
        // foreach ($users as $user) {
        //     $arruser = [];
        //     $arruser['id'] = $user->id;
        //     $arruser['name'] = isset($user->profile->name) ? $user->profile->name : $user->username;
        //     $arruser['fiscal_year'] = $this->year;
        //     $usersarr[] = $arruser;
        // }

        // $provider = new ArrayDataProvider([
        //     'allModels' => $usersarr,
        //     'pagination' => [
        //         'pageSize' => 5,
        //     ],
        //     'sort' => [
        //         'attributes' => ['id', 'name', 'fiscal_year']
        //     ]
        // ]);

        return $this->render('view', [
            'model' => $model,
            'url_prev'  => Url::previous(),
            // 'usersDataProvider' => $provider,
        ]);
    }

    /**
     * Creates a new Plannerlist model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Plannerlist();

        $assigned = Plannerlist::find()->select('user_id')->where(['>', 'user_id', 2])->column();
        if ($assigned) {
            $toassignments = AuthAssignment::find()->select('user_id')->where(['item_name' => 'Planner'])->andWhere(['not in', 'user_id', $assigned])->column();
        } else {
            $toassignments = AuthAssignment::find()->select('user_id')->where(['item_name' => 'Planner'])->column();
        }

        $users = [];
        foreach ($toassignments as $user_id) {
            $user = User::findOne(['id' => $user_id]);
            $users[$user_id] = isset($user->profile->name) ? $user->profile->name : $user->username;
        }


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->name]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
        }

        $this->populate_dropdown();
        $this->dropdown_data['users'] = $users;

        return $this->render('create', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Updates an existing Plannerlist model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);

        $toassignments = AuthAssignment::find()->select('user_id')->where(['item_name' => 'Planner'])->andWhere(['user_id' => $model->user_id])->column();

        $users = [];
        foreach ($toassignments as $user_id) {
            $user = User::findOne(['id' => $user_id]);
            $users[$user_id] = isset($user->profile->name) ? $user->profile->name : $user->username;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been updated.', ['name' => $model->name]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($id)]);
        }

        $this->populate_dropdown();
        $this->dropdown_data['users'] = $users;

        return $this->render('update', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Deletes an existing Plannerlist model.
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
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->name]));
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->name]));
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Plannerlist model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Plannerlist the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Plannerlist::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionReset_session()
    {
        $this->sess->remove('plannerlist');
        return $this->redirect(['index']);
    }
}
