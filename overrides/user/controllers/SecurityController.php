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

use yii\helpers\ArrayHelper;
use yii\base\Theme;
use infinindotech\user\controllers\SecurityController as SecCon;
use app\overrides\user\models\LoginForm;
use app\modules\references\models\Year_model as Reftahun; // use Year_model module
use app\components\Theming;
use app\modules\businessEntity\models\BusinessEntity;
use app\modules\construction\models\Labor;
use app\overrides\user\models\User;
use Yii;
use yii\filters\AccessControl;
//use app\modules\manage_unit\models\UserUnit;


/**
 * Controller that manages user authentication process.
 *
 * @property Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class SecurityController extends SecCon
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['captcha'],
                'rules' => [
                    [
                        'actions' => ['captcha'],
                        'allow' => true,
                        'roles' => ['?', '@'],

                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                // 'class' => 'yii\captcha\CaptchaAction',
                'class' => 'app\overrides\actions\MathCaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    /**
     * Displays the login page.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        $tahuns = null;
        $yearlogincheck = Yii::$app->params['enableYearLogin']; // if true, user registration button and access are enabled
        if (!Yii::$app->params['enableLogin']) {
            $this->goHome();
        }
        if (!\Yii::$app->user->isGuest) {
            $this->goHome();
        }

        /** @var LoginForm $model */
        $model = \Yii::createObject(LoginForm::class);
        $event = $this->getFormEvent($model);
        if ($yearlogincheck) {
            $tahuns = ArrayHelper::map(Reftahun::find()->where(['active_login' => 'T'])->orderBy('name')->all(), 'name', 'name');
        }
        $this->performAjaxValidation($model);

        $this->trigger(self::EVENT_BEFORE_LOGIN, $event);

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->login()) {
            $this->trigger(self::EVENT_AFTER_LOGIN, $event);

            // if (Yii::$app->user->can('OPD') && !Yii::$app->user->can('Team')) {
            //     $user_unit = UserUnit::find()->where(['user_id' => $user_check->id, 'mod_ref_year_id' => $model->tahun])->one();
            //     if (!$user_unit) {
            //         return $this->redirect('/manage_unit/user-unit/unit');
            //     } else {
            //         $this->setUnit($user_unit);
            //         return $this->redirect('/admin');
            //     }
            // } else {
            // //    return $this->redirect('/admin');
            // }

            $user_check = Yii::$app->user->identity;
            if (!Yii::$app->user->can('Team') && Yii::$app->user->can('Badan Usaha')) {
                $badan_usaha = User::find()->where(['id' => $user_check->id])->one();
                if (!$badan_usaha) {
                    return $this->redirect('/admin');
                } else {
                    $this->setBadanUsaha($badan_usaha);
                    return $this->redirect('/admin');
                }
            } elseif (!Yii::$app->user->can('Team') && Yii::$app->user->can('Tenaga Kerja')) {
                $tenaga_kerja = User::find()->where(['id' => $user_check->id])->one();
                if (!$tenaga_kerja) {
                    return $this->redirect('/admin');
                } else {
                    $this->setTenagaKerja($tenaga_kerja);
                    return $this->redirect('/admin');
                }
            } else {
                return $this->redirect('/admin');
            }
        }
        Theming::setThemes(Yii::$app->params['loginTheme']);
        return $this->render('login', [
            'model'  => $model,
            'tahuns' => $tahuns,
            'module' => $this->module,
        ]);
    }

    /**
     * Set Pilihan OPD to session
     *
     * @return User|null
     */
    public function setUnit($user_unit)
    {
        $session   = Yii::$app->session;
        $session['unit'] = [
            'user_id'     => $user_unit->user_id,
            'unit_id'     => $user_unit->ref_unit_id,
            'unit_name'   => $user_unit->name,
            'risk_code'   => $user_unit->refUnit->risk_code,
        ];
    }

    public function setBadanUsaha($badanUsaha)
    {
        $businessEntity = BusinessEntity::findOne(['id' => $badanUsaha->id_business_entity]);
        $session   = Yii::$app->session;
        $session['badan_usaha'] = [
            'id_user'                => $badanUsaha->id,
            'id_business_entity'     => $badanUsaha->id_business_entity,
            'username'               => $businessEntity->username,
        ];
    }

    public function setTenagaKerja($tenagaKerja)
    {
        $labor = Labor::findOne(['id' => $tenagaKerja->id_labor]);
        $session   = Yii::$app->session;
        $session['tenaga_kerja'] = [
            'id_user'                => $tenagaKerja->id,
            'id_labor'               => $tenagaKerja->id_labor,
            'username'               => $labor->username,
        ];
    }
}
