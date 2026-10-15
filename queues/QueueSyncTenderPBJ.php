<?php

namespace app\queues;

use app\modules\data_sync\models\SyncLog;
use app\modules\rup\models\Pbj;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class QueueSyncTenderPBJ extends BaseObject implements JobInterface
{
    public $chunks = [];
    public $url = 'https://eprocinfo.bpbj.surakarta.go.id/api/rest-tender?filter[tahun]=';
    public $year = 2024;
    public $klpd_code = 'D147';
    public $sync_log_id = null;

    public function execute($queue)
    {
        if ($this->sync_log_id == null) {
            $syncLog = new SyncLog();
            $syncLog->name = 'Sync Tender PBJ';
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
                CURLOPT_URL => $this->url . $this->year,
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
                Yii::$app->queue->push(new QueueSyncTenderPBJ([
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
                if ($row->jenis_pengadaan === 'Pekerjaan Konstruksi') {
                    $model = Pbj::findOne(['kodelelang' => $row->id_tender, 'fiscal_year' => $this->year]);
                    if (!$model) {
                        $model = new Pbj();
                    }
                    // foreach ($row as $key => $val) {
                    //     $model->$key = $val;
                    // }

                    $model->pbj_type = 0;
                    $model->kodelelang = $row->id_tender;
                    $model->name = $row->nama_paket;
                    $model->rup_code = $row->id_rup;
                    $model->budget = $row->pagu_paket;
                    $model->hps = $row->hps_paket;
                    $model->bid_price = $row->penawaran;
                    $model->final_price = $row->negosiasi;
                    $model->fiscal_year = $row->tahun;
                    $model->ppk_name = $row->ppk;
                    $model->vendor_name = $row->pemenang;
                    $model->work_start_date = $row->mulai;
                    $model->work_finished_date = $row->selesai;
                    $model->kodesatker = $row->opd_kode;
                    $model->namasatker = $row->opd;
                    $model->jenispengadaan = $row->jenis_pengadaan;
                    // $model->kodelelang = $row->rup_stk_id;
                    // $model->kodelelang = $row->id_rup;
                    // $model->kodelelang = $row->id_paket;
                    // $model->kodelelang = $row->tanggal_buat;
                    // $model->kodelelang = $row->jenis_pengadaan;
                    // $model->kodelelang = $row->kepanitiaan;
                    // $model->kodelelang = $row->anggota_pokja;
                    // $model->kodelelang = $row->pemenang_alamat;
                    // $model->kodelelang = $row->penawars;
                    // $model->kodelelang = $row->penawaran;
                    // $model->kodelelang = $row->nego_asli;
                    // $model->kodelelang = $row->negosiasi;
                    // $model->kodelelang = $row->status_nego;
                    // $model->kodelelang = $row->selisih;
                    $model->step = $row->tahap;
                    // $model->kodelelang = $row->opd_stk_id;

                    //Data PBJ yang belum ada dari API
                    // 'namecode' => Yii::t('rup', 'PBJ'),
                    // $model->sppbj_no = ''; //$row->sppbj_no;
                    // 'contract_no' => Yii::t('rup', 'Contract Number'),
                    // 'contract_date' => Yii::t('rup', 'Contract Date'),
                    // 'contract_duration' => Yii::t('rup', 'Contract Duration'),
                    // 'contract_price' => Yii::t('rup', 'Contract Price'),
                    // 'contract_start_date' => Yii::t('rup', 'Contract Start Date'),
                    // 'contract_end_date
                    // 'program' => Yii::t('rup', 'Program'),
                    // 'activity' => Yii::t('rup', 'Activity'),
                    // 'sub_activity' => Yii::t('rup', 'Sub Activity'),
                    // 'idmetodepengadaan' => Yii::t('rup', 'Metode Pengadaan'),
                    // 'metodepengadaan' => Yii::t('rup', 'Metode Pengadaan'),
                    // 'ppk_id' => Yii::t('rup', 'PPK'),
                    // 'ppk_nip' => Yii::t('rup', 'NIP PPK'),

                    // Data PBJ yang harus diisi
                    // 'name' => Yii::t('rup', 'PBJ Name'),
                    // 'namecode' => Yii::t('rup', 'PBJ'),
                    // 'budget' => Yii::t('rup', 'Budget Price'),
                    // 'hps' => Yii::t('rup', 'Budget Own Estimate Price'),
                    // 'vendor_name' => Yii::t('rup', 'Vendor'),
                    // 'sppbj_no' => Yii::t('rup', 'SPPBJ Number'),
                    // 'contract_no' => Yii::t('rup', 'Contract Number'),
                    // 'contract_date' => Yii::t('rup', 'Contract Date'),
                    // 'contract_duration' => Yii::t('rup', 'Contract Duration'),
                    // 'contract_price' => Yii::t('rup', 'Contract Price'),
                    // 'contract_start_date' => Yii::t('rup', 'Contract Start Date'),
                    // 'contract_end_date' => Yii::t('rup', 'Contract End Date'),
                    // 'work_start_date' => Yii::t('rup', 'Work Start Date'),
                    // 'work_finished_date' => Yii::t('rup', 'Work Finished Date'),
                    // 'program' => Yii::t('rup', 'Program'),
                    // 'activity' => Yii::t('rup', 'Activity'),
                    // 'sub_activity' => Yii::t('rup', 'Sub Activity'),
                    // 'namasatker' => Yii::t('rup', 'Namasatker'),
                    // 'kodesatker' => Yii::t('rup', 'Kodesatker'),
                    // 'idmetodepengadaan' => Yii::t('rup', 'Metode Pengadaan'),
                    // 'metodepengadaan' => Yii::t('rup', 'Metode Pengadaan'),
                    // 'ppk_id' => Yii::t('rup', 'PPK'),
                    // 'ppk_name' => Yii::t('rup', 'PPK'),
                    // 'ppk_nip' => Yii::t('rup', 'NIP PPK'),
                    // 'fiscal_year' => Yii::t('rup', 'Tahunanggaran'),

                    // id_tender	"9938104"
                    // opd	"BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH"
                    // rup_stk_id	"174856"
                    // id_rup	46694619
                    // id_paket	"11301104"
                    // nama_paket	"Belanja Jasa Konsultansi Berorientasi Bidang- Keuangan Penyusunan Laporan Atas Belanja Daerah"
                    // pagu_paket	"200000000.00"
                    // hps_paket	"199911000.00"
                    // tanggal_buat	"2024-01-03 16:13:37"
                    // jenis_pengadaan	"Jasa Konsultansi BU Non Konstruksi"
                    // kepanitiaan	"Pokja Pemilihan Belanja Jasa Konsultansi Berorientasi Bidang- Keuangan Penyusunan Laporan Atas Belanja Daerah Pada Badan Pengelolaan Keuangan dan Aset Daerah Kota Surakarta TA 2024"
                    // tahun	"2024"
                    // ppk	"Tulus Widajat, SE, MSi"
                    // anggota_pokja	"Tri Hartini, Dimas Arif Yustianto, Jarot Dwi Raharjo, ST.-Pokja, Dina Dwi Prasetya, S.Sos, Rida Arinda Rochmawati, ST"
                    // pemenang	null
                    // pemenang_alamat	null
                    // penawars	""
                    // penawaran	null
                    // nego_asli	null
                    // negosiasi	null
                    // status_nego	"Belum Ada Pemenang"
                    // selisih	null
                    // mulai	"2024-01-26 13:00:00"
                    // selesai	"2024-03-08 10:00:00"
                    // tahap	"Download Dokumen Pemilihan"
                    // opd_stk_id	"174856"
                    // opd_kode	"5.02.0.00.0.00.01.0000"
                    // 1

                    // untuk penyedia
                    // $model->tipe_rup = 1;
                    // $model->is_sync = true;

                    // $model->id = (int)$row->idrup;
                    // $model->idsatker = (string)$model->idsatker;
                    // $model->statusdeletepaket = (string)$model->statusdeletepaket;
                    // $model->statusaktifpaket = (string)$model->statusaktifpaket;
                    // $model->idjenispengadaan = (int)$model->idjenispengadaan;
                    // $model->tahunanggaran = (string)$model->tahunanggaran;
                    // $model->spesifikasi = $this->trim_me($model->spesifikasi,250);
                    // $model->namapaket = $this->trim_me($model->namapaket,250);
                    // $model->keterangan = $this->trim_me($model->keterangan,250);

                    try {
                        if ($model->save(false)) {
                            $saved[] = $model->kodelelang;
                        } else {
                            $model->validate();
                            $notsaved[] = [
                                'id' => $model->kodelelang,
                                'errors' => $model->errors,
                            ];
                        }
                    } catch (\Throwable $th) {
                        //throw $th;
                        $model->validate();
                        $notsaved[] = [
                            'id' => $model->kodelelang,
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