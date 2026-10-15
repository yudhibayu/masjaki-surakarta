<?php

namespace app\modules\rup\models;

use Yii;
use app\models\Satker;
use app\modules\construction\models\PbjBusinessEntity;
use yii\i18n\Formatter;
use yii\db\ActiveRecord;
use app\overrides\user\models\User;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use app\modules\referencesMasjaki\models\SourceOfFunds;

/**
 * This is the model class for table "pbj".
 *
 * @property int $kodelelang PBJ Code
 * @property int $pbj_type Tipe PBJ
 * @property string $name PBJ Name
 * @property int $rup_code RUP Code
 * @property float|null $budget Budget Price
 * @property float|null $hps Budget Own Estimate Price
 * @property float|null $bid_price Bid Price / Penawaran
 * @property float|null $final_price Final Price / Negosiasi
 * @property string|null $vendor_name Vendor
 * @property string|null $step Tahapan
 * @property int|null $source_of_funds Sumber Dana
 * @property string|null $sppbj_no SPPBJ Number
 * @property string|null $contract_no Contract Number
 * @property string|null $contract_date Contract Date
 * @property string|null $contract_duration Contract Duration
 * @property float|null $contract_price Contract Price
 * @property string|null $contract_start_date Contract Start Date
 * @property string|null $contract_end_date Contract End Date
 * @property string|null $work_start_date Work Start Date
 * @property string|null $work_finished_date Work Finished Date
 * @property string|null $program Program
 * @property string|null $activity Activity
 * @property string|null $sub_activity Sub Activity
 * @property string|null $namasatker Namasatker
 * @property string|null $kodesatker Kodesatker
 * @property int|null $idmetodepengadaan Metode Pengadaan
 * @property string|null $metodepengadaan Metode Pengadaan
 * @property int|null $idjenispengadaan Jenis Pengadaan
 * @property string|null $jenispengadaan Jenis Pengadaan
 * @property int|null $ppk_id PPK
 * @property string|null $ppk_name PPK
 * @property string|null $ppk_nip NIP PPK
 * @property int|null $fiscal_year
 * @property string|null $created_at Created At
 * @property string|null $updated_at Update At
 * @property int|null $created_by Created By
 * @property int|null $updated_by Updated By
 */
class Pbj extends \yii\db\ActiveRecord
{
    const TIPE_PBJ_TENDER = 0;
    const TIPE_PBJ_NONTENDER = 1;

    public $sess;
    public $is_sync = false;
    public $namecode;

    /**
     * Init Object Controller
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        if (isset(Yii::$app->session)) {
            $this->sess = Yii::$app->session;
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pbj';
    }

    /**
     * {@inheritdoc}
     */
    public static function primaryKey()
    {
        return ['kodelelang'];
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
            [['name'], 'trim'],
            [['kodelelang', 'name', 'budget', 'kodesatker'], 'required'],
            [['kodelelang', 'pbj_type', 'rup_code', 'idmetodepengadaan', 'idjenispengadaan', 'ppk_id', 'fiscal_year', 'created_by', 'updated_by', 'source_of_funds'], 'integer'],
            [['name', 'vendor_name', 'namasatker', 'step'], 'string'],
            [['budget', 'hps', 'contract_price', 'bid_price', 'final_price'], 'number'],
            [['contract_date', 'contract_start_date', 'contract_end_date', 'work_start_date', 'work_finished_date', 'created_at', 'updated_at'], 'safe'],
            [['sppbj_no', 'contract_no', 'contract_duration', 'program', 'activity', 'sub_activity', 'kodesatker', 'metodepengadaan', 'jenispengadaan', 'ppk_name', 'ppk_nip'], 'string', 'max' => 255],
            [['kodelelang', 'rup_code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'kodelelang' => Yii::t('rup', 'PBJ Code'),
            'pbj_type' => Yii::t('rup', 'PBJ Type'),
            'rup_code' => Yii::t('rup', 'RUP Code'),
            'name' => Yii::t('rup', 'PBJ Name'),
            'namecode' => Yii::t('rup', 'PBJ'),
            'budget' => Yii::t('rup', 'Budget Price'),
            'hps' => Yii::t('rup', 'Budget Own Estimate Price'),
            'bid_price' => Yii::t('rup', 'Bid Price'),
            'final_price' => Yii::t('rup', 'Final Price'),
            'vendor_name' => Yii::t('rup', 'Vendor'),
            'step' => Yii::t('rup', 'Step'),
            'source_of_funds' => Yii::t('rup', 'Source of Funds'),
            'sppbj_no' => Yii::t('rup', 'SPPBJ Number'),
            'contract_no' => Yii::t('rup', 'Contract Number'),
            'contract_date' => Yii::t('rup', 'Contract Date'),
            'contract_duration' => Yii::t('rup', 'Contract Duration'),
            'contract_price' => Yii::t('rup', 'Contract Price'),
            'contract_start_date' => Yii::t('rup', 'Contract Start Date'),
            'contract_end_date' => Yii::t('rup', 'Contract End Date'),
            'work_start_date' => Yii::t('rup', 'Work Start Date'),
            'work_finished_date' => Yii::t('rup', 'Work Finished Date'),
            'program' => Yii::t('rup', 'Program'),
            'activity' => Yii::t('rup', 'Activity'),
            'sub_activity' => Yii::t('rup', 'Sub Activity'),
            'namasatker' => Yii::t('rup', 'Namasatker'),
            'kodesatker' => Yii::t('rup', 'Kodesatker'),
            'idmetodepengadaan' => Yii::t('rup', 'Metode Pengadaan'),
            'metodepengadaan' => Yii::t('rup', 'Metode Pengadaan'),
            'idjenispengadaan' => Yii::t('rup', 'Jenis Pengadaan'),
            'jenispengadaan' => Yii::t('rup', 'Jenis Pengadaan'),
            'ppk_id' => Yii::t('rup', 'PPK'),
            'ppk_name' => Yii::t('rup', 'PPK'),
            'ppk_nip' => Yii::t('rup', 'NIP PPK'),
            'fiscal_year' => Yii::t('rup', 'Tahunanggaran'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Update At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }


    public function getPbjType()
    {
        return $this->pbj_type == 1 ? 'Non Tender' : 'Tender';
    }


    // Daerah Start relation
    public function getPbjBusinessEntity()
    {
        return $this->hasOne(PbjBusinessEntity::class, ['kode_pbj' => 'kodelelang']);
    }

    /**
     * Get updated_by relation from [[SourceOfFunds]] table.
     *
     * @return \yii\db\ActiveQuery|SourceOfFundsQuery
     */
    public function getSourceOfFunds()
    {
        return $this->hasOne(SourceOfFunds::class, ['id' => 'source_of_funds']);
    }

    /**
     * Get updated_by relation from [[Satker]] table.
     *
     * @return \yii\db\ActiveQuery|SatkerQuery
     */
    public function getSatker()
    {
        return $this->hasOne(Satker::class, ['code' => 'kodesatker']);
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


    /**
     * Get updated_by relation from [[Rupmetodepengadaan]] table.
     *
     * @return \yii\db\ActiveQuery|RupmetodepengadaanQuery
     */
    public function getRupmetodepengadaan()
    {
        return $this->hasOne(Rupmetodepengadaan::class, ['id' => 'idmetodepengadaan']);
    }

    /**
     * Get updated_by relation from [[Rupjenispengadaan]] table.
     *
     * @return \yii\db\ActiveQuery|RupjenispengadaanQuery
     */
    public function getRupjenispengadaan()
    {
        return $this->hasOne(Rupjenispengadaan::class, ['id' => 'idjenispengadaan']);
    }

    //Daerah Akhir Relation



    /**
     * {@inheritdoc}
     * @return PbjQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PbjQuery(get_called_class());
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
            $data = $this->userMaker->profile->name ? $this->userMaker->profile->name : $this->userMaker->username;
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
            $data = $this->userUpdater->profile->name ? $this->userUpdater->profile->name : $this->userUpdater->username;
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
        // $this->contract_date = $this->contract_date ? Yii::$app->formatter->asDate($this->contract_date, 'yyyy-MM-dd') : null;
        // $this->contract_start_date = $this->contract_start_date ? Yii::$app->formatter->asDate($this->contract_start_date, 'yyyy-MM-dd') : null;
        // $this->contract_end_date = $this->contract_end_date ? Yii::$app->formatter->asDate($this->contract_end_date, 'yyyy-MM-dd') : null;
        // $this->work_start_date = $this->work_start_date ? Yii::$app->formatter->asDate($this->work_start_date, 'yyyy-MM-dd') : null;
        // $this->work_finished_date = $this->work_finished_date ? Yii::$app->formatter->asDate($this->work_finished_date, 'yyyy-MM-dd') : null;
        // $this->ppk_name = $this->ppk->name;

        if (!$this->is_sync) {
            if (isset($this->satker->name)) {
                $this->namasatker = $this->satker->name;
            }
            if (isset($this->rupmetodepengadaan->name)) {
                $this->metodepengadaan = $this->rupmetodepengadaan->name;
            }
            if (isset($this->rupjenispengadaan->name)) {
                $this->jenispengadaan = $this->rupjenispengadaan->name;
            }
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
        // $this->contract_date = (($this->contract_date != '0000-00-00') && ($this->contract_date)) ? Yii::$app->formatter->asDate($this->contract_date, 'dd-MM-yyyy') : null;
        // $this->contract_start_date = (($this->contract_start_date != '0000-00-00') && ($this->contract_start_date)) ? Yii::$app->formatter->asDate($this->contract_start_date, 'dd-MM-yyyy') : null;
        // $this->contract_end_date = (($this->contract_end_date != '0000-00-00') && ($this->contract_end_date)) ? Yii::$app->formatter->asDate($this->contract_end_date, 'dd-MM-yyyy') : null;
        // $this->work_start_date = (($this->work_start_date != '0000-00-00') && ($this->work_start_date)) ? Yii::$app->formatter->asDate($this->work_start_date, 'dd-MM-yyyy') : null;
        // $this->work_finished_date = (($this->work_finished_date != '0000-00-00') && ($this->work_finished_date)) ? Yii::$app->formatter->asDate($this->work_finished_date, 'dd-MM-yyyy') : null;
        $this->namecode = "(" . $this->kodelelang . ") " . $this->name;

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
