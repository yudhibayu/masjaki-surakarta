<?php

namespace app\modules\referencesMasjaki\models;

use app\modules\businessEntity\models\BusinessEntity;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class PesertaPembinaan extends ActiveRecord
{
    const BUJK = 'BUJK';
    const MASYARAKAT = 'PERSEORANGAN';
    const KELOMPOK = 'KELOMPOK';

    public $jenis_entitas = self::BUJK;

    public static function tableName()
    {
        return '{{%peserta_pembinaan}}';
    }

    public function behaviors()
    {
        return [[
            'class' => TimestampBehavior::class,
            'attributes' => [ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'], ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at']],
            'value' => date('Y-m-d H:i:s'),
        ]];
    }

    public function rules()
    {
        return [
            [['kegiatan_id', 'jenis_entitas'], 'required'],
            [['kegiatan_id', 'id_business_entity', 'masyarakat_jasa_konstruksi_id'], 'integer'],
            [['jenis_entitas'], 'in', 'range' => [self::BUJK, self::MASYARAKAT, self::KELOMPOK]],
            [['kegiatan_id'], 'exist', 'targetClass' => KegiatanPembinaan::class, 'targetAttribute' => ['kegiatan_id' => 'id'], 'filter' => ['deleted_at' => null]],
            [['id_business_entity'], 'validateBusinessEntity'],
            [['masyarakat_jasa_konstruksi_id'], 'validateMasyarakat'],
            [['jenis_entitas'], 'validateExactlyOne'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function beforeValidate()
    {
        if ($this->jenis_entitas === self::BUJK) {
            $this->masyarakat_jasa_konstruksi_id = null;
        } else {
            $this->id_business_entity = null;
        }
        return parent::beforeValidate();
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->jenis_entitas = $this->id_business_entity ? self::BUJK : self::MASYARAKAT;
    }

    public function validateExactlyOne($attribute)
    {
        $hasBujk = $this->id_business_entity !== null && $this->id_business_entity !== '';
        $hasMasyarakat = $this->masyarakat_jasa_konstruksi_id !== null && $this->masyarakat_jasa_konstruksi_id !== '';
        if ($hasBujk === $hasMasyarakat) {
            $this->addError($attribute, 'Pilih tepat satu peserta: BUJK atau Masyarakat Jasa Konstruksi.');
        }
    }

    public function validateBusinessEntity($attribute)
    {
        if (!$this->$attribute) {
            return;
        }
        $existingSelection = !$this->isNewRecord && (string) $this->getOldAttribute($attribute) === (string) $this->$attribute;
        $query = BusinessEntity::find()->where(['id' => $this->$attribute]);
        if (!$existingSelection) {
            $query->andWhere(['deleted_at' => null, 'account_verification' => BusinessEntity::VERIFICATION]);
        }
        if (!$query->exists()) {
            $this->addError($attribute, 'Pilih BUJK aktif dan terverifikasi dari master yang tersedia.');
            return;
        }
        $duplicate = self::find()->where([
            'kegiatan_id' => $this->kegiatan_id,
            'id_business_entity' => $this->$attribute,
            'deleted_at' => null,
        ]);
        if (!$this->isNewRecord) {
            $duplicate->andWhere(['<>', 'id', $this->id]);
        }
        if ($duplicate->exists()) {
            $this->addError($attribute, 'BUJK tersebut sudah terdaftar pada kegiatan ini.');
        }
    }

    public function validateMasyarakat($attribute)
    {
        if (!$this->$attribute) {
            return;
        }
        $existingSelection = !$this->isNewRecord && (string) $this->getOldAttribute($attribute) === (string) $this->$attribute;
        $jenisSubjek = $this->jenis_entitas === self::KELOMPOK
            ? MasyarakatJasaKonstruksi::KELOMPOK
            : MasyarakatJasaKonstruksi::PERSEORANGAN;
        $query = MasyarakatJasaKonstruksi::find()->where([
            'id' => $this->$attribute,
            'jenis_subjek' => $jenisSubjek,
        ]);
        if (!$existingSelection) {
            $query->andWhere(['deleted_at' => null, 'is_active' => 1]);
        }
        if (!$query->exists()) {
            $label = $jenisSubjek === MasyarakatJasaKonstruksi::KELOMPOK ? 'kelompok' : 'masyarakat';
            $this->addError($attribute, 'Pilih data ' . $label . ' yang aktif dan sesuai jenis peserta.');
            return;
        }
        $duplicate = self::find()->where([
            'kegiatan_id' => $this->kegiatan_id,
            'masyarakat_jasa_konstruksi_id' => $this->$attribute,
            'deleted_at' => null,
        ]);
        if (!$this->isNewRecord) {
            $duplicate->andWhere(['<>', 'id', $this->id]);
        }
        if ($duplicate->exists()) {
            $this->addError($attribute, 'Entitas masyarakat tersebut sudah terdaftar pada kegiatan ini.');
        }
    }

    public function attributeLabels()
    {
        return [
            'kegiatan_id' => 'Kegiatan', 'jenis_entitas' => 'Jenis Peserta',
            'id_business_entity' => 'BUJK', 'masyarakat_jasa_konstruksi_id' => 'Masyarakat / Kelompok',
        ];
    }

    public function getKegiatan()
    {
        return $this->hasOne(KegiatanPembinaan::class, ['id' => 'kegiatan_id']);
    }

    public function getBusinessEntity()
    {
        return $this->hasOne(BusinessEntity::class, ['id' => 'id_business_entity']);
    }

    public function getMasyarakatJasaKonstruksi()
    {
        return $this->hasOne(MasyarakatJasaKonstruksi::class, ['id' => 'masyarakat_jasa_konstruksi_id']);
    }

    public function getHasil()
    {
        return $this->hasOne(HasilPembinaan::class, ['peserta_pembinaan_id' => 'id']);
    }
}
