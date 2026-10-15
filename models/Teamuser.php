<?php

namespace app\models;

use Yii;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\i18n\Formatter;
use yii\db\ActiveRecord;
use app\overrides\user\models\User;

/**
 * This is the model class for table "teamuser".
 *
 * @property int $team_id
 * @property int $user_id
 * @property int $fiscal_year
 */
class Teamuser extends \yii\db\ActiveRecord
{
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
        return 'teamuser';
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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['team_id', 'user_id', 'fiscal_year'], 'required'],
            [['team_id', 'user_id', 'fiscal_year'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'team_id' => Yii::t('app', 'Team'),
            'user_id' => Yii::t('app', 'User'),
            'fiscal_year' => Yii::t('app', 'Fiscal Year'),
        ];
    }
// Daerah Start relation

//Daerah Akhir Relation



    /**
     * {@inheritdoc}
     * @return TeamuserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TeamuserQuery(get_called_class());
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
        // $this->team_name = $this->team->name;
        // $this->user_name = $this->user->name;
 
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
