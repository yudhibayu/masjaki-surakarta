<?php

namespace app\modules\technicalspec\models;

use app\modules\monitoring\models\MonitoringTask;
use app\modules\references\models\RefSatuan;
use Yii;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\i18n\Formatter;
use yii\db\ActiveRecord;
use app\overrides\user\models\User;

/**
 * This is the model class for table "ded_data".
 *
 * @property int $id DED ID
 * @property string $name DED Name
 * @property int $monitoring_task_id Monitoring Task ID
 * @property string|null $monitoring_task_name Monitoring Task Name
 * @property int $ref_component_category_id Component Category ID
 * @property string|null $ref_component_category_name Ref Component Category Name
 * @property int $ref_component_id Component ID
 * @property string|null $ref_component_name Ref Component Name
 * @property int $ref_satuan_id Satuan ID
 * @property string|null $ref_satuan_name Ref Satuan Name
 * @property int $mco_cco_id ID MCO / CCO
 * @property string|null $mco_cco_name Name MCO / CCO
 * @property int $cco CCO
 * @property string|null $specification Specification
 * @property string|null $quantity Quantity
 * @property string|null $notes Notes
 * @property string|null $created_at Created At
 * @property int|null $created_by Created By
 * @property string|null $updated_at Updated At
 * @property int|null $updated_by Updated By
 * 
 * @property McoCco $mcoCco MCO/CCO Paket
 * @property DedFiles[] $files File DED
 */
class DedData extends \yii\db\ActiveRecord
{
    public $sess;
    public $file_uploads;

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
        return 'ded_data';
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
            [['name', 'monitoring_task_id', 'ref_component_category_id', 'ref_component_id', 'mco_cco_id', 'ref_satuan_id'], 'required'],
            [['monitoring_task_id', 'ref_component_category_id', 'ref_component_id', 'mco_cco_id', 'ref_satuan_id', 'cco', 'created_by', 'updated_by'], 'integer'],
            [['monitoring_task_name', 'specification', 'notes'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'ref_component_category_name', 'ref_component_name', 'ref_satuan_name', 'quantity', 'mco_cco_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'DED ID'),
            'name' => Yii::t('technicalspec', 'DED Name'),
            'monitoring_task_id' => Yii::t('monitoring', 'Monitoring Task ID'),
            'monitoring_task_name' => Yii::t('monitoring', 'Monitoring Task Name'),
            'ref_component_category_id' => Yii::t('technicalspec', 'Component Category ID'),
            'ref_component_category_name' => Yii::t('technicalspec', 'Ref Component Category Name'),
            'ref_component_id' => Yii::t('technicalspec', 'Component ID'),
            'ref_component_name' => Yii::t('technicalspec', 'Ref Component Name'),
            'mco_cco_id' => Yii::t('technicalspec', 'MCO/CCO ID'),
            'mco_cco_name' => Yii::t('technicalspec', 'MCO/CCO'),
            'ref_satuan_id' => Yii::t('app', 'Satuan'),
            'ref_satuan_name' => Yii::t('app', 'Satuan'),
            'cco' => Yii::t('technicalspec', 'CCO'),
            'specification' => Yii::t('technicalspec', 'Specification'),
            'quantity' => Yii::t('technicalspec', 'Quantity'),
            'notes' => Yii::t('technicalspec', 'Notes'),
            'file_uploads' => Yii::t('app', 'File Uploads'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
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
     * Get Monitoring Tasks
     * 
     * @return \yii\db\ActiveQuery|MonitoringTaskQuery
     */
    public function getMonitoringTask()
    {
        return $this->hasOne(MonitoringTask::class, ['id' => 'monitoring_task_id']);
    }

    /**
     * Get ref_component_id relation from [[RefComponent]] table.
     *
     * @return \yii\db\ActiveQuery|RefComponentQuery
     */
    public function getRefComponent()
    {
        return $this->hasOne(RefComponent::class, ['id' => 'ref_component_id']);
    }

    /**
     * Get ref_component_category_id relation from [[RefComponentCategory]] table.
     *
     * @return \yii\db\ActiveQuery|RefComponentCategoryQuery
     */
    public function getRefComponentCategory()
    {
        return $this->hasOne(RefComponentCategory::class, ['id' => 'ref_component_category_id'])
            ->via('refComponent');
    }

    /**
     * Get mco_cco_id relation from [[McoCco]] table.
     *
     * @return \yii\db\ActiveQuery|McoCcoQuery
     */
    public function getMcoCco()
    {
        return $this->hasOne(McoCco::class, ['id' => 'mco_cco_id']);
    }

    public function getFiles()
    {
        return $this->hasMany(DedFiles::class, ['ded_data_id' => 'id']);
    }

    public function getRefSatuan()
    {
        return $this->hasOne(RefSatuan::class, ['id' => 'ref_satuan_id']);
    }
    //Daerah Akhir Relation



    /**
     * {@inheritdoc}
     * @return DedDataQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DedDataQuery(get_called_class());
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

    public function getKuantitasSatuan()
    {
        return $this->quantity . ' ' . $this->ref_satuan_name;
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
        $this->monitoring_task_name = $this->monitoringTask->name;
        $this->ref_component_category_name = $this->refComponentCategory->name;
        $this->ref_component_name = $this->refComponent->name;
        $this->ref_satuan_name = isset($this->refSatuan->name) ? $this->refSatuan->name : null;
        $this->cco = $this->mcoCco->cco;

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
        $specs = MonitoringSpec::find()->where(['ded_data_id' => $this->id])->one();
        if ($specs) {
            $this->addError('name', 'Please remove all monitoring specs before delete');
            return false;
        }

        parent::beforeDelete();
        return true;
    }
    //Daerah Akhir Event Driven

}
