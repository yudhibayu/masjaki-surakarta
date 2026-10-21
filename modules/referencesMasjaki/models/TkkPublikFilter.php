<?php

namespace app\modules\referencesMasjaki\models;

use yii\base\Model;

class TkkPublikFilter extends Model
{
    public $tahun;
    public $jabatan;
    public $jenjang;
    public $skema;

    public function formName()
    {
        return '';
    }

    public function rules()
    {
        return [
            [['tahun'], 'integer', 'min' => 2000, 'max' => 2100],
            [['jabatan', 'jenjang', 'skema'], 'string', 'max' => 180],
        ];
    }

    public function apply($query)
    {
        foreach ([
            'tahun' => 'k.tahun_anggaran', 'jabatan' => 'p.jabatan_kerja',
            'jenjang' => 'p.jenjang_kualifikasi', 'skema' => 'p.skema_sertifikasi',
        ] as $attribute => $column) {
            if ($this->$attribute !== null && $this->$attribute !== '') {
                $query->andWhere([$column => $this->$attribute]);
            }
        }
        return $query;
    }

    public function hasParticipantFilter()
    {
        return $this->jabatan !== null && $this->jabatan !== ''
            || $this->jenjang !== null && $this->jenjang !== ''
            || $this->skema !== null && $this->skema !== '';
    }
}
