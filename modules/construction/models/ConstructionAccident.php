<?php

namespace app\modules\construction\models;

use app\modules\rup\models\Pbj;
use Yii;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\i18n\Formatter;
use yii\db\ActiveRecord;
use app\overrides\user\models\User;

/**
 * This is the model class for table "construction_accident".
 *
 * @property int $id id
 * @property int $id_construction_work
 * @property string $date hari tanggal
 * @property string $place tempat
 * @property string $photo_before_repair foto sebelum perbaikan
 * @property string|null $photo_after_repair foto sesudah perbaikan
 * @property string $file_name_before Nama Foto
 * @property string $file_path_before Direktori Foto
 * @property string $file_extension_before Format Foto
 * @property string $file_size_before Ukuran Foto
 * @property string $file_name_after Nama Foto
 * @property string $file_path_after Direktori Foto
 * @property string $file_extension_after Format Foto
 * @property string $file_size_after Ukuran Foto
 * @property string|null $created_at dibuat di
 * @property string|null $updated_at diedit di
 * @property string|null $deleted_at dihapus di
 * @property string|null $created_by dibuat oleh
 * @property string|null $updated_by diedit oleh
 * @property string|null $deleted_by dihapus oleh
 * @property string $job_executor Pelaksana Pekerjaan
 * @property string $accident_category Kategori Kecelakaan
 * @property string $description Keterangan
 * @property int $amount_victims_minor_injuries Jumlah Korban Luka Ringan
 * @property int $amount_victims_moderate_injuries Jumlah Korban Luka Sedang
 * @property int $amount_victims_serious_injuries Jumlah Korban Luka Berat
 * @property int $amount_victims_die Jumlah Korban Meninggal Dunia
 *
 * @property ConstructionWork $constructionWork
 */
class ConstructionAccident extends \yii\db\ActiveRecord
{
    public $sess;
    public $file_data_before;
    public $file_data_after;

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
        return 'construction_accident';
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
            [
                ['date', 'accident_category', 'description'],
                'required',
                'when' => function ($model) {
                    return (($model->accident == 1));
                },
                'whenClient' => "function (attribute, value) {
                                    return (($('#constructionaccident-accident').val() == 1));
                                }"
            ],
            [
                ['no_bpjs', 'total_pekerja', 'iuran_pekerja'],
                'required',
                'when' => function ($model) {
                    return (($model->bpjs == 1));
                },
                'whenClient' => "function (attribute, value) {
                                    return (($('#constructionaccident-bpjs').val() == 1));
                                }"
            ],
            // [['date', 'place'], 'required'],
            [['id_construction_work', 'file_size_before', 'file_size_after', 'amount_victims_minor_injuries', 'amount_victims_moderate_injuries', 'amount_victims_serious_injuries', 'amount_victims_die', 'bpjs', 'total_pekerja', 'accident'], 'integer'],
            [['date', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['iuran_pekerja'], 'number'],
            [['place', 'photo_before_repair', 'photo_after_repair', 'created_by', 'updated_by', 'deleted_by', 'file_name_before', 'file_extension_before', 'file_path_before', 'file_name_after', 'file_extension_after', 'file_path_after', 'job_executor', 'accident_category', 'description', 'no_bpjs'], 'string', 'max' => 255],
            [['file_data_before'], 'file', 'mimeTypes' => ['image/*', 'application/pdf']],
            [['file_data_after'], 'file', 'mimeTypes' => ['image/*', 'application/pdf']],
            // [['photo_before_repair'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf', 'checkExtensionByMimeType' => true, 'maxSize' => 2 * 1024 * 1024, 'mimeTypes' => 'application/pdf'],
            // [['photo_after_repair'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf', 'checkExtensionByMimeType' => true, 'maxSize' => 2 * 1024 * 1024, 'mimeTypes' => 'application/pdf'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_construction_work' => Yii::t('app', 'ID Proyek Konstruksi'),
            'accident' => Yii::t('app', 'Accident'),
            'date' => Yii::t('app', 'Date Occur'),
            'place' => Yii::t('app', 'Place'),
            'job_executor' => Yii::t('app', 'Job Executor'),
            'accident_category' => Yii::t('app', 'Accident Category'),
            'description' => Yii::t('app', 'Description'),
            'amount_victims_minor_injuries' => Yii::t('app', 'Amount Victims Minor Injuries'),
            'amount_victims_moderate_injuries' => Yii::t('app', 'Amount Victims Moderate Injuries'),
            'amount_victims_serious_injuries' => Yii::t('app', 'Amount Victims Serious Injuries'),
            'amount_victims_die' => Yii::t('app', 'Amount Victims Die'),
            'photo_before_repair' => Yii::t('app', 'Photo Before Repair'),
            'file_extension_before' => Yii::t('app', 'File Extension Before'),
            'file_size_before' => Yii::t('app', 'File Size Before'),
            'file_path_before' => Yii::t('app', 'File Path Before'),
            'file_name_before' => Yii::t('app', 'File Name Before'),
            'photo_after_repair' => Yii::t('app', 'Photo After Repair'),
            'file_extension_after' => Yii::t('app', 'File Extension After'),
            'file_size_after' => Yii::t('app', 'File Size After'),
            'file_path_after' => Yii::t('app', 'File Path After'),
            'file_name_after' => Yii::t('app', 'File Name After'),
            'bpjs' => Yii::t('app', 'Use BPJS'),
            'no_bpjs' => Yii::t('app', 'BPJS Number'),
            'total_pekerja' => Yii::t('app', 'Total Worker'),
            'iuran_pekerja' => Yii::t('app', 'Worker Contribution'),
            'pbj_name' => Yii::t('app', 'Construction Work'),
            'file_accident' => Yii::t('app', 'File Upload'),
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
     * Gets query for [[ConstructionWork]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConstructionWork()
    {
        return $this->hasOne(Pbj::class, ['kodelelang' => 'id_construction_work']);
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
        $this->date = $this->date ? Yii::$app->formatter->asDate($this->date, 'yyyy-MM-dd') : null;

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
        if ($this->date != null) {
            $this->date = $this->date != '0000-00-00' ? Yii::$app->formatter->asDate($this->date, 'dd-MM-yyyy') : null;
        }


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
