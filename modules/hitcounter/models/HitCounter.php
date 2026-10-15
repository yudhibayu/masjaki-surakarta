<?php

namespace app\modules\hitcounter\models;

use Yii;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\i18n\Formatter;
use yii\db\ActiveRecord;
use app\overrides\user\models\User;

/**
 * This is the model class for table "hit_counter".
 *
 * @property int $id
 * @property string|null $counter_id
 * @property string|null $cookie_mark
 * @property int|null $js_cookei_enabled
 * @property int|null $js_java_enabled
 * @property int|null $js_timezone_offset
 * @property string|null $js_timezone
 * @property string|null $js_connection
 * @property string|null $js_current_url
 * @property string|null $js_referer_url
 * @property int|null $js_screen_width
 * @property int|null $js_screen_height
 * @property int|null $js_color_depth
 * @property string|null $js_browser_language
 * @property int|null $js_history_length
 * @property int|null $js_is_toutch_device
 * @property int|null $js_processor_ram
 * @property string|null $serv_ip
 * @property string|null $serv_user_agent
 * @property string|null $serv_referer_url
 * @property string|null $serv_server_name
 * @property int|null $serv_auth_user_id
 * @property int|null $serv_port
 * @property string|null $serv_cookies
 * @property string|null $serv_os
 * @property string|null $serv_client
 * @property string|null $serv_device
 * @property string|null $serv_brand
 * @property string|null $serv_model
 * @property string|null $serv_bot
 * @property string|null $serv_host_by_ip
 * @property int|null $serv_is_proxy_or_vpn
 * @property string $created_at
 */
class HitCounter extends \yii\db\ActiveRecord
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
        return 'hit_counter';
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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['js_cookei_enabled', 'js_java_enabled', 'js_timezone_offset', 'js_screen_width', 'js_screen_height', 'js_color_depth', 'js_history_length', 'js_is_toutch_device', 'js_processor_ram', 'serv_auth_user_id', 'serv_port', 'serv_is_proxy_or_vpn'], 'integer'],
            [['js_current_url', 'js_referer_url', 'serv_user_agent', 'serv_referer_url'], 'string'],
            [['serv_cookies', 'created_at'], 'safe'],
            [['created_at'], 'required'],
            [['counter_id', 'js_timezone', 'js_connection', 'js_browser_language', 'serv_server_name', 'serv_os', 'serv_client', 'serv_device', 'serv_brand', 'serv_model', 'serv_bot', 'serv_host_by_ip'], 'string', 'max' => 255],
            [['cookie_mark'], 'string', 'max' => 32],
            [['serv_ip'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'counter_id' => Yii::t('app', 'Counter ID'),
            'cookie_mark' => Yii::t('app', 'Cookie Mark'),
            'js_cookei_enabled' => Yii::t('app', 'Js Cookei Enabled'),
            'js_java_enabled' => Yii::t('app', 'Js Java Enabled'),
            'js_timezone_offset' => Yii::t('app', 'Js Timezone Offset'),
            'js_timezone' => Yii::t('app', 'Js Timezone'),
            'js_connection' => Yii::t('app', 'Js Connection'),
            'js_current_url' => Yii::t('app', 'Js Current Url'),
            'js_referer_url' => Yii::t('app', 'Js Referer Url'),
            'js_screen_width' => Yii::t('app', 'Js Screen Width'),
            'js_screen_height' => Yii::t('app', 'Js Screen Height'),
            'js_color_depth' => Yii::t('app', 'Js Color Depth'),
            'js_browser_language' => Yii::t('app', 'Js Browser Language'),
            'js_history_length' => Yii::t('app', 'Js History Length'),
            'js_is_toutch_device' => Yii::t('app', 'Js Is Toutch Device'),
            'js_processor_ram' => Yii::t('app', 'Js Processor Ram'),
            'serv_ip' => Yii::t('app', 'Serv Ip'),
            'serv_user_agent' => Yii::t('app', 'Serv User Agent'),
            'serv_referer_url' => Yii::t('app', 'Serv Referer Url'),
            'serv_server_name' => Yii::t('app', 'Serv Server Name'),
            'serv_auth_user_id' => Yii::t('app', 'Serv Auth User ID'),
            'serv_port' => Yii::t('app', 'Serv Port'),
            'serv_cookies' => Yii::t('app', 'Serv Cookies'),
            'serv_os' => Yii::t('app', 'Serv Os'),
            'serv_client' => Yii::t('app', 'Serv Client'),
            'serv_device' => Yii::t('app', 'Serv Device'),
            'serv_brand' => Yii::t('app', 'Serv Brand'),
            'serv_model' => Yii::t('app', 'Serv Model'),
            'serv_bot' => Yii::t('app', 'Serv Bot'),
            'serv_host_by_ip' => Yii::t('app', 'Serv Host By Ip'),
            'serv_is_proxy_or_vpn' => Yii::t('app', 'Serv Is Proxy Or Vpn'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }
    // Daerah Start relation

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
        $this->counter_name = $this->counter->name;
        $this->serv_auth_user_name = $this->servAuthUser->name;

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
