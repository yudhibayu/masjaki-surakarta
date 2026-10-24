<?php

namespace app\components;

use yii\db\Query;

class TertibPublikRekap
{
    public static function years()
    {
        return (new Query())
            ->select('mt.fiscal_year')
            ->distinct()
            ->from(['k' => '{{%pengawasan_tertib}}'])
            ->innerJoin(['mt' => '{{%monitoring_task}}'], 'mt.id = k.monitoring_task_id')
            ->where(['k.is_public' => 1])
            ->andWhere(['>', 'mt.fiscal_year', 0])
            ->orderBy(['mt.fiscal_year' => SORT_DESC])
            ->column();
    }

    public static function summary($year)
    {
        $activityQuery = self::activityQuery($year);
        $activity = (clone $activityQuery)->select([
            'paket' => 'COUNT(DISTINCT k.monitoring_task_id)',
            'pengawasan' => 'COUNT(DISTINCT k.id)',
        ])->one();

        $findingQuery = self::findingQuery($year);
        $findings = (clone $findingQuery)->select('COUNT(DISTINCT f.id)')->scalar();
        $completed = (clone $findingQuery)
            ->andWhere(['f.status_tindak_lanjut' => 'SELESAI'])
            ->select('COUNT(DISTINCT f.id)')->scalar();

        $annual = [];
        foreach ((clone self::activityQuery(null))->select([
            'tahun' => 'mt.fiscal_year',
            'paket' => 'COUNT(DISTINCT k.monitoring_task_id)',
            'pengawasan' => 'COUNT(DISTINCT k.id)',
        ])->groupBy('mt.fiscal_year')->orderBy(['mt.fiscal_year' => SORT_DESC])->all() as $row) {
            $annual[(int) $row['tahun']] = [
                'tahun' => (int) $row['tahun'],
                'paket' => (int) $row['paket'],
                'pengawasan' => (int) $row['pengawasan'],
                'temuan' => 0,
                'selesai' => 0,
            ];
        }

        foreach ((clone self::findingQuery(null))->select([
            'tahun' => 'mt.fiscal_year',
            'temuan' => 'COUNT(DISTINCT f.id)',
            'selesai' => "COUNT(DISTINCT CASE WHEN f.status_tindak_lanjut = 'SELESAI' THEN f.id END)",
        ])->groupBy('mt.fiscal_year')->all() as $row) {
            $yearKey = (int) $row['tahun'];
            if (isset($annual[$yearKey])) {
                $annual[$yearKey]['temuan'] = (int) $row['temuan'];
                $annual[$yearKey]['selesai'] = (int) $row['selesai'];
            }
        }

        return [
            'stats' => [
                'paket' => (int) ($activity['paket'] ?? 0),
                'pengawasan' => (int) ($activity['pengawasan'] ?? 0),
                'temuan' => (int) $findings,
                'selesai' => (int) $completed,
            ],
            'rows' => array_values($annual),
        ];
    }

    private static function activityQuery($year)
    {
        $query = (new Query())
            ->from(['k' => '{{%pengawasan_tertib}}'])
            ->innerJoin(['mt' => '{{%monitoring_task}}'], 'mt.id = k.monitoring_task_id')
            ->where(['k.is_public' => 1])
            ->andWhere(['>', 'mt.fiscal_year', 0]);
        if ($year !== null) {
            $query->andWhere(['mt.fiscal_year' => (int) $year]);
        }
        return $query;
    }

    private static function findingQuery($year)
    {
        $query = (new Query())
            ->from(['f' => '{{%pengawasan_tertib_temuan}}'])
            ->innerJoin(['k' => '{{%pengawasan_tertib}}'], 'k.id = f.pengawasan_id')
            ->innerJoin(['mt' => '{{%monitoring_task}}'], 'mt.id = k.monitoring_task_id')
            ->where(['k.is_public' => 1, 'f.is_public' => 1])
            ->andWhere(['>', 'mt.fiscal_year', 0]);
        if ($year !== null) {
            $query->andWhere(['mt.fiscal_year' => (int) $year]);
        }
        return $query;
    }
}
