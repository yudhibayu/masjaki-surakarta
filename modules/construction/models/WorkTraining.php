<?php

namespace app\modules\construction\models;

use Yii;
use yii\i18n\Formatter;

use yii\db\ActiveRecord;
use app\overrides\user\models\User;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use app\modules\references\models\Year_model;
use app\modules\referencesMasjaki\models\Skills;
use app\modules\referencesMasjaki\models\RefNegara;
use app\modules\referencesMasjaki\models\RefKabkota;
use app\modules\referencesMasjaki\models\RefProvinsi;
use app\modules\referencesMasjaki\models\RefKecamatan;
use app\modules\referencesMasjaki\models\RefKelurahan;

/**
 * This is the model class for table "work_training".
 *
 * @property int $id ID
 * @property string $training_name Nama Pelatihan
 * @property string $kuota Kuota
 * @property string $finish_date Tanggal Selesai
 * @property string $finish_time Waktu Selesai
 * @property string $person_responsible_name Nama Penanggungjawab
 * @property int $skills Jenis Keahlian
 * @property string $implementation_date Tanggal Pelaksanaan
 * @property string $implementation_time Waktu pelaksanaan
 * @property string $description Keterangan
 * @property string|null $id_country negara
 * @property string|null $id_province provinsi
 * @property string|null $id_regency kabupaten
 * @property string|null $id_subdistrict kecamatan
 * @property string|null $id_ward kelurahan
 * @property string|null $country negara
 * @property string|null $province provinsi
 * @property string|null $regency kabupaten
 * @property string $subdistrict Kecamatan
 * @property string $ward Kelurahan 
 * @property string $id_year ID Tahun
 * @property string $year_name Nama Tahun
 * @property string $publication Publikasi 
 * @property string|null $created_at Dibuat di
 * @property string|null $updated_at Diedit di
 * @property string|null $deleted_at Dihapus di
 * @property string|null $created_by Dibuat oleh
 * @property string|null $updated_by Diedit oleh
 * @property string|null $deleted_by Dihapus oleh
 *
 * @property Skills $skills0
 */
class WorkTraining extends \yii\db\ActiveRecord
{
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
        return 'work_training';
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
            [['training_name', 'kuota', 'finish_date', 'finish_time', 'person_responsible_name', 'skills', 'implementation_date', 'implementation_time', 'id_country', 'id_province', 'id_regency', 'id_subdistrict', 'id_ward'], 'required'],
            [['skills', 'id_year', 'year_name', 'publication'], 'integer'],
            [['implementation_date', 'kuota', 'finish_date', 'finish_time', 'implementation_time', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['training_name', 'person_responsible_name', 'description', 'id_country', 'id_province', 'id_regency', 'id_subdistrict', 'id_ward', 'country', 'province', 'regency', 'subdistrict', 'ward', 'created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 255],
            // ['finish_date', 'compare', 'compareAttribute' => 'implementation_date', 'operator' => '>=', 'type' => 'date', 'message' => 'Tanggal penyelesaian tidak boleh lebih awal'],
            ['finish_time', 'compare', 'compareAttribute' => 'implementation_time', 'operator' => '>=', 'type' => 'date', 'message' => 'Waktu penyelesaian tidak boleh lebih awal'],
            ['finish_date', 'validateDateRange'],

        ];
    }

    public function validateDateRange($attribute, $params)
    {
        $implementation_date = strtotime($this->implementation_date);
        $finish_date = strtotime($this->finish_date);

        if ($finish_date < $implementation_date) {
            $this->addError($attribute, 'Tanggal akhir tidak boleh lebih kecil dari tanggal awal.');
        }
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'training_name' => Yii::t('app', 'Training Name'),
            'kuota' => Yii::t('app', 'Quota'),
            'finish_date' => Yii::t('app', 'Finish Date'),
            'finish_time' => Yii::t('app', 'Finish Time'),
            'person_responsible_name' => Yii::t('app', 'Person Responsible Name'),
            'skills' => Yii::t('app', 'Skills'),
            'implementation_date' => Yii::t('app', 'Implementation Date'),
            'implementation_time' => Yii::t('app', 'Implementation Time'),
            'description' => Yii::t('app', 'Description'),
            'id_province' => Yii::t('app', 'Province'),
            'id_country' => Yii::t('app', 'Country'),
            'id_regency' => Yii::t('app', 'Regency'),
            'id_subdistrict' => Yii::t('app', 'Subdistrict'),
            'id_ward' => Yii::t('app', 'Ward'),
            'country' => Yii::t('app', 'Country'),
            'province' => Yii::t('app', 'Province'),
            'regency' => Yii::t('app', 'Regency'),
            'subdistrict' => Yii::t('app', 'Subdistrict'),
            'id_year' => Yii::t('app', 'Year ID'),
            'year_name' => Yii::t('app', 'Year Name'),
            'publication' => Yii::t('app', 'Publication'),
            'ward' => Yii::t('app', 'Ward'),
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
     * Gets query for [[Skills0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSkillsRelation()
    {
        return $this->hasOne(Skills::class, ['id' => 'skills']);
    }

    /**
     * Gets query for [[RefYear]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefYear()
    {
        return $this->hasOne(Year_model::class, ['id' => 'id_year']);
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
        $this->implementation_date = $this->implementation_date ? Yii::$app->formatter->asDate($this->implementation_date, 'yyyy-MM-dd') : null;
        $this->finish_date = $this->finish_date ? Yii::$app->formatter->asDate($this->finish_date, 'yyyy-MM-dd') : null;

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
        $this->implementation_date = $this->implementation_date != '0000-00-00' ? Yii::$app->formatter->asDate($this->implementation_date, 'dd-MM-yyyy') : null;
        $this->finish_date = $this->finish_date != '0000-00-00' ? Yii::$app->formatter->asDate($this->finish_date, 'dd-MM-yyyy') : null;

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