<?php

namespace app\controllers;

use app\components\Theming;
use Yii;

use app\models\TblAccountRequest;
use app\models\TblAccountRequestSearch;
use app\overrides\user\models\AuthAssignment;
use app\overrides\user\models\Profile;
use app\overrides\user\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

/**
 * AccountRequestController implements the CRUD actions for TblAccountRequest model.
 */
class AccountRequestController extends Controller
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
                    'confirm' => ['POST'],
                    'reject' => ['POST'],
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
                    [
                        'allow' => true,
                        'actions' => ['request', 'request-success', 'select'],
                        'roles' => ['?'],
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
        //monggo
        $this->sess = Yii::$app->session;
        if (isset(Yii::$app->session['year']['name']) || isset(Yii::$app->session['tahun'])) {
            $this->year = isset(Yii::$app->session['year']['name']) ? Yii::$app->session['year']['name'] : Yii::$app->session['tahun'];
        } else {
            $this->year = date('Y');
        }

        if (isset(Yii::$app->session['satker']['satker_id'])) {
            $this->unit_id = Yii::$app->session['satker']['satker_id'];
        }
        if (isset(Yii::$app->session['team']['team_id'])) {
            $this->team_id = Yii::$app->session['team']['team_id'];
        }
        /*         $this->user = Yii::$app->user->identity;
        $this->req = Yii::$app->request->post(); */
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        Theming::setThemes(Yii::$app->params['backEndTheme']);

        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
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

    public function actionSelect()
    {
        Theming::setThemes(Yii::$app->params['frontEndTheme']);
        return $this->render('select');
    }

    /**
     * Lists all TblAccountRequest models.
     * @return mixed
     */
    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = $this->sess['tbl_account_request'];
            if (isset($this->sess['tbl_account_request']['page']))
                $_GET['page'] = $this->sess['tbl_account_request']['page'];
        } else {
            $this->sess['tbl_account_request'] = $params;
        }

        $searchModel = new TblAccountRequestSearch();
        $dataProvider = $searchModel->search($params);

        Url::remember();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblAccountRequest model.
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
     * Creates a new TblAccountRequest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TblAccountRequest();

        if ($model->load(Yii::$app->request->post())) {
            try {
                if (!$model->password || !$model->password_confirm) {
                    throw new BadRequestHttpException('password tidak valid');
                }
                if (($model->password == '') && ($model->password_confirm == '')) {
                    throw new BadRequestHttpException('password tidak valid');
                }
                if ($model->password != $model->password_confirm) {
                    throw new BadRequestHttpException('password dan konfirmasinya harus sama');
                }
                if (!$model->save()) {
                    $model->validate();
                    throw new BadRequestHttpException('error saat menyimpan data');
                } else {
                    $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->name]));
                    return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
                }
            } catch (\Throwable $th) {
                // throw $th;
                $this->sess->setFlash('error', $th->getMessage());
            }
        }

        $this->populate_dropdown();

        return $this->render('create', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Creates a new TblAccountRequest model, but from user perspective.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionRequest()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/admin/index-core']);
        }
        Theming::setThemes(Yii::$app->params['frontEndTheme']);
        $model = new TblAccountRequest();

        if ($model->load(Yii::$app->request->post())) {
            try {
                if (!$model->password || !$model->password_confirm) {
                    throw new BadRequestHttpException('password tidak valid');
                }
                if (($model->password == '') && ($model->password_confirm == '')) {
                    throw new BadRequestHttpException('password tidak valid');
                }
                if ($model->password != $model->password_confirm) {
                    throw new BadRequestHttpException('password dan konfirmasinya harus sama');
                }
                if (!$model->save()) {
                    $model->validate();
                    throw new BadRequestHttpException('error saat menyimpan data');
                } else {
                    $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been created.', ['name' => $model->name]));
                    return $this->redirect(['request-success', 'id' => Yii::$app->hasher->encode($model->id)]);
                }
            } catch (\Throwable $th) {
                // throw $th;
                $this->sess->setFlash('error', $th->getMessage());
            }
        }

        $this->populate_dropdown();

        return $this->render('request', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Displays a single TblAccountRequest model after request.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionRequestSuccess($id)
    {
        Theming::setThemes(Yii::$app->params['frontEndTheme']);
        $id = Yii::$app->hasher->decode($id);
        $model = $this->findModel($id);
        if ($model->confirm_status != $model::CONFIRM_STATUS_PENDING) {
            throw new BadRequestHttpException(Yii::t('app', 'Status already affirmed'));
        }

        return $this->render('request-success', [
            'model' => $model,
            'url_prev'  => Url::previous()
        ]);
    }

    /**
     * Updates an existing TblAccountRequest to confirm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionConfirm($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);

        if ($model->confirm_status == $model::CONFIRM_STATUS_PENDING) {
            // $profile = new Profile;
            $assignment = new AuthAssignment;
            $transaction1 = $model->getDb()->beginTransaction();
            $transaction2 = $assignment->getDb()->beginTransaction();
            try {
                $model->confirm_status = $model::CONFIRM_STATUS_ACCEPTED;
                if (!$model->save(false)) {
                    throw new ServerErrorHttpException('Cannot save data');
                }
                $user = new User;
                $user->username = $model->username;
                $user->password_hash = $model->password_hash;
                $user->email = $model->email;
                $user->confirm();
                if (!$user->save(false)) {
                    throw new ServerErrorHttpException('Cannot save data');
                }
                $user->mailer->sendWelcomeMessage($user, null, true);
                // $profile->user_id = $user->id;
                $profile = Profile::findOne(['user_id' => $user->id]);
                $profile->name = $model->name;
                $profile->public_email = $model->email;
                if (!$profile->save(false)) {
                    throw new ServerErrorHttpException('Cannot save data');
                }
                $assignment->user_id = $user->id;
                $assignment->created_at = time();
                if ($model->requested_account_type == $model::REQUEST_ACCOUNT_PLANNER) {
                    $assignment->item_name = 'Planner';
                } else if ($model->requested_account_type == $model::REQUEST_ACCOUNT_SUPERVISOR) {
                    $assignment->item_name = 'Supervisor';
                }
                if (!$assignment->save(false)) {
                    throw new ServerErrorHttpException('Cannot save data');
                }
                $transaction1->commit();
                $transaction2->commit();
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been updated.', ['name' => $model->name]));
                return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
            } catch (\Throwable $th) {
                $transaction1->rollBack();
                $transaction2->rollBack();
                throw $th;
            }
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be updated.', ['name' => $model->name]));
        }

        return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
    }

    /**
     * Updates an existing TblAccountRequest to reject model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionReject($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);

        if ($model->confirm_status == $model::CONFIRM_STATUS_PENDING) {
            $model->confirm_status = $model::CONFIRM_STATUS_REJECTED;
            if (!$model->save(false)) {
                throw new ServerErrorHttpException('Cannot save data');
            } else {
                $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been updated.', ['name' => $model->name]));
            }
        } else {
            $this->sess->setFlash('error', Yii::t('app', 'Record  <strong>{name}</strong> can not be updated.', ['name' => $model->name]));
        }

        return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($model->id)]);
    }

    /**
     * Updates an existing TblAccountRequest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $id = Yii::$app->hasher->decode($id);

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            try {
                if ($model->password != $model->password_confirm) {
                    throw new BadRequestHttpException('password dan konfirmasinya harus sama');
                }
                if (!$model->save()) {
                    $model->validate();
                    throw new BadRequestHttpException('error saat menyimpan data : ');
                } else {
                    $this->sess->setFlash('success', Yii::t('app', 'Record  <strong>{name}</strong> has been updated.', ['name' => $model->name]));
                    return $this->redirect(['view', 'id' => Yii::$app->hasher->encode($id)]);
                }
            } catch (\Throwable $th) {
                // throw $th;
                $this->sess->setFlash('error', $th->getMessage());
            }
        }

        $this->populate_dropdown();

        return $this->render('update', [
            'model'         => $model,
            'dropdown_data' => $this->dropdown_data,
            'url_prev'      => Url::previous()
        ]);
    }

    /**
     * Deletes an existing TblAccountRequest model.
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
     * Finds the TblAccountRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblAccountRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblAccountRequest::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionReset_session()
    {
        $this->sess->remove('tbl_account_request');
        return $this->redirect(['index']);
    }
}
