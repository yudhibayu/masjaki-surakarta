<?php

namespace app\modules\manage_sot\models;

use app\overrides\user\models\User;
use yii\i18n\Formatter;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
// use dektrium\user\models\User;
use Yii;

/**
 * This is the model class for table "manage_sot".
 *
 * @property int $id ID
 * @property string $name SOT Year
 * @property string $created_at Created At
 * @property string $updated_at Updated At
 * @property int $created_by Created By
 * @property int $updated_by Updated By
 */
class RefSot extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ref_sot';
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
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => date('Y-m-d H:i:s'),
            ],
            [
                'class' => BlameableBehavior::className(),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['note', 'active'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('manage_sot', 'SOTK Regulation'),
            'note' => Yii::t('manage_sot', 'Note'),
            'active' => Yii::t('manage_sot', 'Active'),
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
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * Get updated_by relation from [[User]] table.
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

//Daerah Akhir Relation



    /**
     * {@inheritdoc}
     * @return RefSotQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RefSotQuery(get_called_class());
    }

//Daerah Start Virtual Attribute
     /**
     * Virtual Attribute for Created By Name
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getCreatedByName()
    {
        $data = null;
        if ($this->created_by) {
            if (isset($this->createdBy->profileName)) {
                $data = $this->createdBy->profileName;
                return $data;
            } 
            if (isset($this->createdBy->username)) {
                $data = $this->createdBy->username;
                return $data;
            }
        }
        return $data;
    }

     /**
     * Virtual Attribute for Updated By Name
     *
     * @return \yii\db\ActiveQuery|UserTypeQuery
     */
    public function getUpdatedByName()
    {
        $data = null;
        if ($this->updated_by) {
            if (isset($this->updatedBy->profileName)) {
                $data = $this->updatedBy->profileName;
                return $data;
            } 
            if (isset($this->updatedBy->username)) {
                $data = $this->updatedBy->username;
                return $data;
            }
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
