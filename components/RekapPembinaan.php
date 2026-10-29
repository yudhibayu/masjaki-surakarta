<?php

namespace app\components;

use yii\db\Query;

class RekapPembinaan
{
    public static function summary($year = null, $publishedOnly = true, $typeId = null)
    {
        $events = self::eventQuery($publishedOnly);
        self::applyFilters($events, $year, $typeId);
        $base = self::withParticipantCounts($events);
        $stats = (clone $base)->select([
            'bujk' => 'COUNT(DISTINCT p.id_business_entity)',
            'masyarakat' => 'COUNT(DISTINCT p.masyarakat_jasa_konstruksi_id)',
            'kegiatan' => 'COUNT(DISTINCT k.id)',
            'keikutsertaan' => 'COUNT(DISTINCT p.id)',
        ])->one();

        $annual = self::withParticipantCounts(self::eventQuery($publishedOnly));
        self::applyFilters($annual, $year, $typeId);
        $rows = $annual->select([
            'tahun' => 'k.tahun_anggaran',
            'bujk' => 'COUNT(DISTINCT p.id_business_entity)',
            'masyarakat' => 'COUNT(DISTINCT p.masyarakat_jasa_konstruksi_id)',
            'kegiatan' => 'COUNT(DISTINCT k.id)',
            'keikutsertaan' => 'COUNT(DISTINCT p.id)',
        ])->groupBy('k.tahun_anggaran')->orderBy(['k.tahun_anggaran' => SORT_DESC])->all();

        $byType = self::withParticipantCounts(self::eventQuery($publishedOnly))
            ->leftJoin(['j' => '{{%jenis_kegiatan_pembinaan}}'], 'j.id = k.jenis_kegiatan_id');
        self::applyFilters($byType, $year, $typeId);
        $typeRows = $byType->select([
            'jenis_kegiatan_id' => 'k.jenis_kegiatan_id',
            'jenis_kegiatan' => 'j.nama',
            'bujk' => 'COUNT(DISTINCT p.id_business_entity)',
            'masyarakat' => 'COUNT(DISTINCT p.masyarakat_jasa_konstruksi_id)',
            'kegiatan' => 'COUNT(DISTINCT k.id)',
            'keikutsertaan' => 'COUNT(DISTINCT p.id)',
        ])->groupBy(['k.jenis_kegiatan_id', 'j.nama'])->orderBy(['j.nama' => SORT_ASC])->all();

        $yearsQuery = (new Query())->select('tahun_anggaran')->distinct()
            ->from(['k' => '{{%kegiatan_pembinaan}}'])->where(['k.deleted_at' => null]);
        if ($publishedOnly) {
            $yearsQuery->andWhere(['k.is_published' => 1]);
        }
        $years = $yearsQuery->orderBy(['tahun_anggaran' => SORT_DESC])->column();
        $stats = $stats ?: ['bujk' => 0, 'masyarakat' => 0, 'kegiatan' => 0, 'keikutsertaan' => 0];
        foreach (['bujk', 'masyarakat', 'kegiatan', 'keikutsertaan'] as $key) {
            $stats[$key] = (int) $stats[$key];
        }
        return ['stats' => $stats, 'rows' => $rows, 'typeRows' => $typeRows, 'years' => $years];
    }

    private static function applyFilters(Query $query, $year, $typeId)
    {
        if ($year !== null) {
            $query->andWhere(['k.tahun_anggaran' => (int) $year]);
        }
        if ($typeId !== null) {
            $query->andWhere(['k.jenis_kegiatan_id' => (int) $typeId]);
        }
    }

    private static function eventQuery($publishedOnly)
    {
        $query = (new Query())->from(['k' => '{{%kegiatan_pembinaan}}'])
            ->where(['k.deleted_at' => null]);
        if ($publishedOnly) {
            $query->andWhere(['k.is_published' => 1]);
        }
        return $query;
    }

    private static function withParticipantCounts(Query $query)
    {
        return $query->leftJoin(['p' => '{{%peserta_pembinaan}}'], 'p.kegiatan_id = k.id AND p.deleted_at IS NULL');
    }
}
