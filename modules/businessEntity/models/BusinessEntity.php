<?php

namespace app\modules\businessEntity\models;

use Yii;

use yii\i18n\Formatter;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use app\overrides\user\models\User;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use infinindotech\user\helpers\Password;
use app\modules\referencesMasjaki\models\RefNegara;
use app\modules\referencesMasjaki\models\RefKabkota;
use app\modules\referencesMasjaki\models\RefProvinsi;
use app\modules\referencesMasjaki\models\RefKecamatan;
use app\modules\referencesMasjaki\models\RefKelurahan;
use app\modules\referencesMasjaki\models\FormOfBusiness;

/**
 * This is the model class for table "business_entity".
 *
 * @property int $id id
 * @property string $username username
 * @property string $password kata sandi
 * @property string $email email
 * @property int|null $npwp npwp
 * @property string|null $company_name nama perusahaan
 * @property string|null $form_of_business bentuk usaha
 * @property string|null $photo foto
 * @property string $file_name Nama Foto
 * @property string $file_path Direktori Foto
 * @property string $file_extension Format Foto
 * @property string $file_size Ukuran Foto
 * @property int|null $phone_number nomor telepon
 * @property int|null $pkp_confirmation_number nomor pengukuhan pkp
 * @property string|null $website situs web
 * @property string|null $fax fax
 * @property int|null $postal_code kode pos
 * @property string|null $company_address alamat perusahaan
 * @property string|null $id_country negara
 * @property string|null $id_province provinsi
 * @property string|null $id_regency kabupaten
 * @property string|null $id_subdistrict kecamatan
 * @property string|null $id_ward kelurahan
 * @property string|null $country negara
 * @property string|null $province provinsi
 * @property string|null $regency kabupaten
 * @property string|null $subdistrict kecamatan
 * @property string|null $ward kelurahan
 * @property string|null $created_at dibuat di
 * @property string|null $updated_at diedit di
 * @property string|null $deleted_at dihapus di
 * @property string|null $created_by dibuat oleh
 * @property string|null $updated_by diedit oleh
 * @property string|null $deleted_by dihapus oleh
 */
class BusinessEntity extends \yii\db\ActiveRecord
{
    const UNVERIFICATION = 0;
    const VERIFICATION = 1;
    const BLACKLIST = 2;

    public $sess;
    public $password;
    public $file_data;
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
        return 'business_entity';
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
            // Validasi untuk field wajib
            [['username', 'email', 'npwp', 'phone_number', 'postal_code', 'company_name', 'form_of_business', 'company_address', 'id_country', 'id_province', 'id_regency', 'id_subdistrict', 'id_ward'], 'required'],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message' => 'Konfirmasi password tidak sama'],

            // Validasi untuk field integer
            [['postal_code', 'account_verification', 'file_size'], 'integer'],

            // Validasi untuk timestamp
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],

            // Validasi untuk field string dengan panjang maksimum
            [['pkp_confirmation_number', 'npwp', 'phone_number', 'username', 'password_hash', 'password', 'email', 'company_name', 'form_of_business', 'photo', 'website', 'fax', 'id_country', 'id_province', 'id_regency', 'id_subdistrict', 'id_ward', 'country', 'province', 'regency', 'subdistrict', 'ward', 'created_by', 'updated_by', 'deleted_by', 'file_name', 'file_extension', 'file_path'], 'string', 'max' => 255],

            [['company_address'], 'string'],

            // Validasi username
            [['username'], 'match', 'pattern' => '/^\S*$/', 'message' => 'Username tidak boleh mengandung spasi.'],
            [['username'], 'match', 'pattern' => '/^[a-z]+$/', 'message' => 'Username hanya boleh mengandung huruf kecil.'],

            // Validasi password
            [['password'], 'string', 'min' => 8],
            [['password'], 'match', 'pattern' => '/^\S*$/', 'message' => 'Password tidak boleh mengandung spasi.'],
            [['password'], 'match', 'pattern' => '/[A-Z]/', 'message' => 'Password harus mengandung setidaknya satu huruf besar.'],
            [['password'], 'match', 'pattern' => '/[0-9]/', 'message' => 'Password harus mengandung setidaknya satu angka.'],
            [['password'], 'match', 'pattern' => '/[!@#$%^&*(),.?":{}|<>]/', 'message' => 'Password harus mengandung setidaknya satu karakter khusus.'],

            // Validasi email
            [['email'], 'required'],
            [['email'], 'email'],
            [['email'], 'match', 'pattern' => '/^\S+@\S+\.\S+$/', 'message' => 'Email harus mengandung @ dan tidak boleh mengandung spasi.'],
            ['username', 'validateUsername'],
            ['email', 'validateEmail'],
            // Validasi file
            [['file_data'], 'file', 'mimeTypes' => ['image/*']],
            ['verifyCode', 'captcha', 'captchaAction' => '/user/security/captcha', 'when' => function ($model) {
                return !empty($model->verifyCode);
            }],

            // [['photo'], 'file', 'skipOnEmpty' => false, 'extensions' => 'jpg, png, jpeg', 'checkExtensionByMimeType' => true, 'maxSize' => 2 * 1024 * 1024, 'mimeTypes' => ['image/jpeg', 'image/png', 'image/jpg']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'account_verification' => Yii::t('app', 'Account Verification'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password' => Yii::t('app', 'Password'),
            'confirm_password' => Yii::t('app', 'Confirm Password'),
            'email' => Yii::t('app', 'Email'),
            'npwp' => Yii::t('app', 'NPWP'),
            'company_name' => Yii::t('app', 'Company Name'),
            'form_of_business' => Yii::t('app', 'Form of Business'),
            'photo' => Yii::t('app', 'Photo'),
            'file_extension' => Yii::t('app', 'File Extension'),
            'file_size' => Yii::t('app', 'File Size'),
            'file_path' => Yii::t('app', 'File Path'),
            'file_name' => Yii::t('app', 'File Name'),
            'phone_number' => Yii::t('app', 'Phone Number'),
            'pkp_confirmation_number' => Yii::t('app', 'PKP Confirmation Number'),
            'website' => Yii::t('app', 'Website'),
            'fax' => Yii::t('app', 'Fax'),
            'postal_code' => Yii::t('app', 'Postal Code'),
            'company_address' => Yii::t('app', 'Company Address'),
            'id_province' => Yii::t('app', 'Province'),
            'id_country' => Yii::t('app', 'Country'),
            'id_regency' => Yii::t('app', 'Regency'),
            'id_subdistrict' => Yii::t('app', 'Subdistrict'),
            'id_ward' => Yii::t('app', 'Ward'),
            'province' => Yii::t('app', 'Province'),
            'country' => Yii::t('app', 'Country'),
            'regency' => Yii::t('app', 'Regency'),
            'subdistrict' => Yii::t('app', 'Subdistrict'),
            'ward' => Yii::t('app', 'Ward'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'verifyCode' => Yii::t('user', 'Captcha'),

        ];
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


    // /**
    //  * Gets query for [[BusinessPermit]].
    //  *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getBusinessPermit()
    // {
    //     return $this->hasMany(BusinessPermit::class, ['id_business_entity' => 'id']);
    // }

    // /**
    //  * Gets query for [[Administrator]].
    //  *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getAdministrator()
    // {
    //     return $this->hasMany(Administrator::class, ['id_business_entity' => 'id']);
    // }

    // /**
    //  * Gets query for [[ChangeDeed]].
    //  *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getChangeDeed()
    // {
    //     return $this->hasMany(ChangeDeed::class, ['id_business_entity' => 'id']);
    // }

    // /**
    //  * Gets query for [[DeedOfIncorporation]].
    //  *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getDeedOfIncorporation()
    // {
    //     return $this->hasMany(DeedOfIncorporation::class, ['id_business_entity' => 'id']);
    // }


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
     * Gets query for [[FormOfBusiness]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFormOfBusiness()
    {
        return $this->hasOne(FormOfBusiness::class, ['id' => 'form_of_business']);
    }

    /**
     * Gets query for [[Owner]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasMany(Owner::class, ['id_business_entity' => 'id']);
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

    public function search($params)
    {
        $query = BusinessEntity::find();
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'npwp' => $this->npwp,
            'phone_number' => $this->phone_number,
            'pkp_confirmation_number' => $this->pkp_confirmation_number,
            'postal_code' => $this->postal_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'file_size' => $this->file_size,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterWhere(['like', 'form_of_business', $this->form_of_business])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'file_path', $this->file_path])
            ->andFilterWhere(['like', 'file_extension', $this->file_extension])
            ->andFilterWhere(['like', 'file_name', $this->file_name])
            ->andFilterWhere(['like', 'website', $this->website])
            ->andFilterWhere(['like', 'fax', $this->fax])
            ->andFilterWhere(['like', 'company_address', $this->company_address])
            ->andFilterWhere(['like', 'id_country', $this->id_country])
            ->andFilterWhere(['like', 'id_province', $this->id_province])
            ->andFilterWhere(['like', 'id_regency', $this->id_regency])
            ->andFilterWhere(['like', 'id_subdistrict', $this->id_subdistrict])
            ->andFilterWhere(['like', 'id_ward', $this->id_ward])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'regency', $this->regency])
            ->andFilterWhere(['like', 'subdistrict', $this->subdistrict])
            ->andFilterWhere(['like', 'ward', $this->ward])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by]);

        return $dataProvider;
    }
}
