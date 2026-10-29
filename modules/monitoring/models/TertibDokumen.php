<?php

namespace app\modules\monitoring\models;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

class TertibDokumen extends ActiveRecord
{
    public $upload;

    public static function tableName()
    {
        return '{{%pengawasan_tertib_dokumen}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']],
                'updatedAtAttribute' => false,
                'value' => date('Y-m-d H:i:s'),
            ],
            [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false,
            ],
        ];
    }

    public function rules()
    {
        return [
            [['pengawasan_id', 'jenis_dokumen'], 'required'],
            [['pengawasan_id', 'file_size', 'created_by'], 'integer'],
            [['jenis_dokumen'], 'string', 'max' => 80],
            [['nama_file'], 'string', 'max' => 255],
            [['file_path'], 'string', 'max' => 80],
            [['mime_type'], 'string', 'max' => 100],
            [['upload'], 'required'],
            [['upload'], 'file', 'extensions' => ['pdf', 'jpg', 'jpeg', 'png'], 'maxSize' => 10 * 1024 * 1024, 'checkExtensionByMimeType' => false],
            [['created_at'], 'safe'],
            [['pengawasan_id'], 'exist', 'skipOnError' => true, 'targetClass' => TertibPengawasan::class, 'targetAttribute' => ['pengawasan_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return ['jenis_dokumen' => 'Jenis dokumen', 'upload' => 'Pilih file'];
    }

    public function getPengawasan()
    {
        return $this->hasOne(TertibPengawasan::class, ['id' => 'pengawasan_id']);
    }
}
