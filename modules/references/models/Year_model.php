<?php

namespace app\modules\references\models;

use app\modules\manage_sot\models\RefSot;
use app\modules\references\References;
use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "mod_ref_year".
 *
 * @property int id
 * @property int nama
 * @property string aktif
 */
class Year_model extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ref_year';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'active', 'active_login'], 'required'],
            [['name'], 'unique'],
            [['name','ref_sot_id'], 'integer'],
            [['active','ref_sot_name'], 'string'],
            [['active_login'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => References::t('year_lang','ID'),
            'name' => References::t('year_lang','Year Name'),
            'active' => References::t('year_lang','Active'),
            'active_login' => References::t('year_lang','Active Login'),
            'ref_sot_id' => Yii::t('manage_sot', 'Sot'),
            'ref_sot_name' => Yii::t('manage_sot', 'Sot'),
        ];
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
     * Gets query for [[RefSot]].
     *
     * @return \yii\db\ActiveQuery|RefSotQuery
     */
    public function getRefSot()
    {
        return $this->hasOne(RefSot::className(), ['id' => 'ref_sot_id']);
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
}
