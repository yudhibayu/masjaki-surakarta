<?php

namespace app\modules\construction\models;

use Yii;
use yii\i18n\Formatter;

use yii\db\ActiveRecord;
use app\overrides\user\models\User;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use infinindotech\user\helpers\Password;
use app\modules\referencesMasjaki\models\Education;
use app\modules\referencesMasjaki\models\RefNegara;
use app\modules\referencesMasjaki\models\RefKabkota;
use app\modules\referencesMasjaki\models\RefProvinsi;
use app\modules\referencesMasjaki\models\RefKecamatan;
use app\modules\referencesMasjaki\models\RefKelurahan;
use app\modules\construction\models\ConstructionVacancies;

/**
 * This is the model class for table "labor".
 *
 * @property int $id id
 * @property string $name nama 
 * 
 * @property int $nik_passport_number nik nomor paspor
 * @property int $npwp npwp
 * @property int $phone_number nomor telepon
 * @property string $email email
 * @property string $username username
 * @property string $password_hash password hash
 * @property string $account_verification verifikasi akun
 * @property string $skilled_power_type jenis tenaga terampil
 * @property string $citizenship kewarganegaraan
 * @property int $bpjs_health_number nomor bpjs kesehatan
 * @property int $employment_bpjs_number nomor bpjs ketenagakerjaan
 * @property string $last_education pendidikan terakhir
 * @property int $long_work_experience lama pengalaman kerja
 * @property string $employment_status status kepegawaian
 * @property string $professional_expertise profesi keahlian
 * @property string $address alamat
 * @property string|null $id_country negara
 * @property string|null $id_province provinsi
 * @property string|null $id_regency kabupaten
 * @property string|null $id_subdistrict kecamatan
 * @property string|null $id_ward kelurahan
 * @property string|null $country negara
 * @property string|null $province provinsi
 * @property string|null $regency kabupaten
 * @property string $subdistrict kecamatan
 * @property string $ward kelurahan
 * @property string $description Deskripsi
 * @property string $created_at dibuat di
 * @property string $updated_at diedit di
 * @property string $deleted_at dihapus di
 * @property string $created_by dibuat oleh
 * @property string $updated_by diedit oleh
 * @property string $deleted_by dihapus oleh
 */
class Labor extends \yii\db\ActiveRecord
{
    const TETAP = 1;
    const TIDAK_TETAP = 0;

    const UNVERIFICATION = 0;
    const VERIFICATION = 1;
    const BLACKLIST = 2;

    const WNI = 1;
    const WNA = 0;

    public $sess;
    public $password;
    public $confirm_password;
    public $verifyCode;

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
        return 'labor';
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
            [['name', 'nik_passport_number', 'npwp', 'phone_number', 'email', 'username', 'skilled_power_type', 'citizenship', 'bpjs_health_number', 'employment_bpjs_number', 'last_education', 'long_work_experience', 'employment_status', 'professional_expertise', 'address', 'id_country', 'id_province', 'id_regency', 'id_subdistrict', 'id_ward'], 'required'],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message' => 'Konfirmasi password tidak sama'],
            [['long_work_experience', 'account_verification'], 'integer'],
            [['employment_status', 'description'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name', 'email', 'username', 'password_hash', 'password', 'skilled_power_type', 'citizenship', 'last_education', 'professional_expertise', 'npwp',  'id_country', 'id_province', 'id_regency', 'id_subdistrict', 'id_ward', 'country', 'province', 'regency', 'subdistrict', 'ward', 'created_by', 'updated_by', 'deleted_by', 'phone_number', 'nik_passport_number', 'bpjs_health_number', 'employment_bpjs_number',], 'string', 'max' => 255],

            [['address'], 'string'],

            // Validasi username
            [['username'], 'match', 'pattern' => '/^\S*$/', 'message' => 'Username tidak boleh mengandung spasi.'],
            [['username'], 'match', 'pattern' => '/^[a-z]+$/', 'message' => 'Username hanya boleh mengandung huruf kecil.'],
            ['email', 'validateEmail'],
            ['username', 'validateUsername'],

            // Validasi password
            [['password'], 'string', 'min' => 8],
            [['password'], 'match', 'pattern' => '/^\S*$/', 'message' => 'Password tidak boleh mengandung spasi.'],
            [['password'], 'match', 'pattern' => '/[A-Z]/', 'message' => 'Password harus mengandung setidaknya satu huruf besar.'],
            [['password'], 'match', 'pattern' => '/[0-9]/', 'message' => 'Password harus mengandung setidaknya satu angka.'],
            [['password'], 'match', 'pattern' => '/[!@#$%^&*(),.?":{}|<>]/', 'message' => 'Password harus mengandung setidaknya satu karakter khusus.'],

            // Validasi email
            // [['email'], 'required'],
            [['email'], 'email'],
            [['email'], 'match', 'pattern' => '/^\S+@\S+\.\S+$/', 'message' => 'Email harus mengandung @ dan tidak boleh mengandung spasi.'],
            ['verifyCode', 'captcha', 'captchaAction' => '/user/security/captcha', 'when' => function ($model) {
                return !empty($model->verifyCode);
            }],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'nik_passport_number' => Yii::t('app', 'NIK/Passport Number'),
            'npwp' => Yii::t('app', 'NPWP'),
            'phone_number' => Yii::t('app', 'Phone Number'),
            'email' => Yii::t('app', 'Email'),
            'username' => Yii::t('app', 'Username'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'account_verification' => Yii::t('app', 'Account Verification'),
            'skilled_power_type' => Yii::t('app', 'Skilled Power Type'),
            'citizenship' => Yii::t('app', 'Citizenship'),
            'bpjs_health_number' => Yii::t('app', 'BPJS Health Number'),
            'employment_bpjs_number' => Yii::t('app', 'Employment BPJS Number'),
            'last_education' => Yii::t('app', 'Last Education'),
            'long_work_experience' => Yii::t('app', 'Long Work Experience'),
            'employment_status' => Yii::t('app', 'Employment Status'),
            'professional_expertise' => Yii::t('app', 'Professional Expertise'),
            'address' => Yii::t('app', 'Address'),
            'id_province' => Yii::t('app', 'Province'),
            'id_country' => Yii::t('app', 'Country'),
            'id_regency' => Yii::t('app', 'Regency'),
            'id_subdistrict' => Yii::t('app', 'Subdistrict'),
            'id_ward' => Yii::t('app', 'Ward'),
            'country' => Yii::t('app', 'Country'),
            'province' => Yii::t('app', 'Province'),
            'regency' => Yii::t('app', 'Regency'),
            'subdistrict' => Yii::t('app', 'Subdistrict'),
            'ward' => Yii::t('app', 'Ward'),
            'description' => Yii::t('app', 'Description'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'verifyCode' => Yii::t('user', 'Captcha'),
        ];
    }
    public function getEmploymentStatus0()
    {
        return $this->employment_status == $this::TETAP ? 'Tetap' : 'Tidak Tetap';
    }
    public function validateUsername($attribute)
    {
        if ($this->isNewRecord) {
            if (User::find()->where([$attribute => $this->$attribute])->exists()) {
                $this->addError($attribute, 'Username sudah dipakai');
            }
        }
    }

    public function validateEmail($attribute)
    {
        if ($this->isNewRecord) {
            if (User::find()->where([$attribute => $this->$attribute])->exists()) {
                $this->addError($attribute, 'Email sudah dipakai');
            }
        }
    }

    public function getWniWna()
    {
        return $this->citizenship == 1 ? 'WNI' : 'WNA';
    }

    public function getStatusAccountVerification()
    {
        switch ($this->account_verification) {
            case $this::UNVERIFICATION:
                return 'Belum Terverifikasi';
            case $this::VERIFICATION:
                return 'Terverifikasi';
            case $this::BLACKLIST:
                return 'Terblacklist';
            default:
                return 'Status Tidak Diketahui';
        }
    }


    // Daerah Start relation

    /**
     * Gets query for [[RefNegara]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefNegara()
    {
        return $this->hasOne(RefNegara::class, ['id' => 'id_country']);
    }

    /**
     * Gets query for [[RefProvinsi]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefProvinsi()
    {
        return $this->hasOne(RefProvinsi::class, ['id' => 'id_province']);
    }

    /**
     * Gets query for [[RefKabkota]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefKabkota()
    {
        return $this->hasOne(RefKabkota::class, ['id' => 'id_regency']);
    }

    /**
     * Gets query for [[RefKecamatan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefKecamatan()
    {
        return $this->hasOne(RefKecamatan::class, ['id' => 'id_subdistrict']);
    }

    /**
     * Gets query for [[RefKelurahan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefKelurahan()
    {
        return $this->hasOne(RefKelurahan::class, ['id' => 'id_ward']);
    }

    /**
     * Gets query for [[Educatiin]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEducation()
    {
        return $this->hasOne(Education::class, ['id' => 'last_education']);
    }

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
            $data = isset($this->userMaker->profile->name) ? $this->userMaker->profile->name : $this->userMaker->username;
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
            $data = isset($this->userUpdater->profile->name) ? $this->userUpdater->profile->name : $this->userUpdater->username;
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


        if (isset($this->refNegara->name)) {
            $this->country = $this->refNegara->name;
        }
        if (isset($this->refProvinsi->name)) {
            $this->province = $this->refProvinsi->name;
        }
        if (isset($this->refKabkota->name)) {
            $this->regency = $this->refKabkota->name;
        }
        if (isset($this->refKecamatan->name)) {
            $this->subdistrict = $this->refKecamatan->name;
        }
        if (isset($this->refKelurahan->name)) {
            $this->ward = $this->refKelurahan->name;
        }
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

}
