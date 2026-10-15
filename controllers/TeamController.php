<?php

namespace app\controllers;

use app\components\Theming;
use Yii;

use app\models\Teamlist;
use app\models\TeamlistSearch;
use app\models\Teamuser;
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
 * TeamController implements the CRUD actions for Teamlist model.
 */
class TeamController extends Controller
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
     * Lists all Teamlist models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['teamlist'];
            if (isset($this->sess['teamlist']['page']))
                $_GET['page'] = $this->sess['teamlist']['page'];
        } else {
            $this->sess['teamlist'] = $params;
        }

        $searchModel = new TeamlistSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Teamlist model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);

        $users = $model->getUsers();
        $usersarr = [];
        foreach ($users as $user) {
            $arruser = [];
            $arruser['id'] = $user->id;
            $arruser['name'] = isset($user->profile->name) ? $user->profile->name : $user->username;
            $arruser['fiscal_year'] = $this->year;
            $usersarr[] = $arruser;
        }

        $provider = new ArrayDataProvider([
            'allModels' => $usersarr,
            'pagination' => [
                'pageSize' => 5,
            ],
            'sort' => [
                'attributes' => ['id', 'name', 'fiscal_year']
            ]
        ]);

        return $this->render('view', [
            'model' => $model,
            'url_prev'  => Url::previous(),
            'usersDataProvider' => $provider,
        ]);
    }

    /**
     * Creates a new Teamlist model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Teamlist();

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

    public function actionAddMember($teamid = null)
    {
        $model = new Teamuser();
        $model->fiscal_year = $this->year;

        if ($model->load(Yii::$app->request->post())) {
            $is_assigned = Teamuser::find()->where(['user_id' => $model->user_id, 'team_id' => $model->team_id, 'fiscal_year' => $model->fiscal_year])->one();
            if (!$is_assigned && $model->save()) {
                $team = Teamlist::findOne(['id' => $model->team_id]);
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $team->name]));
                return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->team_id)]);
            }
        }

        // dropdown data
        $assigned = Teamuser::find()->select('user_id')->where(['fiscal_year' => $this->year])->column();
        $toassignments = AuthAssignment::find()->select('user_id')->where(['item_name' => 'Team'])->andWhere(['not in', 'user_id', $assigned])->column();
        $users = [];
        foreach ($toassignments as $user_id) {
            $user = User::findOne(['id' => $user_id]);
            $users[$user_id] = isset($user->profile->name) ? $user->profile->name : $user->username;
        }

        if (($teamid != null) && ($teamid != '')) {
            $teams = Teamlist::find()->where(['id' => $teamid])->all();
            $model->team_id = $teamid;
        } else {
            $teams = Teamlist::find()->orderBy('name')->all();
        }
        $teams = ArrayHelper::map($teams, 'id', 'name');

        return $this->render('add_member', [
            'users'     => $users,
            'teams'     => $teams,
            'model'     => $model,
            'url_prev'  => Url::previous()
        ]);
    }

    public function actionRemoveMember($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = Teamuser::findOne(['user_id' => $id, 'fiscal_year' => $this->year]);
        if (isset($model->team_id)) {
            $team = Teamlist::findOne(['id' => $model->team_id]);
        } else {
            return $this->redirect(['index']);
        }

        if ($model && $model->deleteAll(['user_id' => $id, 'fiscal_year' => $this->year])) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->user_id]));
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->user_id]));
        }

        return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($team->id)]);
    }

    /**
     * Updates an existing Teamlist model.
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
     * Deletes an existing Teamlist model.
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
     * Finds the Teamlist model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Teamlist the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Teamlist::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionReset_session()
    {
        $this->sess->remove('teamlist');
        return $this->redirect(['index']);
    }

    /**
     * Creates a new Team model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionSetTeam()
    {
        $team_list = Teamlist::find()->select(['id', 'name'])->orderBy('id')->all();
        $team_list = ArrayHelper::map($team_list, 'id', 'name');

        $model = new Teamuser();
        $model->fiscal_year = $this->year;
        if ($this->sess['team']) {
            $model->team_id = $this->sess['team']['team_id'];
        }
        if ($model->load(Yii::$app->request->post())) {

            $this->sess->remove('team');

            $user_check = Yii::$app->user->identity->id;
            $model->user_id = $user_check;
            $is_assigned = Teamuser::find()->where(['user_id' => $model->user_id, 'team_id' => $model->team_id, 'fiscal_year' => $model->fiscal_year])->one();
            if (!$is_assigned && $model->save()) {
                $team_user = Teamuser::find()->where(['user_id' => $user_check, 'fiscal_year' => $this->year])->one();
                $this->setTeam($team_user);
                return $this->redirect('/admin/index-core');
            }
        }

        return $this->render('team_choose', [
            'model' => $model,
            'dropdown_data' => $team_list
        ]);
    }

    /**
     * Set Pilihan Team to session
     *
     * @return User|null
     */
    public function setTeam($team_user)
    {
        $session   = Yii::$app->session;
        $session['team'] = [
            'team_id'     => $team_user->team_id,
            'team_name'   => Teamlist::findOne($team_user->team_id)->name,
            'user_id'     => $team_user->user_id,
        ];
    }
}
