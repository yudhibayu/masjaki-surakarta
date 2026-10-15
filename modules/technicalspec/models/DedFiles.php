<?php

namespace app\modules\technicalspec\models;

use Yii;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\i18n\Formatter;
use yii\db\ActiveRecord;
use app\overrides\user\models\User;

/**
 * This is the model class for table "ded_files".
 *
 * @property int $id DED File ID
 * @property string|null $name DED File Name
 * @property int $ded_data_id DED ID
 * @property int $ded_data_name DED Name
 * @property string|null $description Description
 * @property string $file_name File Name
 * @property string|null $file_path File Path
 * @property string|null $file_extension File Extension
 * @property int|null $file_size File Size
 * @property string|null $file_upload File Uploaded
 * @property string|null $created_at Created At
 * @property string|null $updated_at Updated At
 * @property int|null $created_by Created By
 * @property int|null $updated_by Updated By
 */
class DedFiles extends \yii\db\ActiveRecord
{
    public $sess;
    public $file_upload = null;

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
        return 'ded_files';
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
            [['description'], 'trim'],
            [['ded_data_id', 'file_name'], 'required'],
            [['ded_data_id', 'file_size', 'created_by', 'updated_by'], 'integer'],
            [['description', 'file_path'], 'string'],
            [['created_at', 'updated_at', 'file_upload'], 'safe'],
            [['name', 'ded_data_name', 'file_name'], 'string', 'max' => 255],
            [['file_extension'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'DED File ID'),
            'name' => Yii::t('technicalspec', 'DED File Name'),
            'ded_data_id' => Yii::t('technicalspec', 'DED ID'),
            'ded_data_name' => Yii::t('technicalspec', 'DED Name'),
            'description' => Yii::t('technicalspec', 'Description'),
            'file_name' => Yii::t('technicalspec', 'File Name'),
            'file_path' => Yii::t('technicalspec', 'File Path'),
            'file_extension' => Yii::t('technicalspec', 'File Extension'),
            'file_size' => Yii::t('technicalspec', 'File Size'),
            'file_upload' => Yii::t('technicalspec', 'File Upload'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
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

    /**
     * Get ded_data_id relation from [[DedData]] table.
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getDedData()
    {
        return $this->hasOne(DedData::class, ['id' => 'ded_data_id']);
    }
    //Daerah Akhir Relation



    /**
     * {@inheritdoc}
     * @return DedFilesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DedFilesQuery(get_called_class());
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
        $this->ded_data_name = $this->dedData->name;

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
