<?php

namespace app\modules\construction\models;

use Yii;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\i18n\Formatter;
use yii\db\ActiveRecord;
use app\overrides\user\models\User;

/**
 * This is the model class for table "registrant_vacancies".
 *
 * @property int $id ID
 * @property int|null $id_vacancies ID Lowongan
 * @property string|null $name_vacancies Nama Lowongan
 * @property int|null $id_labor ID Tenaga Kerja
 * @property string|null $name_labor Nama Tenaga Kerja
 * @property string|null $created_at Dibuat di
 * @property string|null $updated_at Diedit di
 * @property string|null $created_by Dibuat oleh
 * @property string|null $updated_by Diedit oleh
 * @property int|null $status status
 * @property string|null $nik NIK
 * @property string|null $subdistrict Kecamatan
 * @property string|null $ward Kelurahan
 *
 * @property ConstructionVacancies $vacancies
 * @property Labor $labor
 */
class RegistrantVacancies extends \yii\db\ActiveRecord
{
    const SELECTED = 1;
    const UNSELECTED = 0;
    const NOTSELECTED = 2;

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
        return 'registrant_vacancies';
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
            [['id_vacancies', 'id_labor', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name_vacancies', 'name_labor', 'nik', 'subdistrict', 'ward'], 'string', 'max' => 100],
            [['created_by', 'updated_by'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_vacancies' => Yii::t('app', 'ID Vacancies'),
            'name_vacancies' => Yii::t('app', 'Vacancy Name'),
            'id_labor' => Yii::t('app', 'Labor'),
            'name_labor' => Yii::t('app', 'Labor Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'status' => Yii::t('app', 'Status'),
            'nik' => Yii::t('app', 'NIK'),
            'subdistrict' => Yii::t('app', 'Subdistrict'),
            'ward' => Yii::t('app', 'Ward'),
        ];
    }
    public function getStatusSelected()
    {
        switch ($this->status) {
            case $this::UNSELECTED:
                return 'Belum Terpilih';
            case $this::SELECTED:
                return 'Terpilih';
            case $this::NOTSELECTED:
                return 'Tidak Terpilih';
            default:
                return 'Status Tidak Diketahui';
        }
    }
    // Daerah Start relation
    /**
     * Gets query for [[Vacancies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVacancies()
    {
        return $this->hasOne(ConstructionVacancies::class, ['id' => 'id_vacancies']);
    }
    /**
     * Gets query for [[Labor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLabor()
    {
        return $this->hasOne(Labor::class, ['id' => 'id_labor']);
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

        if (isset($this->vacancies->name)) {
            $this->name_vacancies = $this->vacancies->name;
        }
        if (isset($this->labor->name)) {
            $this->name_labor = $this->labor->name;
        }
        if (isset($this->labor->nik_passport_number)) {
            $this->nik = $this->labor->nik_passport_number;
        }
        if (isset($this->labor->subdistrict)) {
            $this->subdistrict = $this->labor->subdistrict;
        }
        if (isset($this->labor->ward)) {
            $this->ward = $this->labor->ward;
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
