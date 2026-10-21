<?php

namespace app\components;

use app\modules\referencesMasjaki\models\TkkPublikFilter;
use yii\data\Pagination;
use yii\db\Query;

class TkkPublikRekap
{
    public static function summary(TkkPublikFilter $filter)
    {
        $base = self::pesertaQuery($filter);
        $stats = (clone $base)->select([
            'tenaga_kerja' => 'COUNT(DISTINCT p.tenaga_kerja_id)',
            'keikutsertaan' => 'COUNT(*)',
            'kompeten' => "SUM(CASE WHEN p.status_sertifikasi='KOMPETEN' THEN 1 ELSE 0 END)",
            'skk' => "SUM(CASE WHEN p.nomor_skk IS NOT NULL AND p.nomor_skk<>'' AND p.tanggal_terbit_skk IS NOT NULL THEN 1 ELSE 0 END)",
        ])->one();
        $years = (new Query())->select('tahun_anggaran')->distinct()
            ->from('{{%kegiatan_fasilitasi_sertifikasi}}')->where(['is_public' => 1])
            ->orderBy(['tahun_anggaran' => SORT_DESC])->column();
        return ['stats' => $stats, 'years' => array_combine($years, $years) ?: []];
    }

    private static function pesertaQuery(TkkPublikFilter $filter)
    {
        $query = (new Query())
            ->from(['p' => '{{%peserta_fasilitasi_sertifikasi}}'])
            ->innerJoin(['k' => '{{%kegiatan_fasilitasi_sertifikasi}}'], 'k.id=p.kegiatan_id')
            ->where(['k.is_public' => 1]);
        return $filter->apply($query);
    }

    public static function data(TkkPublikFilter $filter)
    {
        $base = self::pesertaQuery($filter);
        $stats = (clone $base)->select([
            'tenaga_kerja' => 'COUNT(DISTINCT p.tenaga_kerja_id)',
            'keikutsertaan' => 'COUNT(*)',
            'kompeten' => "SUM(CASE WHEN p.status_sertifikasi='KOMPETEN' THEN 1 ELSE 0 END)",
            'belum_kompeten' => "SUM(CASE WHEN p.status_sertifikasi='BELUM_KOMPETEN' THEN 1 ELSE 0 END)",
            'skk' => "SUM(CASE WHEN p.nomor_skk IS NOT NULL AND p.nomor_skk<>'' AND p.tanggal_terbit_skk IS NOT NULL THEN 1 ELSE 0 END)",
        ])->one();

        if ($filter->hasParticipantFilter()) {
            $kegiatan = (clone $base)->select('COUNT(DISTINCT p.kegiatan_id)')->scalar();
        } else {
            $activities = (new Query())->from('{{%kegiatan_fasilitasi_sertifikasi}}')
                ->where(['is_public' => 1]);
            if ($filter->tahun !== null && $filter->tahun !== '') {
                $activities->andWhere(['tahun_anggaran' => $filter->tahun]);
            }
            $kegiatan = $activities->count();
        }
        $stats['kegiatan'] = (int) $kegiatan;

        $groups = self::groupQuery($base);
        [$rows, $pagination] = self::page($groups);

        $years = (new Query())->select('tahun_anggaran')->distinct()
            ->from('{{%kegiatan_fasilitasi_sertifikasi}}')->where(['is_public' => 1])
            ->orderBy(['tahun_anggaran' => SORT_DESC])->column();
        return [
            'stats' => $stats, 'rows' => $rows, 'pagination' => $pagination,
            'years' => array_combine($years, $years) ?: [],
            'jabatan' => self::options('jabatan_kerja'),
            'jenjang' => self::options('jenjang_kualifikasi'),
            'skema' => self::options('skema_sertifikasi'),
        ];
    }

    public static function detail($id)
    {
        $activity = (new Query())->select(['id', 'nama_kegiatan', 'tahun_anggaran'])
            ->from('{{%kegiatan_fasilitasi_sertifikasi}}')
            ->where(['id' => $id, 'is_public' => 1])->one();
        if ($activity === false) { return null; }
        $base = self::pesertaQuery(new TkkPublikFilter());
        $base->andWhere(['k.id' => $id]);
        [$rows, $pagination] = self::page(self::groupQuery($base));
        return ['activity' => $activity, 'rows' => $rows, 'pagination' => $pagination];
    }

    private static function groupQuery(Query $base)
    {
        return (clone $base)->select([
            'kegiatan_id' => 'k.id',
            'tahun_anggaran' => 'k.tahun_anggaran',
            'nama_kegiatan' => 'k.nama_kegiatan',
            'jabatan_kerja' => 'p.jabatan_kerja',
            'jenjang_kualifikasi' => 'p.jenjang_kualifikasi',
            'skema_sertifikasi' => 'p.skema_sertifikasi',
            'jumlah_peserta' => 'COUNT(DISTINCT p.tenaga_kerja_id)',
            'jumlah_kompeten' => "COUNT(DISTINCT CASE WHEN p.status_sertifikasi='KOMPETEN' THEN p.tenaga_kerja_id END)",
            'jumlah_skk' => "COUNT(DISTINCT CASE WHEN p.nomor_skk IS NOT NULL AND p.nomor_skk<>'' AND p.tanggal_terbit_skk IS NOT NULL THEN p.tenaga_kerja_id END)",
        ])->groupBy([
            'k.id', 'k.tahun_anggaran', 'k.nama_kegiatan', 'p.jabatan_kerja',
            'p.jenjang_kualifikasi', 'p.skema_sertifikasi',
        ]);
    }

    private static function page(Query $groups)
    {
        $pagination = new Pagination([
            'totalCount' => (int) (new Query())->from(['rekap' => clone $groups])->count('*'),
            'pageSize' => 20,
        ]);
        $rows = (clone $groups)->orderBy([
            'k.tahun_anggaran' => SORT_DESC, 'k.nama_kegiatan' => SORT_ASC,
            'p.jabatan_kerja' => SORT_ASC, 'p.skema_sertifikasi' => SORT_ASC,
        ])->offset($pagination->offset)->limit($pagination->limit)->all();
        return [$rows, $pagination];
    }

    private static function options($column)
    {
        $query = (new Query())->select('p.' . $column)->distinct()
            ->from(['p' => '{{%peserta_fasilitasi_sertifikasi}}'])
            ->innerJoin(['k' => '{{%kegiatan_fasilitasi_sertifikasi}}'], 'k.id=p.kegiatan_id')
            ->where(['k.is_public' => 1])->andWhere(['not', ['p.' . $column => null]])
            ->andWhere(['<>', 'p.' . $column, ''])->orderBy(['p.' . $column => SORT_ASC]);
        $values = $query->column();
        return array_combine($values, $values) ?: [];
    }
}
