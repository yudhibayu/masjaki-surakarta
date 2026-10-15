<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace app\overrides\user\models;

use app\models\Satker;
use app\models\Usersatker;
use Yii;
use infinindotech\user\models\User as InfinindotechUser;
use app\overrides\user\models\Profile;
use OAuth2\Storage\UserCredentialsInterface;
use kartik\password\StrengthValidator;
use yii\helpers\Html;
/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class User extends InfinindotechUser implements UserCredentialsInterface
{
    public $session;

    /**
     * Init Object Controller
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->session = Yii::$app->session;
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            // username rules
            'usernameTrim'     => ['username', 'trim'],
            'usernameRequired' => ['username', 'required', 'on' => ['register', 'create', 'connect', 'update']],
            'usernameMatch'    => ['username', 'match', 'pattern' => static::$usernameRegexp],
            'usernameLength'   => ['username', 'string', 'min' => 3, 'max' => 255],
            'usernameUnique'   => [
                'username',
                'unique',
                'message' => \Yii::t('user', 'This username has already been taken')
            ],

            // email rules
            'emailTrim'     => ['email', 'trim'],
            'emailRequired' => ['email', 'required', 'on' => ['register', 'connect', 'create', 'update']],
            'emailPattern'  => ['email', 'email'],
            'emailLength'   => ['email', 'string', 'max' => 255],
            'emailUnique'   => [
                'email',
                'unique',
                'message' => \Yii::t('user', 'This email address has already been taken')
            ],

            // password rules
            'passwordRequired' => ['password', 'required', 'on' => ['register']],
            // 'passwordMatch'    => ['password', 'match', 'pattern' => static::$passwordRegexp, 'message' => 'Your password should contain atleast 2 digit and password length must be of 8'],
            'passwordMatch'    => $this->userPasswordValidator(),
            'passwordLength'   => ['password', 'string', 'min' => 8, 'max' => 16, 'on' => ['register', 'create', 'update']],

            // kolom berapa kali password dirubah
            [['pwd_expire',], 'number'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne($this->module->modelMap['Profile'], ['user_id' => 'id']);
    }

    /**
     * @param Profile $profile
     */
    public function setProfile($profile)
    {
        $this->_profile = $profile;
    }

    /**
     * Returns user role name according to RBAC
     * @return string
     */
    public function getRoleName()
    {
        $roles = Yii::$app->authManager->getRolesByUser($this->id);
        if (!$roles) {
            return null;
        }

        reset($roles);
        /* @var $role \yii\rbac\Role */
        $role = current($roles);

        return $role->name;
    }

    /**
     * Gets query for [[UserUnit]].
     *
     * @return \yii\db\ActiveQuery|UserUnitQuery
     */
    public function getUnitName()
    {
        return $this->refUnit->name;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // return static::findOne(['auth_key' => $token]);
        $module = Yii::$app->getModule('oauth2');
        $is_valid = $module->getServer()->verifyResourceRequest();
        // var_dump($is_valid);exit;

        if($is_valid){
            $data = $module->getServer()->getAccessTokenData(\OAuth2\Request::createFromGlobals());
            // var_dump($data);exit;
            if((!$data['user_id']) && (!$data['client_id'])){
                return null;
            }
            else if((!$data['user_id']) && $data['client_id']){
                return null;
            }
            $user_id = $data['user_id'];
            $expires = $data['expires'];
            // var_dump([,]);
            if(date('Y-m-d H:i:s',$expires) < date('Y-m-d H:i:s',time())){
                return null;
            }
            return static::findOne(['id' => $user_id]);
        }
        else{
            return null;
        }
    }

    /**
     * Gets query for [[UserUnit]].
     *
     * @return \yii\db\ActiveQuery|UserUnitQuery
     */
    public function getUserUnit()
    {
        return $this->hasOne(Usersatker::className(), ['user_id' => 'id', 'fiscal_year' => $this->session['milestone']['year_name']]);
    }

    /**
     * Gets query for [[UserUnit]].
     *
     * @return \yii\db\ActiveQuery|UserUnitQuery
     */
    public function getRefUnit()
    {
        return $this->hasOne(Satker::className(), ['id' => 'satker_id'])->via('userUnit');
    }

    public function checkUserCredentials($dataId, $password)
    {
        $user = $this->findOne(['email'=>$dataId]);
        
        if($user==null){
            $user = $this->findOne(['username'=>$dataId]);
        }
        
        if($user){
            if(Yii::$app->getSecurity()->validatePassword($password,$user->password_hash)){
                return true;
            }
        }
        return false;
    }

    public function getUserDetails($dataId)
    {
        $user = $this->findOne(['email'=>$dataId]);
        if(!$user){
            $user = $this->findOne(['username'=>$dataId]);
        }
        if($user){
            return array('user_id'=>$user->id);
        }
        return false;

    }

    public static function userIdentifierCallback($id)
    {
        $user = self::findOne($id);
        return isset($user->username) ? Html::a($user->username, ['/user/admin', 'UserSearch[username]' => $user->username], ['data-pjax' => 0]) : $id;
    }

    public static function userPasswordValidator($fields = ['password'])
    {
        return [
            $fields, StrengthValidator::className(),
            // 'preset' => 'normal', 
            'userAttribute' => 'email',
            'lower' => 1,
            'upper' => 1,
            'digit' => 1,
            'special' => 1,
            'repeat' => 5,
            'min' => 8,
            'max' => 30,
            'lowerError' => '{attribute} harus memiliki setidaknya {n, plural, one{satu huruf kecil} other{# huruf kecil}} ({found} ditemukan)!',
            'upperError' => '{attribute} harus memiliki setidaknya {n, plural, one{satu huruf besar} other{# huruf besar}} ({found} ditemukan)!',
            'digitError' => '{attribute} harus memiliki setidaknya {n, plural, one{satu karakter angka} other{# karakter angka}} ({found} ditemukan)!',
            'specialError' => '{attribute} harus memiliki setidaknya {n, plural, one{satu karakter spesial} other{# karakter spesial}} ({found} ditemukan)!',
            // 'repeatError' => '{attribute} tidak boleh memiliki lebih dari {n, plural, one{satu karakter berulang} other{# karakter berulang}} ({found} ditemukan)!',
        ];
    }
}
