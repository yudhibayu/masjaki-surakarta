<?php

namespace app\queues;

use app\modules\data_sync\models\SyncLog;
use app\modules\rup\models\Rup;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class QueueSyncRupPenyedia extends BaseObject implements JobInterface
{
    public $chunks = [];
    public $url = 'https://isb.lkpp.go.id/isb-2/api/4eb7e158-259e-4a6a-9e20-fdf43ca9f678/json/15105/RUP-PaketPenyedia-Terumumkan/tipe/4:12/parameter/';
    public $year = 2024;
    public $klpd_code = 'D147';
    public $sync_log_id = null;

    public function execute($queue)
    {
        if ($this->sync_log_id == null) {
            $syncLog = new SyncLog();
            $syncLog->name = 'Sync RUP Penyedia';
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
                Yii::$app->queue->push(new QueueSyncRupPenyedia([
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
                if ($row->status_umumkan_rup === 'Terumumkan' && $row->jenis_pengadaan === 'Pekerjaan Konstruksi') {
                    // $model = Rup::findOne(['idrup' => $row->idrup, 'tahunanggaran' => $this->year]);
                    $model = Rup::findOne(['idrup' => $row->kd_rup, 'tahunanggaran' => $this->year]);
                    if (!$model) {
                        $model = new Rup();
                    }
                    // foreach ($row as $key => $val) {
                    //     $model->$key = $val;
                    // }

                    // untuk penyedia
                    $model->tipe_rup = 0;
                    $model->is_sync = true;

                    $model->tahunanggaran = $row->tahun_anggaran;
                    $model->kd_klpd = $row->kd_klpd;
                    $model->kodesatker = $row->kd_satker_str;
                    $model->idsatker = $row->kd_satker;
                    $model->namasatker = $row->nama_satker;
                    $model->id = $row->kd_rup;
                    $model->idrup = $row->kd_rup;
                    $model->namapaket = $row->nama_paket;
                    $model->jumlahpagu = $row->pagu;
                    $model->idmetodepengadaan = $row->kd_metode_pengadaan;
                    $model->metodepengadaan = $row->metode_pengadaan;
                    $model->idjenispengadaan = $row->kd_jenis_pengadaan;
                    $model->jenispengadaan = $row->jenis_pengadaan;
                    $model->statuspradipa = $row->status_pradipa;
                    $model->statuspdn = $row->status_pdn;
                    $model->statususahakecil = $row->status_ukm;
                    $model->statuspaketkonsolidasi = $row->status_konsolidasi;
                    $model->volume = $row->volume_pekerjaan;
                    $model->keterangan = $row->spesifikasi_pekerjaan;
                    $model->tanggalpengumuman = $row->tgl_pengumuman_paket;
                    $model->tanggalkebutuhan = $row->tgl_buat_paket;
                    $model->tanggalawalpemilihan = $row->tgl_awal_pemilihan;
                    $model->tanggalakhirpemilihan = $row->tgl_akhir_pemilihan;
                    $model->tanggalawalpekerjaan = $row->tgl_awal_kontrak;
                    $model->tanggalakhirpekerjaan = $row->tgl_akhir_kontrak;
                    $model->ppk = $row->nama_ppk;
                    $model->nip_ppk = $row->nip_ppk;
                    $model->statusaktifpaket = $row->status_aktif_rup;
                    $model->statusdeletepaket = $row->status_delete_rup;
                    $model->statusumumkan = $row->status_umumkan_rup;

                    // $model->id = (int)$row->idrup;
                    $model->id = (int)$row->kd_rup;
                    $model->idsatker = (string)$model->idsatker;
                    $model->statusdeletepaket = (string)$model->statusdeletepaket;
                    $model->statusaktifpaket = (string)$model->statusaktifpaket;
                    $model->statusumumkan = (string)$model->statusumumkan;
                    $model->idjenispengadaan = (int)$model->idjenispengadaan;
                    $model->tahunanggaran = (string)$model->tahunanggaran;
                    // $model->spesifikasi = $this->trim_me($model->spesifikasi,250);
                    // $model->namapaket = $this->trim_me($model->namapaket,250);
                    // $model->keterangan = $this->trim_me($model->keterangan,250);
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
                echo json_encode(['saved' => $saved, 'not saved' => $notsaved]);
            }
        }
        $syncLog->refresh();
        $syncLog->queue_finished = (int)$syncLog->queue_finished + 1;
        $syncLog->save();
    }
}