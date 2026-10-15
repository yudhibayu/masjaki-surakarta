<?php

namespace app\overrides\user\models;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use infinindotech\user\traits\ModuleTrait;
use infinindotech\user\models\LoginForm as dekLF;
use infinindotech\user\helpers\Password;
use infinindotech\user\Finder;
use overrides\user\models\User;
use Yii;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends dekLF
{
    public $verifyCode;
    public $tahun;

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'login'      => Yii::t('user', 'Login'),
            'password'   => Yii::t('user', 'Password'),
            'tahun'      => Yii::t('app', 'Year'),
            'rememberMe' => Yii::t('user', 'Remember me next time'),
            'verifyCode' => Yii::t('user', 'Captcha'),
        ];
    }

    /** @inheritdoc */
    public function rules()
    {
        $rules = [
            'loginTrim' => ['login', 'trim'],
            'requiredFields' => [['login'], 'required'],
            'confirmationValidate' => [
                'login',
                function ($attribute) {
                    if ($this->user !== null) {
                        $confirmationRequired = $this->module->enableConfirmation
                            && !$this->module->enableUnconfirmedLogin;
                        if ($confirmationRequired && !$this->user->getIsConfirmed()) {
                            $this->addError($attribute, Yii::t('user', 'You need to confirm your email address'));
                        }
                        if ($this->user->getIsBlocked()) {
                            $this->addError($attribute, Yii::t('user', 'Your account has been blocked'));
                        }
                    }
                }
            ],
            'rememberMe' => ['rememberMe', 'boolean'],
            [['verifyCode'], 'captcha', 'captchaAction' => '/user/security/captcha'],
        ];

        if (!$this->module->debug) {
            if (Yii::$app->params['enableYearLogin']){
                $rules = array_merge($rules, [
                    
                    'requiredFields' => [['login', 'password', 'tahun'], 'required'],
                    'passwordValidate' => [
                        'password',
                        function ($attribute) {
                            if ($this->user === null || !Password::validate($this->password, $this->user->password_hash)) {
                                $this->addError($attribute, Yii::t('user', 'Invalid login or password'));
                            }
                        }
                    ]
                ]);
            } else {
                $rules = array_merge($rules, [
                    
                    'requiredFields' => [['login', 'password'], 'required'],
                    'passwordValidate' => [
                        'password',
                        function ($attribute) {
                            if ($this->user === null || !Password::validate($this->password, $this->user->password_hash)) {
                                $this->addError($attribute, Yii::t('user', 'Invalid login or password'));
                            }
                        }
                    ]
                ]);
            }
        } else {
            if (Yii::$app->params['enableYearLogin']){
                $rules = array_merge($rules, [
                    'requiredFields' => [['tahun'], 'required'],
                ]);
            }
        }

        return $rules;
    }

    /**
     * Validates form and logs the user in.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate() && $this->user) {
            $isLogged = Yii::$app->getUser()->login($this->user, $this->rememberMe ? $this->module->rememberFor : 0);

            if ($isLogged) {
                if (Yii::$app->params['enableYearLogin']){
                    $this->setTahun();
                }
                $this->user->updateAttributes(['last_login_at' => time()]);
            }
            return $isLogged;
        }

        return false;
    }

    /**
     * Set Pilihan tahun to session
     *
     * @return User|null
     */
    public function setTahun()
    {
        $session   = Yii::$app->session;
        $session['tahun'] = $this->tahun;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[] = [
            'class' => '\giannisdag\yii2CheckLoginAttempts\behaviors\LoginAttemptBehavior',
            // Amount of attempts in the given time period
            'attempts' => 3,
            // the duration, in seconds, for a regular failure to be stored for
            // resets on new failure
            'duration' => 90,
            // the duration, in seconds, to disable login after exceeding `attemps`
            'disableDuration' => 90,
            // the attribute used as the key in the database
            // and add errors to
            'usernameAttribute' => 'login',
            // the attribute to check for errors
            'passwordAttribute' => 'password',
            // the validation message to return to `usernameAttribute`
            'message' => Yii::t('app', 'Login disabled for {minutes} minutes', ['minutes' => 1.5]),
        ];
        // $behaviors[] = 'bedezign\yii2\audit\AuditTrailBehavior';
        return $behaviors;
    }

}
