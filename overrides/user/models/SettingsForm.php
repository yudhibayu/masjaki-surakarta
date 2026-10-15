<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\overrides\user\models;

use infinindotech\user\helpers\Password;
use infinindotech\user\Mailer;
use infinindotech\user\Module;
use infinindotech\user\traits\ModuleTrait;
use Yii;
use yii\base\Model;
use infinindotech\user\models\SettingsForm as dekSF;


/**
 * SettingsForm gets user's username, email and password and changes them.
 *
 * @property User $user
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class SettingsForm extends dekSF
{
    /** @inheritdoc */
    public function rules()
    {
        return [
            'usernameTrim' => ['username', 'trim'],
            'usernameRequired' => ['username', 'required'],
            'usernameLength'   => ['username', 'string', 'min' => 3, 'max' => 255],
            'usernamePattern' => ['username', 'match', 'pattern' => '/^[-a-zA-Z0-9_\.@]+$/'],
            'emailTrim' => ['email', 'trim'],
            'emailRequired' => ['email', 'required'],
            'emailPattern' => ['email', 'email'],
            'emailUsernameUnique' => [['email', 'username'], 'unique', 'when' => function ($model, $attribute) {
                return $this->user->$attribute != $model->$attribute;
            }, 'targetClass' => $this->module->modelMap['User']],
            'newPasswordLength' => ['new_password', 'string', 'max' => 72, 'min' => 8],
            'newPasswordMatch'  => User::userPasswordValidator(['new_password']),
            'currentPasswordRequired' => ['current_password', 'required'],
            'currentPasswordValidate' => ['current_password', function ($attr) {
                if (!Password::validate($this->$attr, $this->user->password_hash)) {
                    $this->addError($attr, Yii::t('user', 'Current password is not valid'));
                }
            }],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'email'            => Yii::t('user', 'Email'),
            'username'         => Yii::t('user', 'Username'),
            'new_password'     => Yii::t('user', 'New password'),
            'current_password' => Yii::t('user', 'Current password'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // $behaviors[] = 'bedezign\yii2\audit\AuditTrailBehavior';
        return $behaviors;
    }

        /**
     * Saves new account settings.
     *
     * @return bool
     */
    public function save()
    {
        if ($this->validate()) {
            $this->user->scenario = 'settings';
            $this->user->username = $this->username;
            $this->user->password = $this->new_password;
            if ($this->email == $this->user->email && $this->user->unconfirmed_email != null) {
                $this->user->unconfirmed_email = null;
            } elseif ($this->email != $this->user->email) {
                switch ($this->module->emailChangeStrategy) {
                    case Module::STRATEGY_INSECURE:
                        $this->insecureEmailChange();
                        break;
                    case Module::STRATEGY_DEFAULT:
                        $this->defaultEmailChange();
                        break;
                    case Module::STRATEGY_SECURE:
                        $this->secureEmailChange();
                        break;
                    default:
                        throw new \OutOfBoundsException('Invalid email changing strategy');
                }
            }

            $new_pwd = $this->new_password;
            $current_pwd = $this->current_password;

            $return = $this->user->save();

            if($new_pwd && ($new_pwd != $current_pwd)){
                $userpwd = User::findOne(['id' => $this->user->id]);
                $userpwd->pwd_expire = time() + 31556926;
                $userpwd->save();
            }
            
            return $return;
        }

        return false;
    }
}
