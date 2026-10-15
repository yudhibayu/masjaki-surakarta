<?php

namespace app\modules\businessEntity\models;

use Yii;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\i18n\Formatter;
use yii\db\ActiveRecord;
use app\overrides\user\models\User;

/**
 * This is the model class for table "deed_of_incorporation".
 *
 * @property int $id id
 * @property int $deed_number nomor akta
 * @property string $date_of_deed tanggal akta
 * @property string $notary_public notaris
 * @property string $deed_file file akta
 * @property string $file_name Nama Foto
 * @property string $file_path Direktori Foto
 * @property string $file_extension Format Foto
 * @property string $file_size Ukuran Foto
 * @property string|null $created_at dibuat di
 * @property string|null $updated_at diedit di
 * @property string|null $deleted_at dihapus di
 * @property string|null $created_by dibuat oleh
 * @property string|null $updated_by diedit oleh
 * @property string|null $deleted_by dihapus oleh
 */
class DeedOfIncorporation extends \yii\db\ActiveRecord
{
    public $sess;
    public $file_data;

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
        return 'deed_of_incorporation';
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
            [['deed_number', 'date_of_deed', 'notary_public'], 'required'],
            [['id_business_entity', 'file_size'], 'integer'],
            [['date_of_deed', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['notary_public', 'deed_number', 'deed_file', 'created_by', 'updated_by', 'deleted_by', 'file_name', 'file_extension', 'file_path'], 'string', 'max' => 255],
            [['file_data'], 'file', 'mimeTypes' => ['application/pdf']],

            // [['deed_file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf', 'checkExtensionByMimeType' => true, 'maxSize' => 2 * 1024 * 1024, 'mimeTypes' => 'application/pdf'],
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
            'deed_number' => Yii::t('app', 'Deed Number'),
            'date_of_deed' => Yii::t('app', 'Date of Deed'),
            'notary_public' => Yii::t('app', 'Notary Public'),
            'deed_file' => Yii::t('app', 'Deed File'),
            'file_extension' => Yii::t('app', 'File Extension'),
            'file_size' => Yii::t('app', 'File Size'),
            'file_path' => Yii::t('app', 'File Path'),
            'file_name' => Yii::t('app', 'File Name'),
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
        $this->date_of_deed = $this->date_of_deed ? Yii::$app->formatter->asDate($this->date_of_deed, 'yyyy-MM-dd') : null;

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
        $this->date_of_deed = $this->date_of_deed != '0000-00-00' ? Yii::$app->formatter->asDate($this->date_of_deed, 'dd-MM-yyyy') : null;

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
