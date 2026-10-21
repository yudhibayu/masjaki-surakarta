<?php

namespace app\modules\referencesMasjaki\models;

use yii\base\Model;

class TkkReportFilter extends Model
{
    public $tahun;
    public $kegiatan;
    public $lsp;
    public $jabatan;
    public $jenjang;
    public $skema;
    public $status;

    public function rules()
    {
        return [
            [['tahun', 'kegiatan', 'lsp'], 'integer'],
            [['jabatan', 'jenjang', 'skema'], 'string', 'max' => 180],
            [['status'], 'in', 'range' => array_keys(PesertaFasilitasiSertifikasi::STATUS)],
        ];
    }

    public function formName()
    {
        return '';
    }

    public function apply($query)
    {
        foreach ([
            'tahun' => 'k.tahun_anggaran', 'kegiatan' => 'p.kegiatan_id', 'lsp' => 'k.lsp_id',
            'jabatan' => 'p.jabatan_kerja', 'jenjang' => 'p.jenjang_kualifikasi',
            'skema' => 'p.skema_sertifikasi', 'status' => 'p.status_sertifikasi',
        ] as $attribute => $column) {
            if ($this->$attribute !== null && $this->$attribute !== '') {
                $query->andWhere([$column => $this->$attribute]);
            }
        }
        return $query;
    }
}
