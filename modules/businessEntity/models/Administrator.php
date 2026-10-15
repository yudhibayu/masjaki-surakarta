<?php

namespace app\modules\businessEntity\models;

use Yii;

use yii\i18n\Formatter;
use yii\db\ActiveRecord;
use app\overrides\user\models\User;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use app\modules\referencesMasjaki\models\RefNegara;
use app\modules\referencesMasjaki\models\RefKabkota;
use app\modules\businessEntity\models\BusinessEntity;
use app\modules\referencesMasjaki\models\RefProvinsi;
use app\modules\referencesMasjaki\models\RefKecamatan;
use app\modules\referencesMasjaki\models\RefKelurahan;
use app\modules\referencesMasjaki\models\TypeOfManagement;

/**
 * This is the model class for table "administrator".
 *
 * @property int $id id
 * @property string $name nama
 * @property int $nik_passport_number nik nomor paspor
 * @property int $npwp npwp
 * @property int $employment_bpjs_number nomor bpjs ketenagakerjaan
 * @property int $number_bpjs_health nomor bpjs kesehatan
 * @property int $status status
 * @property string $position jabatan
 * @property string $served_since menjabat sejak
 * @property string $served_arrived menjabat sampai
 * @property int $type_of_management jenis kepengurusan
 * @property string $citizenship kewarganegaraan
 * @property string $address alamat
 * @property string $province provinsi
 * @property string $regency kabupaten
 * @property string $subdistrict kecamatan
 * @property string $ward kelurahan
 * @property string $country negara
 * @property string $id_country negara
 * @property string $id_province provinsi
 * @property string $id_regency kabkota
 * @property string $id_subdistrict kecamatan
 * @property string $id_ward kelurahan
 * @property string|null $created_at dibuat di
 * @property string|null $updated_at diedit di
 * @property string|null $deleted_at dihapus di
 * @property string|null $created_by dibuat oleh
 * @property string|null $updated_by diedit oleh
 * @property string|null $deleted_by dihapus oleh
 */
class Administrator extends \yii\db\ActiveRecord
{
    const NON_ACTIVE = 0;
    const ACTIVE = 1;

    const WNI = 0;
    const WNA = 1;

    public $sess;

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
        return 'administrator';
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
            [['name', 'nik_passport_number', 'id_business_entity', 'npwp', 'employment_bpjs_number', 'number_bpjs_health', 'status', 'position', 'served_since', 'served_arrived', 'type_of_management', 'citizenship', 'address', 'id_country', 'id_province', 'id_regency', 'id_subdistrict', 'id_ward'], 'required'],
            [['id_business_entity', 'status', 'type_of_management'], 'integer'],
            [['served_since', 'served_arrived', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name', 'position', 'citizenship', 'address', 'province', 'regency', 'subdistrict', 'ward', 'created_by', 'updated_by', 'deleted_by', 'id_province', 'id_regency', 'id_subdistrict', 'id_ward', 'country', 'id_country', 'nik_passport_number', 'npwp', 'employment_bpjs_number', 'number_bpjs_health',], 'string', 'max' => 255],
            // [['id_business_entity'], 'exist', 'skipOnError' => true, 'targetClass' => BusinessEntity::class, 'targetAttribute' => ['id_business_entity' => 'id']],

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
            'employment_bpjs_number' => Yii::t('app', 'Employment BPJS Number'),
            'number_bpjs_health' => Yii::t('app', 'Health BPJS Number'),
            'status' => Yii::t('app', 'Status'),
            'position' => Yii::t('app', 'Position'),
            'served_since' => Yii::t('app', 'Served Since'),
            'served_arrived' => Yii::t('app', 'Served Arrived'),
            'type_of_management' => Yii::t('app', 'Type of Management'),
            'citizenship' => Yii::t('app', 'Citizenship'),
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
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
        ];
    }
    public function getWniWna()
    {
        return $this->citizenship == 1 ? 'WNI' : 'WNA';
    }

    public function getStatusActive()
    {
        return $this->status == 1 ? 'Aktif' : 'Tidak Aktif';
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
     * Gets query for [[Citizenship]].
     *
     * @return \yii\db\ActiveQuery
     */
    // public function getCitizenshipData()
    // {
    //     return $this->hasOne(RefNegara::class, ['id' => 'citizenship']);
    // }


    /**
     * Gets query for [[TypeOfManagement]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTypeOfManagement()
    {
        return $this->hasOne(TypeOfManagement::class, ['id' => 'type_of_management']);
    }

    /**
     * Gets query for [[BusinessEntity]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessEntity()
    {
        return $this->hasOne(BusinessEntity::class, ['id' => 'id_business_entity']);
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
        }
        // tulis sini saat update/insert
        $this->served_since = $this->served_since ? Yii::$app->formatter->asDate($this->served_since, 'yyyy-MM-dd') : null;
        $this->served_arrived = $this->served_arrived ? Yii::$app->formatter->asDate($this->served_arrived, 'yyyy-MM-dd') : null;

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
        $this->served_since = $this->served_since != '0000-00-00' ? Yii::$app->formatter->asDate($this->served_since, 'dd-MM-yyyy') : null;
        $this->served_arrived = $this->served_arrived != '0000-00-00' ? Yii::$app->formatter->asDate($this->served_arrived, 'dd-MM-yyyy') : null;

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
