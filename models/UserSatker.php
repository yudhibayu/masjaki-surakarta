<?php

namespace app\models;

use Yii;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\i18n\Formatter;
use yii\db\ActiveRecord;
use app\overrides\user\models\User;

/**
 * This is the model class for table "usersatker".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $satker_id
 * @property int|null $fiscal_year
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 */
class UserSatker extends \yii\db\ActiveRecord
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
        return 'usersatker';
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
            [
                'user_id', 'checkId',
                'when'  => function ($model) {
                    if (Yii::$app->user->can('Administrator') && (isset($this->oldAttributes['user_id']) && $this->oldAttributes['user_id'] == $this->user_id)) {
                        return false;
                    } elseif (Yii::$app->user->can('Administrator')) {
                        return true;
                    }
                }
            ],
            [['user_id', 'satker_id', 'fiscal_year'], 'required'],
            [['user_id', 'satker_id', 'fiscal_year', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_id', 'fiscal_year'], 'unique', 'targetAttribute' => ['user_id', 'fiscal_year']],
            [['satker_id', 'fiscal_year'], 'unique', 'targetAttribute' => ['satker_id', 'fiscal_year']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'        => Yii::t('app', 'ID'),
            'user_id'   => Yii::t('app', 'User'),
            'user_name' => Yii::t('app', 'User'),
            'satker_id' => Yii::t('app', 'OPD'),
            'satker_name'  => Yii::t('app', 'OPD'),
            'fiscal_year' => Yii::t('app', 'Year'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
    // Daerah Start relation
    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Satker]].
     *
     * @return \yii\db\ActiveQuery|SatkerQuery
     */
    public function getSatker()
    {
        return $this->hasOne(Satker::className(), ['id' => 'satker_id']);
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
        $this->user_name = $this->user->username;
        $this->satker_name = $this->satker->name;

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

    /**
     * Cek ID
     *
     * @return void
     */
    public function checkId($attribute, $params)
    {
        $check = self::find()->where(['user_id' => $this->user_id, 'fiscal_year' => $this->fiscal_year])->one();
        if ($check) {
            $this->addError($attribute, Yii::t('app', 'Record already exist'));
            return false;
        } else {
            return true;
        }
    }
}
