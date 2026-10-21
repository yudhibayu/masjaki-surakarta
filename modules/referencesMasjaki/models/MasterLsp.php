<?php

namespace app\modules\referencesMasjaki\models;

class MasterLsp extends TkkRecord
{
    public static function tableName()
    {
        return '{{%master_lsp}}';
    }

    public function rules()
    {
        return [
            [['nama_lsp'], 'filter', 'filter' => function ($value) { return $value === null ? null : trim($value); }],
            [['nama_lsp'], 'required'],
            [['nama_lsp'], 'string', 'max' => 180],
            [['nama_lsp'], 'unique'],
            [['is_active'], 'boolean'],
            [['is_active'], 'default', 'value' => 1],
        ];
    }

    public function attributeLabels()
    {
        return ['nama_lsp' => 'Nama LSP', 'is_active' => 'Aktif'];
    }

    public function getKegiatan()
    {
        return $this->hasMany(KegiatanFasilitasiSertifikasi::class, ['lsp_id' => 'id']);
    }
}
