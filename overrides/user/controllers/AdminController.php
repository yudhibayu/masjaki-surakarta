<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\overrides\user\controllers;

use app\components\Theming;
use app\overrides\user\models\User;
use app\overrides\user\models\UserSearch;
use infinindotech\user\controllers\AdminController as Adcon;
use infinindotech\user\filters\AccessRule;
use infinindotech\user\Finder;
use infinindotech\user\helpers\Password;
use infinindotech\user\Module;
use infinindotech\user\traits\EventTrait;
use infinindotech\user\models\Profile;
use yii;
use yii\base\ExitException;
use yii\base\Model;
use yii\base\Module as Module2;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\modules\manage_unit\models\RefUnit;
use app\modules\manage_unit\models\UserUnit;
use app\components\Uploader;
use app\modules\businessEntity\models\BusinessEntity;
use app\modules\construction\models\Labor;

/**
 * AdminController allows you to administrate users.
 *
 * @property Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com
 */
class AdminController extends Adcon
{

    private $dropdown_data;
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete'          => ['post'],
                    'confirm'         => ['post'],
                    'resend-password' => ['post'],
                    'block'           => ['post'],
                    'switch'          => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['switch'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['Administrator'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Dropdown Populate
     *
     * @return void
     */
    public function populate_dropdown()
    {
        $this->dropdown_data = array();
    }

    /**
     * Lists all User models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        Url::remember('', 'actions-redirect');
        $searchModel  = \Yii::createObject(UserSearch::className());
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        Theming::setThemes(Yii::$app->params['backEndTheme']);
        return $this->render('@app/overrides/user/views/admin/index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        /** @var User $user */
        $user = \Yii::createObject([
            'class'    => User::className(),
            'scenario' => 'create',
        ]);
        $event = $this->getUserEvent($user);

        $this->performAjaxValidation($user);

        $this->trigger(self::EVENT_BEFORE_CREATE, $event);
        if ($user->load(\Yii::$app->request->post()) && $user->create()) {
            \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'User has been created'));
            $this->trigger(self::EVENT_AFTER_CREATE, $event);
            return $this->redirect(['update', 'id' => $user->id]);
        }
        Theming::setThemes(Yii::$app->params['backEndTheme']);
        return $this->render('@app/overrides/user/views/admin/create', [
            'user' => $user,
        ]);
    }

    /**
     * Updates an existing User model.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        Url::remember('', 'actions-redirect');
        $user = $this->findModel($id);
        $user->scenario = 'update';
        $getbusiness = BusinessEntity::find()->where(['username' => $user->username])->one();
        $getlabor = Labor::find()->where(['username' => $user->username])->one();
        $event = $this->getUserEvent($user);

        $this->performAjaxValidation($user);

        $this->trigger(self::EVENT_BEFORE_UPDATE, $event);
        if ($user->load(\Yii::$app->request->post()) && $user->save()) {
            if ($getbusiness) {
                $getbusiness->username = $user->username;
                $getbusiness->password_hash = $user->password_hash;
                $getbusiness->save(false);
            }
            if ($getlabor) {
                $getlabor->username = $user->username;
                $getlabor->password_hash = $user->password_hash;
                $getlabor->save(false);
            }
            \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'Account details have been updated'));
            $this->trigger(self::EVENT_AFTER_UPDATE, $event);
            return $this->refresh();
        }
        Theming::setThemes(Yii::$app->params['backEndTheme']);
        return $this->render('@app/overrides/user/views/admin/_account', [
            'user' => $user,
        ]);
    }

    /**
     * Updates an existing profile.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionUpdateProfile($id)
    {
        $this->populate_dropdown();
        Url::remember('', 'actions-redirect');
        $user    = $this->findModel($id);
        $profile = $user->profile;

        if ($profile == null) {
            $profile = \Yii::createObject(Profile::className());
            $profile->link('user', $user);
        }
        //$user_unit = 
        $event = $this->getProfileEvent($profile);

        $this->performAjaxValidation($profile);

        $this->trigger(self::EVENT_BEFORE_PROFILE_UPDATE, $event);

        if ($profile->load(\Yii::$app->request->post()) && $profile->save()) {
            \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'Profile details have been updated'));
            $this->trigger(self::EVENT_AFTER_PROFILE_UPDATE, $event);
            return $this->refresh();
        }
        Theming::setThemes(Yii::$app->params['backEndTheme']);
        return $this->render('@app/overrides/user/views/admin/_profile', [
            'user'          => $user,
            'profile'       => $profile,
            'dropdown_data' => $this->dropdown_data,
        ]);
    }

    /**
     * Shows information about user.
     *
     * @param int $id
     *
     * @return string
     */
    public function actionInfo($id)
    {
        Url::remember('', 'actions-redirect');
        $user = $this->findModel($id);
        Theming::setThemes(Yii::$app->params['backEndTheme']);
        return $this->render('@app/overrides/user/views/admin/_info', [
            'user' => $user,
        ]);
    }

    /**
     * Switches to the given user for the rest of the Session.
     * When no id is given, we switch back to the original admin user
     * that started the impersonation.
     *
     * @param int $id
     *
     * @return string
     */
    public function actionSwitch($id = null)
    {
        if (!$this->module->enableImpersonateUser) {
            throw new ForbiddenHttpException(Yii::t('user', 'Impersonate user is disabled in the application configuration'));
        }

        if (!$id && Yii::$app->session->has(self::ORIGINAL_USER_SESSION_KEY)) {
            $user = $this->findModel(Yii::$app->session->get(self::ORIGINAL_USER_SESSION_KEY));

            Yii::$app->session->remove(self::ORIGINAL_USER_SESSION_KEY);
        } else {
            if (!Yii::$app->user->identity->isAdmin) {
                throw new ForbiddenHttpException;
            }

            $user = $this->findModel($id);
            Yii::$app->session->set(self::ORIGINAL_USER_SESSION_KEY, Yii::$app->user->id);
        }

        $event = $this->getUserEvent($user);

        $this->trigger(self::EVENT_BEFORE_IMPERSONATE, $event);

        Yii::$app->user->switchIdentity($user, 3600);

        $this->trigger(self::EVENT_AFTER_IMPERSONATE, $event);

        return $this->goHome();
    }

    /**
     * If "dektrium/yii2-rbac" extension is installed, this page displays form
     * where user can assign multiple auth items to user.
     *
     * @param int $id
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionAssignments($id)
    {
        if (!isset(\Yii::$app->extensions['dektrium/yii2-rbac'])) {
            throw new NotFoundHttpException();
        }
        Url::remember('', 'actions-redirect');
        $user = $this->findModel($id);
        Theming::setThemes(Yii::$app->params['backEndTheme']);
        return $this->render('@app/overrides/user/views/admin/_assignments', [
            'user' => $user,
        ]);
    }

    /**
     * Shows profile settings form.
     *
     * @return string|\yii\web\Response
     */
    public function actionProfilePicture($id)
    {
        //$model = $this->finder->findProfileById(\Yii::$app->user->identity->getId());
        $user    = $this->findModel($id);
        $profile = $user->profile;
        /*         if ($model == null) {
            $model = \Yii::createObject(Profile::className());
            $model->link('user', \Yii::$app->user->identity);
        } */
        $nama_arsip = $profile->file_name;

        $request = Yii::$app->request;
        if ($request->isAjax) {
            if (Uploader::upload($profile->user_id, $nama_arsip, 'file_name', $profile, '.', ['mimeTypes' => ['image/*'], 'maxSize' => 2100000])) {
                $test = ['result' => 'success'];
                echo json_encode($test);
            } else {
                $test = ['result' => 'error'];
                echo json_encode($test);
            }
        } else {
            Theming::setThemes(Yii::$app->params['backEndTheme']);
            return $this->render('@app/overrides/user/views/admin/propic', [
                'model'         => $profile,
                'suffix'        => 'admin',
                'user'          => $user,
                /*                 'profile'       => $profile, */
                'dropdown_data' => $this->dropdown_data,
            ]);
        }
    }
}
