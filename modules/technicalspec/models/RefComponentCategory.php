<?php

namespace app\modules\technicalspec\models;

use app\modules\references\models\ObjectType;
use Yii;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\i18n\Formatter;
use yii\db\ActiveRecord;
use app\overrides\user\models\User;

/**
 * This is the model class for table "ref_component_category".
 *
 * @property int $id ID Component Category
 * @property string $name Component Category Name
 * @property string|null $code Component Category Code
 * @property int $ref_objecttype_id Object Type ID
 * @property string|null $description Component Category Description
 * @property string|null $created_at Created At
 * @property string|null $updated_at Uploaded At
 * @property int|null $created_by Created By
 * @property int|null $updated_by Updated By
 */
class RefComponentCategory extends \yii\db\ActiveRecord
{
    public $sess;
    public $namecode = '';
    public $ref_objecttype_name;

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
        return 'ref_component_category';
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
            [['name', 'ref_objecttype_id'], 'required'],
            [['description'], 'string'],
            [['created_at', 'updated_at', 'ref_objecttype_name'], 'safe'],
            [['created_by', 'updated_by', 'ref_objecttype_id'], 'integer'],
            [['name', 'code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('technicalspec', 'ID Component Category'),
            'name' => Yii::t('technicalspec', 'Component Category Name'),
            'code' => Yii::t('technicalspec', 'Component Category Code'),
            'ref_objecttype_id' => Yii::t('app', 'Object Type'),
            'ref_objecttype_name' => Yii::t('app', 'Object Type'),
            'description' => Yii::t('technicalspec', 'Component Category Description'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Uploaded At'),
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
     * Get ref_objecttype_id relation from [[ObjectType]] table.
     *
     * @return \yii\db\ActiveQuery|ObjectTypeQuery
     */
    public function getObjectType()
    {
        return $this->hasOne(ObjectType::class, ['id' => 'ref_objecttype_id']);
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



    /**
     * {@inheritdoc}
     * @return RefComponentCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RefComponentCategoryQuery(get_called_class());
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
        if ($this->code) {
            $this->namecode = $this->name . ' (' . $this->code . ')';
        } else {
            $this->namecode = $this->name;
        }
        if ($this->ref_objecttype_id) {
            $this->ref_objecttype_name = $this->objectType->name;
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
