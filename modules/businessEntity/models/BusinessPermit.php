<?php

namespace app\modules\businessEntity\models;

use Yii;

use yii\i18n\Formatter;
use yii\db\ActiveRecord;
use app\overrides\user\models\User;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use app\modules\referencesMasjaki\models\ConstructionSbu;
use app\modules\referencesMasjaki\models\TypeOfBusinessLicense;

/**
 * This is the model class for table "business_permit".
 *
 * @property int $id id
 * @property int $business_permit_type jenis izin usaha
 * @property int $reference_number nomor surat
 * @property string|null $valid_until berlaku sampai
 * @property int|null $business_qualification kualifikasi usaha
 * @property int|null $id_business_qualification kualifikasi usaha
 * @property string|null $grantor_institution instansi pemberi
 * @property string|null $business_permit_file file izin usaha
 * @property string|null $created_at dibuat di
 * @property string|null $updated_at diedit di
 * @property string|null $deleted_at dihapus di
 * @property string|null $created_by dibuat oleh
 * @property string|null $updated_by diedit oleh
 * @property string|null $deleted_by dihapus oleh
 */
class BusinessPermit extends \yii\db\ActiveRecord
{
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
        return 'business_permit';
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
            [['business_permit_type', 'id_business_entity', 'reference_number', 'valid_until',], 'required'],
            [['business_permit_type', 'id_business_entity', 'id_business_qualification'], 'integer'],
            [['valid_until', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['grantor_institution', 'business_permit_file', 'created_by', 'updated_by', 'deleted_by', 'business_qualification', 'reference_number'], 'string', 'max' => 255],
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
            'business_permit_type' => Yii::t('app', 'Business Permit Type'),
            'reference_number' => Yii::t('app', 'Reference Number'),
            'valid_until' => Yii::t('app', 'Valid Until'),
            'business_qualification' => Yii::t('app', 'Business Qualification'),
            'id_business_qualification' => Yii::t('app', 'Id Business Qualification'),
            'grantor_institution' => Yii::t('app', 'Grantor Institution'),
            'business_permit_file' => Yii::t('app', 'Business Permit File'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
        ];
    }
    // Daerah Start relation

    /**
     * Gets query for [[ConstructionSbu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConstructionSbu()
    {
        return $this->hasOne(ConstructionSbu::class, ['id' => 'id_business_qualification']);
    }

    /**
     * Gets query for [[TypeOfBusinessLicense]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTypeOfBusinessLicense()
    {
        return $this->hasOne(TypeOfBusinessLicense::class, ['id' => 'business_permit_type']);
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
        $this->valid_until = $this->valid_until ? Yii::$app->formatter->asDate($this->valid_until, 'yyyy-MM-dd') : null;

        if (isset($this->constructionSbu->name)) {
            $this->business_qualification = $this->constructionSbu->name;
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
        $this->valid_until = $this->valid_until != '0000-00-00' ? Yii::$app->formatter->asDate($this->valid_until, 'dd-MM-yyyy') : null;

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
