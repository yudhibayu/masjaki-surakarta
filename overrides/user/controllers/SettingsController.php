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
use app\components\Uploader;
use app\modules\businessEntity\models\BusinessEntity;
use app\modules\construction\models\Labor;
use infinindotech\user\controllers\SettingsController as SetCon;
use app\overrides\user\models\SettingsForm;
use Yii;
use yii\base\Theme;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\overrides\user\models\Profile;
use app\overrides\user\models\User;
use yii\web\NotFoundHttpException;

/**
 * SettingsController manages updating user settings (e.g. profile, email and password).
 *
 * @property \infinindotech\user\Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class SettingsController extends SetCon
{
    private $dropdown_data; 
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'disconnect' => ['post'],
                    'delete'     => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['profile', 'account', 'profileadmin', 'accountadmin', 'networks', 'disconnect', 'delete', 'departmentlist', 'profile-picture', 'uploaded-picture'],
                        'roles'   => ['@'],
                    ],
                    [
                        'allow'   => true,
                        'actions' => ['confirm'],
                        'roles'   => ['?', '@'],
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
     * Shows profile settings form.
     *
     * @return string|\yii\web\Response
     */
    public function actionProfile()
    {
        $this->populate_dropdown();
        $model = $this->finder->findProfileById(\Yii::$app->user->identity->getId());

        if ($model == null) {
            $model = \Yii::createObject(Profile::className());
            $model->link('user', \Yii::$app->user->identity);
        }

        $event = $this->getProfileEvent($model);

        $this->performAjaxValidation($model);

        $this->trigger(self::EVENT_BEFORE_PROFILE_UPDATE, $event);
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            Uploader::upload($model->user_id, $model->file_name, 'file_name', 'overrides\user\models\Profile', '.', ['mimeTypes' => ['image/*'], 'maxSize' => 2100000]);
            \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'Your profile has been updated'));
            $this->trigger(self::EVENT_AFTER_PROFILE_UPDATE, $event);
            return $this->refresh();
        }
        Theming::setThemes(Yii::$app->params['backEndTheme']);
        return $this->render('@app/overrides/user/views/settings/profile', [
            'model' => $model,
            'suffix' => '',
            'dropdown_data' => $this->dropdown_data,
        ]);
    }

    /**
     * Displays page where user can update account settings (username, email or password).
     *
     * @return string|\yii\web\Response
     */
    public function actionAccount()
    {
        /** @var SettingsForm $model */
        $model = \Yii::createObject(SettingsForm::className());
        $event = $this->getFormEvent($model);

        $this->performAjaxValidation($model);

        $this->trigger(self::EVENT_BEFORE_ACCOUNT_UPDATE, $event);
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash('success', \Yii::t('user', 'Your account details have been updated'));
            $this->trigger(self::EVENT_AFTER_ACCOUNT_UPDATE, $event);
            return $this->refresh();
        }
        Theming::setThemes(Yii::$app->params['backEndTheme']);
        return $this->render('@app/overrides/user/views/settings/account', [
            'model' => $model,
            'suffix' => '',
        ]);
    }

    /**
     * Shows profile settings form.
     *
     * @return string|\yii\web\Response
     */
    public function actionProfileadmin()
    {
        $this->populate_dropdown();
        $model = $this->finder->findProfileById(\Yii::$app->user->identity->getId());

        if ($model == null) {
            $model = \Yii::createObject(Profile::className());
            $model->link('user', \Yii::$app->user->identity);
        }

        $event = $this->getProfileEvent($model);

        $this->performAjaxValidation($model);
        //$nama_arsip = $model->file_name;
        $this->trigger(self::EVENT_BEFORE_PROFILE_UPDATE, $event);
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            //Uploader::upload($model->user_id, $nama_arsip, 'file_name', $model, 'propic');
            \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'Your profile has been updated'));
            $this->trigger(self::EVENT_AFTER_PROFILE_UPDATE, $event);
            return $this->refresh();
        }
        Theming::setThemes(Yii::$app->params['backEndTheme']);
        return $this->render('@app/overrides/user/views/settings/profile', [
            'model' => $model,
            'suffix' => 'admin',
            'dropdown_data' => $this->dropdown_data,
        ]);
    }

    /**
     * Displays page where user can update account settings (username, email or password).
     *
     * @return string|\yii\web\Response
     */
    public function actionAccountadmin()
    {
        /** @var SettingsForm $model */
        $model = \Yii::createObject(SettingsForm::className());
        $getbusiness = BusinessEntity::find()->where(['username' => $model->username])->one();
        $getlabor = Labor::find()->where(['username' => $model->username])->one();
        $event = $this->getFormEvent($model);

        $this->performAjaxValidation($model);

        $this->trigger(self::EVENT_BEFORE_ACCOUNT_UPDATE, $event);
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            if ($getbusiness) {
                $getbusiness->username = $model->username;
                $user = User::find()->where(['username' => $model->username])->one();
                $getbusiness->password_hash = $user->password_hash;
                $getbusiness->save(false);
            }
            if ($getlabor) {
                $getlabor->username = $model->username;
                $user = User::find()->where(['username' => $model->username])->one();
                $getlabor->password_hash = $user->password_hash;
                $getlabor->save(false);
            }
            \Yii::$app->session->setFlash('success', \Yii::t('user', 'Your account details have been updated'));
            $this->trigger(self::EVENT_AFTER_ACCOUNT_UPDATE, $event);
            return $this->refresh();
        }
        Theming::setThemes(Yii::$app->params['backEndTheme']);
        return $this->render('account', [
            'model' => $model,
            'suffix' => 'admin',
        ]);
    }

    /**
     * Shows profile settings form.
     *
     * @return string|\yii\web\Response
     */
    public function actionProfilePicture()
    {
        $model = $this->finder->findProfileById(\Yii::$app->user->identity->getId());

        if ($model == null) {
            $model = \Yii::createObject(Profile::className());
            $model->link('user', \Yii::$app->user->identity);
        }
        $nama_arsip = $model->file_name;

        $request = Yii::$app->request;
        if ($request->isAjax) {
            if (Uploader::upload($model->user_id, $nama_arsip, 'file_name', $model, '.', ['mimeTypes' => ['image/*'], 'maxSize' => 2100000])) {
                $test = ['result' => 'success'];
                echo json_encode($test);
            } else {
                $test = ['result' => 'error'];
                echo json_encode($test);
            }
        } else {
            Theming::setThemes(Yii::$app->params['backEndTheme']);
            return $this->render('@app/overrides/user/views/settings/propic', [
                'model' => $model,
                'suffix' => 'admin',
                'dropdown_data' => $this->dropdown_data,
            ]);
        }
    }

    public function actionUploadedPicture($data = null)
    {
        if (!$data) {
            throw new NotFoundHttpException('Data tidak ditemukan');
        }
        $file_path = Yii::getAlias('@upload/' . $data);
        if (!file_exists($file_path)) {
            $file_path2 = Yii::getAlias('@upload/blank-propic.png');
            if (!file_exists($file_path2)) {
                throw new NotFoundHttpException('Data tidak ditemukan');
            }
            return $this->response->sendFile($file_path2, 'propic', ['inline' => true]);
        }
        return $this->response->sendFile($file_path, 'propic', ['inline' => true]);
    }

}
