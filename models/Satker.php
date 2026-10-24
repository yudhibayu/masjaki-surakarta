<?php

namespace app\models;

use app\modules\manage_sot\models\RefSot;
use Yii;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\i18n\Formatter;
use yii\db\ActiveRecord;
use app\overrides\user\models\User;

/**
 * This is the model class for table "opd".
 *
 * @property int $id ID
 * @property string $name SATKER
 * @property string $code Kode SATKER
 * @property int $fiscal_year Tahun
 * @property string|null $created_at Created At
 * @property string|null $updated_at Updated At
 * @property int|null $created_by Created By
 * @property int|null $updated_by Updated By
 */
class Satker extends \yii\db\ActiveRecord
{
    public $sess;
    public $namecode;

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
        return 'satker';
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
            [['name', 'code','ref_sot_id'], 'required'],
            [['created_by', 'updated_by', 'ref_sot_id'], 'integer'],
            [['created_at', 'updated_at','ref_sot_name'], 'safe'],
            [['name', 'code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Satker'),
            'code' => Yii::t('app', 'Satker Code'),
            'ref_sot_id' => Yii::t('manage_sot', 'Sot'),
            'ref_sot_name' => Yii::t('manage_sot', 'Sot'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
// Daerah Start relation
    /**
     * Gets query for [[RefSot]].
     *
     * @return \yii\db\ActiveQuery|RefSotQuery
     */
    public function getRefSot()
    {
        return $this->hasOne(RefSot::className(), ['id' => 'ref_sot_id']);
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



    /**
     * {@inheritdoc}
     * @return SatkerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SatkerQuery(get_called_class());
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
        $this->ref_sot_name = $this->refSot->name;
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
        $this->namecode = $this->name.' ('.$this->code.')';
        parent::afterFind();
        return true;
    }

    public function getSirupNameVariants()
    {
        $name = strtoupper(preg_replace('/\\s+/u', ' ', trim($this->name)));
        $names = [$name];

        if (preg_match('/\\s+KOTA\\s+SURAKARTA$/u', $name)) {
            $names[] = preg_replace('/\\s+KOTA\\s+SURAKARTA$/u', '', $name);
        } elseif (preg_match('/\\s+SURAKARTA$/u', $name)) {
            $names[] = preg_replace('/\\s+SURAKARTA$/u', ' KOTA SURAKARTA', $name);
        }

        $sirupAliases = [
            'BADAN PENGELOLA KEUANGAN DAN ASET DAERAH' => ['BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH'],
            'DINAS PEMBERDAYAAN PEREMPUAN DAN PERLINDUNGAN ANAK SERTA PENGENDALIAN PENDUDUK DAN KELUARGA BERENCANA' => [
                'DINAS PEMBERDAYAAN PEREMPUAN DAN PELINDUNGAN ANAK SERTA PENGENDALIAN PENDUDUK DAN KELUARGA BERENCANA',
            ],
            'KELURAHAN PASARKLIWON' => ['KELURAHAN PASAR KLIWON'],
            'RSUD BUNG KARNO KOTA SURAKARTA' => ['RSUD BUNG KARNO'],
            'RSUD IBU FATMAWATI SOEKARNO KOTA SURAKARTA' => ['RSUD IBU FATMAWATI SOEKARNO'],
            'UPTD PENGELOLAAN TPA SAMPAH' => ['UPTD PENGELOLAAN TEMPAT PEMROSESAN AKHIR SAMPAH'],
            'UPTD RPH DAN PUSKESWAN' => ['UPTD RUMAH POTONG HEWAN DAN PUSAT KESEHATAN HEWAN'],
        ];
        foreach ($sirupAliases[$name] ?? [] as $alias) {
            $names[] = $alias;
        }

        return array_values(array_unique($names));
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
     * {@inheritdoc}
     * @return User
     */
    public function getUsers($asArray = false)
    {
        $userids = UserSatker::find()->select('user_id')->where(['satker_id' => $this->id, 'fiscal_year' => $this->sess['year']['name']])->column();
        if ($asArray) {
            $users = User::find()->where(['in', 'id', $userids])->asArray()->all();
        } else {
            $users = User::find()->where(['in', 'id', $userids])->all();
        }
        return $users;
    }

    public function getUsersName()
    {
        $users = $this->getUsers();
        $names = [];
        foreach ($users as $user) {
            // $user = new User();
            $names[] = isset($user->profile->name) ? $user->profile->name : $user->username;
        }
        return $names;
    }
}
