<?php

namespace app\commands;

use app\queues\QueueSyncNonTenderPbjSirup;
use app\queues\QueueSyncTenderPbjSirup;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\mutex\MysqlMutex;

/**
 * Sinkronisasi PBJ konstruksi Kota Surakarta dari SIRUP.
 */
class SirupSyncController extends Controller
{
    /** Antrekan sinkronisasi Tender. Tahun default mengikuti kalender WIB. */
    public function actionTender($year = null)
    {
        $year = $this->resolveYear($year);
        $id = Yii::$app->queue->push(new QueueSyncTenderPbjSirup(['year' => $year]));
        $this->stdout("Tender {$year} masuk antrean (job {$id}).\n");
        return ExitCode::OK;
    }

    /** Antrekan sinkronisasi Non Tender. Tahun default mengikuti kalender WIB. */
    public function actionNonTender($year = null)
    {
        $year = $this->resolveYear($year);
        $id = Yii::$app->queue->push(new QueueSyncNonTenderPbjSirup(['year' => $year]));
        $this->stdout("Non Tender {$year} masuk antrean (job {$id}).\n");
        return ExitCode::OK;
    }

    /**
     * Antrekan Tender dan Non Tender, lalu proses antrean sampai kosong.
     * Cocok untuk penjadwal OS.
     */
    public function actionRun($year = null)
    {
        $year = $this->resolveYear($year);
        $mutex = new MysqlMutex(['db' => Yii::$app->db]);
        $lockName = 'sirup-sync-pbj-' . $year;
        if (!$mutex->acquire($lockName, 0)) {
            $this->stdout("Sinkronisasi {$year} sedang berjalan.\n");
            return ExitCode::OK;
        }

        try {
            $this->actionTender($year);
            $this->actionNonTender($year);
            $this->stdout("Memproses antrean...\n");
            $result = Yii::$app->queue->run(false);
            $this->stdout("Pemrosesan antrean selesai.\n");
            return $result === null ? ExitCode::OK : $result;
        } finally {
            $mutex->release($lockName);
        }
    }

    private function resolveYear($year)
    {
        if ($year === null) {
            $year = (new \DateTimeImmutable('now', new \DateTimeZone('Asia/Jakarta')))->format('Y');
        }
        if (!is_scalar($year) || !preg_match('/^\d{4}$/', (string) $year)
            || (int) $year < 2000 || (int) $year > 2100) {
            throw new \yii\console\Exception('Tahun harus berupa angka 4 digit antara 2000 dan 2100.');
        }
        return (int) $year;
    }
}
