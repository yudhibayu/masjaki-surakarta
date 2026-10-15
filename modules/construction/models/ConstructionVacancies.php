<?php

namespace app\modules\construction\models;

use Yii;
use yii\i18n\Formatter;

use yii\db\ActiveRecord;
use app\modules\rup\models\Pbj;
use app\overrides\user\models\User;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use app\modules\references\models\Year_model;
use app\modules\referencesMasjaki\models\Skills;
use app\modules\referencesMasjaki\models\Education;

/**
 * This is the model class for table "construction_vacancies".
 *
 * @property int $id ID
 * @property string $name Nama
 * @property string $description Deskripsi
 * @property int $construction_work Proyek Konstruksi
 * @property int $skills Keterampilan
 * @property int $education Pendidikan
 * @property int $age_range Rentang Usia
 * @property int $age_range_upto Rentang Usia s.d
 * @property int $work_experience Pengalaman Kerja
 * @property int $quota Kuota
 * @property int $salary_range Kisaran Gaji
 * @property int $salary_range_upto Kisaran Gaji s.d
 * @property string $start_date Tanggal Mulai
 * @property string $date_of_completion Tanggal Selesai
 * @property string $start_time Waktu Mulai
 * @property string $end_time Waktu Selesai
 * @property string $type_of_work Tipe Pekerjaan 
 * @property string $publication Publikasi
 * @property string $id_year ID Tahun
 * @property string $year_name Nama Tahun
 * @property string|null $created_at Dibuat di
 * @property string|null $updated_at Diedit di
 * @property string|null $deleted_at Dihapus di
 * @property int|null $created_by Dibuat oleh
 * @property int|null $updated_by Diedit oleh
 * @property int|null $deleted_by Dihapus oleh
 *
 * @property Education $education
 * @property Skills $skills
 * @property ConstructionWork $constructionWork
 */
class ConstructionVacancies extends \yii\db\ActiveRecord
{
    const PART_TIME_TYPE_WORK = 0;
    const FULL_TIME_TYPE_WORK = 1;

    const PUBLICATED = 1;
    const UNPUBLICATED = 0;
    const CLOSED = 2;

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
        return 'construction_vacancies';
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
            [['name', 'construction_work', 'skills', 'education', 'age_range', 'age_range_upto', 'work_experience', 'quota', 'salary_range', 'salary_range_upto', 'start_date', 'start_time', 'end_time', 'date_of_completion', 'type_of_work'], 'required'],
            [['construction_work', 'skills', 'education', 'age_range', 'age_range_upto', 'quota',  'created_by', 'updated_by', 'deleted_by', 'id_year', 'year_name', 'work_experience', 'publication'], 'integer'],
            [['salary_range', 'salary_range_upto', 'id_business_entity'], 'number'],
            [['start_date', 'start_time', 'end_time', 'date_of_completion', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['type_of_work'], 'string'],
            [['name', 'description', 'nama_business_entity',], 'string', 'max' => 255],
            ['age_range_upto', 'validateAgeRange'],
            ['salary_range_upto', 'validateSalary'],
            // ['date_of_completion', 'compare', 'compareAttribute' => 'start_date', 'operator' => '>=', 'type' => 'date', 'message' => 'Tanggal penyelesaian tidak boleh lebih awal'],
            ['end_time', 'compare', 'compareAttribute' => 'start_time', 'operator' => '>=', 'type' => 'date', 'message' => 'Waktu penyelesaian tidak boleh lebih awal'],
            ['date_of_completion', 'validateDateRange'],


        ];
    }

    public function validateDateRange($attribute, $params)
    {
        $start_date = strtotime($this->start_date);
        $date_of_completion = strtotime($this->date_of_completion);

        if ($date_of_completion < $start_date) {
            $this->addError($attribute, 'Tanggal akhir tidak boleh lebih kecil dari tanggal awal.');
        }
    }

    public function validateAgeRange($attribute, $params)
    {
        if ($this->age_range_upto < $this->age_range) {
            $this->addError($attribute, 'Rentang umur sampai dengan tidak boleh lebih kecil dari rentang umur awal');
        }
    }
    public function validateSalary($attribute, $params)
    {
        if ($this->salary_range_upto < $this->salary_range) {
            $this->addError($attribute, 'Rentang gaji sampai dengan tidak boleh lebih kecil dari rentang gaji awal');
        }
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'construction_work' => Yii::t('app', 'Construction Work'),
            'skills' => Yii::t('app', 'Skills'),
            'education' => Yii::t('app', 'Education'),
            'age_range' => Yii::t('app', 'Age Range'),
            'age_range_upto' => Yii::t('app', 'Age Range Upto'),
            'work_experience' => Yii::t('app', 'Work Experience'),
            'quota' => Yii::t('app', 'Quota'),
            'salary_range' => Yii::t('app', 'Salary Range'),
            'salary_range_upto' => Yii::t('app', 'Salary Range Upto'),
            'start_date' => Yii::t('app', 'Start Date'),
            'start_time' => Yii::t('app', 'Start Time'),
            'end_time' => Yii::t('app', 'End Time'),
            'date_of_completion' => Yii::t('app', 'Date of Completion'),
            'type_of_work' => Yii::t('app', 'Type of Work'),
            'publication' => Yii::t('app', 'Publication'),
            'id_year' => Yii::t('app', 'ID Year'),
            'year_name' => Yii::t('app', 'Year Name'),
            'nama_business_entity' => Yii::t('app', 'Business Entity'),
            'id_business_entity' => Yii::t('app', 'Business Entity'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
        ];
    }

    public function getStatusPublication()
    {
        switch ($this->publication) {
            case $this::UNPUBLICATED:
                return 'Belum Terpublikasi';
            case $this::PUBLICATED:
                return 'Sudah Terpublikasi';
            case $this::CLOSED:
                return 'Ditutup';
            default:
                return 'Status Tidak Diketahui';
        }
    }

    public function getWorkType()
    {
        return $this->type_of_work == $this::FULL_TIME_TYPE_WORK ? 'Full Time' : 'Part Time';
    }
    // Daerah Start relation


    /**
     * Gets query for [RefYear]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefYear()
    {
        return $this->hasOne(Year_model::class, ['id' => 'id_year']);
    }

    /**
     * Gets query for [[Education]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEducationRelation()
    {
        return $this->hasOne(Education::class, ['id' => 'education']);
    }
    /**
     * Gets query for [[Skills]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSkillsRelation()
    {
        return $this->hasOne(Skills::class, ['id' => 'skills']);
    }
    /**
     * Gets query for [[ConstructionWork]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConstructionWork()
    {
        return $this->hasOne(Pbj::class, ['kodelelang' => 'construction_work']);
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
        $this->date_of_completion = $this->date_of_completion ? Yii::$app->formatter->asDate($this->date_of_completion, 'yyyy-MM-dd') : null;
        $this->start_date = $this->start_date ? Yii::$app->formatter->asDate($this->start_date, 'yyyy-MM-dd') : null;
        $this->id_year = $this->sess['milestone']['year_id'];
        $this->year_name = $this->sess['milestone']['year_name'];
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
        $this->date_of_completion = $this->date_of_completion != '0000-00-00' ? Yii::$app->formatter->asDate($this->date_of_completion, 'dd-MM-yyyy') : null;
        $this->start_date = $this->start_date != '0000-00-00' ? Yii::$app->formatter->asDate($this->start_date, 'dd-MM-yyyy') : null;

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