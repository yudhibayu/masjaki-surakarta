<?php

namespace app\controllers;

use app\components\Theming;
use Yii;

use app\models\Satker;
use app\models\SatkerSearch;
use app\models\UserSatker;
use app\modules\manage_sot\models\RefSot;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\overrides\user\models\User;
use app\overrides\user\models\AuthAssignment;
use yii\data\ArrayDataProvider;

/**
 * SatkerController implements the CRUD actions for Satker model.
 */
class SatkerController extends Controller
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
                        'roles' => ['Administrator'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['set-satker'],
                        'roles' => ['OPD'],
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
        // if (isset($this->sess['tahun'])) {
        //     $this->year = $this->sess['tahun'];
        // } else if (isset($this->sess['year'])) {
        //     if (is_array($this->sess['year']) && isset($this->sess['year']['name'])) {
        //         $this->year = $this->sess['year'];
        //     } else {
        //         $this->year = $this->sess['year'];
        //     }
        // } else if (isset($this->sess['fiscal_year'])) {
        //     $this->year = $this->sess['fiscal_year'];
        // } else {
        //     $this->year = (int)date('Y');
        // }
    }

    /**
     * Dropdowns Populate
     *
     * @return void
     */
    public function populate_dropdown()
    {
        $this->dropdown_data = array();

        $ref_sot_id = RefSot::find()->select('id,name')->orderBy('name')->all();
        $this->dropdown_data['ref_sot_id'] = ArrayHelper::map($ref_sot_id, 'id', 'name');
        //monggo


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
     * Lists all Satker models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['satker'];
            if (isset($this->sess['satker']['page']))
                $_GET['page'] = $this->sess['satker']['page'];
        } else {
            $this->sess['satker'] = $params;
        }

        $searchModel = new SatkerSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Satker model.
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
            $usersarr[] = $arruser;
        }

        $provider = new ArrayDataProvider([
            'allModels' => $usersarr,
            'pagination' => [
                'pageSize' => 5,
            ],
            'sort' => [
                'attributes' => ['id', 'name']
            ]
        ]);
        return $this->render('view', [
            'model' => $model,
            'url_prev'  => Url::previous(),
            'usersDataProvider' => $provider,
        ]);
    }

    /**
     * Creates a new Satker model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Satker();
      
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

    public function actionAssignSatker()
    {
        $usersatker = UserSatker::find()->where(['user_id' => Yii::$app->user->id])->one();
        if ($usersatker) {
            return $this->redirect(['/']);
        }
        $model = new UserSatker();
        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = Yii::$app->user->id;
            // $model->satker_id = Yii::$app->request->post('satker_id');
            if ($model->save()) {
                if (isset(Yii::$app->session)) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Satker Assignned'));
                }
                return $this->redirect(['/']);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Satker not Assignned'));
            }
        }

        $assignedSatkers = UserSatker::find()->select(['satker_id'])->column();
        $satkers = Satker::find()->where(['not in', 'id', $assignedSatkers])->all();
        $this->dropdown_data['satker'] = ArrayHelper::map($satkers, 'id', 'namecode');

        return $this->render('assignsatker', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Updates an existing Satker model.
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
     * Deletes an existing Satker model.
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

    public function actionSync()
    {
        $tahun = 0;
        $kode = '';
        if ($tahun == 0) {
            $tahun = (int)date('Y');
        }
        if ($kode == '') {
            // $kode = 'D147';
            $kode = 'D240';
        }
        $url = '';
        if ($url == '') {
            $url = 'https://isb.lkpp.go.id/isb/api/aa1b57eb-d727-48e4-b413-cc4f8c30cabb/json/736987665/MasterSatkerRUP/tipe/12:12/parameter/';
        }
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $kode . ':' . $tahun,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // return $response;
        $data = json_decode($response);
        $datachunks = array_chunk($data, 10000);
        $saved = [];
        $erros = [];

        foreach ($datachunks[0] as $row) {
            $model = Satker::findOne(['code' => $row->kd_satker_str, 'fiscal_year' => $tahun]);
            if (!$model) {
                $model = new Satker();
            }
            // foreach ($row as $key => $val) {
            //     $model->$key = $val;
            // }

            // untuk penyedia
            // $model->tipe_rup = 0;

            $model->name = (string)$row->nama_satker;
            $model->code = (string)$row->kd_satker_str;
            $model->fiscal_year = (string)$tahun;
            // $model->spesifikasi = $this->trim_me($model->spesifikasi,250);
            // $model->namapaket = $this->trim_me($model->namapaket,250);
            // $model->keterangan = $this->trim_me($model->keterangan,250);

            if ($model->save(false)) {
                $saved[] = 'Saved ' . $model->name;
            } else {
                $model->validate();
                $notsave = ['name' => $model->name, 'errors' => json_encode($model->errors)];
                $notsaved[] = $notsave;
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Satker model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Satker the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Satker::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionReset_session()
    {
        $this->sess->remove('satker');
        return $this->redirect(['index']);
    }


    /**
     * Creates a new UserSatker model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionSetSatker()
    {
        $user_satker = Satker::find()->select(['id', 'name'])->where(['ref_sot_id' => $this->sess['milestone']['ref_sot_id']])->orderBy('name')->all();
        $user_satker = ArrayHelper::map($user_satker, 'id', 'name');

        $model = new UserSatker;
        if ($this->sess['satker']) {
            $model->satker_id = $this->sess['satker']['satker_id'];
        }
        if ($model->load(Yii::$app->request->post())) {

            $this->sess->remove('satker');

            $user_check = Yii::$app->user->identity->id;
            $model->user_id = $user_check;
            $model->fiscal_year = $this->sess['year']['name'];
            $model->save();

            $user_satker = UserSatker::find()->where(['user_id' => $user_check, 'fiscal_year' => $this->sess['year']['name']])->one();
            $this->setSatker($user_satker);
            return $this->redirect('/admin/index-core');
        }

        return $this->render('satker_choose', [
            'model' => $model,
            'dropdown_data' => $user_satker
        ]);
    }

    /**
     * Set Pilihan OPD to session
     *
     * @return User|null
     */
    public function setSatker($user_satker)
    {
        $session   = Yii::$app->session;
        $session['satker'] = [
            'satker_id'     => $user_satker->satker_id,
            'satker_name'   => Satker::findOne($user_satker->satker_id)->name,
            'user_id'       => $user_satker->user_id,
        ];
    }


    /**
     * Creates a new UserSatker model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAddUserSatker($satkerid = null)
    {

        $model = new UserSatker();

        if ($model->load(Yii::$app->request->post())) {
            $model->fiscal_year = $this->sess['year']['name'];
            $model->save();

            $team = Satker::findOne(['id' => $model->satker_id]);
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $team->name]));
            return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->satker_id)]);
        }

        // dropdown data
        $assigned = UserSatker::find()->select('satker_id')->where(['fiscal_year' => $this->sess['year']['name']])->column();
        $assigned_user = UserSatker::find()->select('user_id')->where(['fiscal_year' => $this->sess['year']['name']])->column();



        $toassignments = AuthAssignment::find()->select('user_id')->where(['item_name' => 'OPD'])->andWhere(['not in', 'user_id', $assigned_user])->column();
        $users = [];
        foreach ($toassignments as $user_id) {
            $user = User::findOne(['id' => $user_id]);
            $users[$user_id] = isset($user->profile->name) ? $user->profile->name : $user->username;
        }

        if (($satkerid != null) && ($satkerid != '')) {
            $idsatker = Yii::$app->hasher->decode($satkerid);
            $satkers = Satker::find()->where(['id' => $idsatker, 'fiscal_year' => $this->sess['year']['name']])->andWhere(['not in', 'id', $assigned])->orderBy('name')->all();
            if ($satkers) {
                $model->satker_id = $satkers[0]->id;
            }
        } else {
            $satkers = Satker::find()->where(['fiscal_year' => $this->sess['year']['name']])->andWhere(['not in', 'id', $assigned])->orderBy('name')->all();
        }

        $satkers = ArrayHelper::map($satkers, 'id', 'name');

        return $this->render('add_user_satker', [
            'users'     => $users,
            'satkers'     => $satkers,
            'model'     => $model,
            'url_prev'  => Url::previous()
        ]);
    }

    /**
     * Delete from User Satker.
     * If deletion is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionRemoveUserSatker($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = UserSatker::findOne(['user_id' => $id, $this->sess['year']['name']]);
        $satker = Satker::findOne(['id' => $model->satker_id]);

        if ($model && $model->deleteAll(['user_id' => $id])) {
            $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> successfully deleted.', ['name' => $model->user_id]));
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be deleted.', ['name' => $model->user_id]));
        }

        return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($satker->id)]);
    }
}
