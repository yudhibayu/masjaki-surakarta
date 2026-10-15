<?php

namespace app\models;

use Yii;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\i18n\Formatter;
use yii\db\ActiveRecord;
use app\overrides\user\models\User;
use kartik\password\StrengthValidator;
use infinindotech\user\helpers\Password;

/**
 * This is the model class for table "tbl_account_request".
 *
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property int $requested_account_type
 * @property string|null $note
 * @property int $confirm_status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class TblAccountRequest extends \yii\db\ActiveRecord
{
    public $sess;
    public $verifyCode;
    public $password;
    public $password_confirm;

    const REQUEST_ACCOUNT_PLANNER = 0;
    const REQUEST_ACCOUNT_SUPERVISOR = 1;

    const CONFIRM_STATUS_PENDING = 0;
    const CONFIRM_STATUS_ACCEPTED = 1;
    const CONFIRM_STATUS_REJECTED = 2;

    public static function requestAccountLabels()
    {
        return [
            self::REQUEST_ACCOUNT_PLANNER => Yii::t('app', 'Planner'),
            self::REQUEST_ACCOUNT_SUPERVISOR => Yii::t('app', 'Supervisor'),
        ];
    }

    public static function confirmStatusLabels()
    {
        return [
            self::CONFIRM_STATUS_PENDING => Yii::t('app', 'Pending'),
            self::CONFIRM_STATUS_ACCEPTED => Yii::t('app', 'Accepted'),
            self::CONFIRM_STATUS_REJECTED => Yii::t('app', 'Rejected'),
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
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_account_request';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    /**
     * Model Behaviors
     * Blameable = table field harus ada: created_by dan updated_by
     * Timestamp = table field harus ada: created_at dan updated_at
     * @return void
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => date('Y-m-d H:i:s'),
            ],
            [
                'class' => BlameableBehavior::class,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['note'], 'trim'],
            [['name', 'username', 'email'], 'required'],
            [['requested_account_type', 'confirm_status', 'created_by', 'updated_by'], 'integer'],
            [['note'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name',], 'string', 'max' => 255],
            [['password_hash', 'password', 'password_confirm'], 'string', 'max' => 60],
            [['username',], 'string', 'min' => 3, 'max' => 60],
            // [['password', 'password_confirm'], 'required'],
            [['username', 'email'], 'trim'],
            [['username', 'email'], 'validateUsernameEmailUnique', 'message' => '{attribute} sudah digunakan.',],
            [['email'], 'email'],
            [
                'username', 'match',
                'pattern' => '/^[-a-zA-Z0-9_\.@]+$/',
                'message' => '{attribute} harus berupa kombinasi huruf kecil dan angka, tanpa spasi dan simbol lainnya.',
            ],
            [['verifyCode'], 'captcha', 'captchaAction' => '/user/security/captcha'],

            'passwordMatch'    => $this->userPasswordValidator(),
            'passwordLength'   => ['password', 'string', 'min' => 8, 'max' => 16, 'on' => ['register', 'create', 'update']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Full Name'),
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'password_confirm' => Yii::t('app', 'Password Confirm'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'requested_account_type' => Yii::t('app', 'Requested Account Type'),
            'note' => Yii::t('app', 'Note'),
            'confirm_status' => Yii::t('app', 'Confirm Status'),
            'verifyCode' => Yii::t('user', 'Captcha'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function validateUsernameEmailUnique()
    {
        $checkusernamelocal = self::find()->where(['username' => $this->username])->andWhere(['confirm_status' => self::CONFIRM_STATUS_PENDING])->one();
        if ($checkusernamelocal) {
            $this->addError('username', Yii::t('app', 'Username already taken'));
        }
        $checkemaillocal = self::find()->where(['email' => $this->email])->andWhere(['confirm_status' => self::CONFIRM_STATUS_PENDING])->one();
        if ($checkemaillocal) {
            $this->addError('email', Yii::t('app', 'Email already taken'));
        }
        $checkusername = User::findOne(['username' => $this->username]);
        if ($checkusername) {
            $this->addError('username', Yii::t('app', 'Username already taken'));
        }
        $checkemail = User::findOne(['email' => $this->email]);
        if ($checkemail) {
            $this->addError('email', Yii::t('app', 'Email already taken'));
        }
    }
    // Daerah Start relation

    /**
     * Get created_by relation from [[User]] table.
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUserMaker()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Get updated_by relation from [[User]] table.
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUserUpdater()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }
    //Daerah Akhir Relation



    /**
     * {@inheritdoc}
     * @return TblAccountRequestQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblAccountRequestQuery(get_called_class());
    }

    //Daerah Start Virtual Attribute

    /**
     * Virtual Attribute for Full Name
     *
     * @return string
     */
    public function getNamaLengkap()
    {
        return $this->name;
    }

    /**
     * Virtual Attribute for Created By Name
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getMaker()
    {
        $data = null;
        if ($this->userMaker) {
            $data = $this->userMaker->profile->name ? $this->userMaker->profile->name : $this->userMaker->username;
        }
        return $data;
    }

    /**
     * Virtual Attribute for Updated By Name
     *
     * @return \yii\db\ActiveQuery|UserTypeQuery
     */
    public function getUpdater()
    {
        $data = null;
        if ($this->userUpdater) {
            $data = $this->userUpdater->profile->name ? $this->userUpdater->profile->name : $this->userUpdater->username;
        }
        return $data;
    }

    //Daerah Akhir Virtual Attribute

    //Daerah Start Event Driven
    /**
     * Sebelum melakukan validasi input dan sebelum event beforeSave
     *
     * @return void
     */
    public function beforeValidate()
    {
        // Converting to db standard format
        // cth: $this->tanggal_mulai   = $this->tanggal_mulai ? Yii::$app->formatter->asDate($this->tanggal_mulai, 'yyyy-MM-dd') : null;

        parent::beforeValidate();
        return true;
    }

    /**
     * Sebelum disimpan, sesuaikan dnegan format db
     *
     * @param [type] $insert
     * @return void
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            // tulis sini jika ini adalah new insert
            if ($this->password) {
                $this->password_hash = Password::hash($this->password);
            }
        } else {
            // tulis sini jika dijalankan hanya saat update
            if ($this->password != '') {
                $this->password_hash = Password::hash($this->password);
            }
        }
        // tulis sini saat update/insert

        parent::beforeSave($insert);
        return true;
    }

    /**
     * Setelah query ditemukan/selesai dan akan ditampilkan ke view
     *
     * @return void
     */
    public function afterFind()
    {
        // monggo

        parent::afterFind();
        return true;
    }

    /**
     * Sebelum hapus
     *
     * @return void
     */
    public function beforeDelete()
    {
        // monggo

        parent::beforeDelete();
        return true;
    }
    //Daerah Akhir Event Driven

    public static function userPasswordValidator($fields = ['password'])
    {
        return [
            $fields, StrengthValidator::class,
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