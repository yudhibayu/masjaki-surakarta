<?php

namespace app\queues;

use app\modules\data_sync\models\SyncLog;
use app\modules\rup\models\Rup;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class QueueSyncBudgetRupPenyedia extends BaseObject implements JobInterface
{
    public $chunks = [];
    public $url = 'https://isb.lkpp.go.id/isb-2/api/c3480597-421b-4831-a5d5-13b6e0681896/json/15091/RUP-PaketAnggaranPenyedia/tipe/4:12/parameter/';
    public $year = 2024;
    public $klpd_code = 'D147';
    public $sync_log_id = null;

    public function execute($queue)
    {
        if ($this->sync_log_id == null) {
            $syncLog = new SyncLog();
            $syncLog->name = 'Sync Budget RUP Penyedia';
            $syncLog->queue_total = 1;
            $syncLog->queue_finished = 0;
            $syncLog->record_total = 0;
            $syncLog->save();
            $syncLog->refresh();
            $this->sync_log_id = $syncLog->id;
        } else {
            $syncLog = SyncLog::findOne(['id' => $this->sync_log_id]);
        }

        if (sizeof($this->chunks) == 0) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->url . $this->year . ':' . $this->klpd_code,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            // return $response;
            $data = json_decode($response);
            $datachunks = array_chunk($data, 10);
            $record_total = count($data);
            $queuecount = count($datachunks);
            $syncLog->refresh();
            $syncLog->record_total = $syncLog->record_total + $record_total;
            $syncLog->queue_total = $syncLog->queue_total + $queuecount;
            $syncLog->save();
            $syncLog->refresh();

            foreach ($datachunks as $datachunk) {
                Yii::$app->queue->push(new QueueSyncBudgetRupPenyedia([
                    'chunks' => $datachunk,
                    'year' => $this->year,
                    'url' => $this->url,
                    'klpd_code' => $this->klpd_code,
                    'sync_log_id' => $syncLog->id,
                ]));
            }
        } else {
            $saved = [];
            $notsaved = [];
            foreach ($this->chunks as $row) {
                // $model = Rup::findOne(['idrup' => $row->koderup, 'tahunanggaran' => $this->year]);
                $model = Rup::findOne(['idrup' => $row->kd_rup, 'tahunanggaran' => $this->year]);
                if ($model) {

                    // $model->sumberdana = $row->sumberdana;
                    $model->sumberdana = $row->sumber_dana;

                    if ($model->save(false)) {
                        $saved[] = $model->idrup;
                    } else {
                        $model->validate();
                        $notsaved[] = [
                            'id' => $model->idrup,
                            'errors' => $model->errors,
                        ];
                    }
                }
            }
            if (sizeof($notsaved) > 0) {
                Yii::error(['saved' => $saved, 'not saved' => $notsaved]);
            }
        }
        $syncLog->refresh();
        $syncLog->queue_finished = (int)$syncLog->queue_finished + 1;
        $syncLog->save();
    }
}
