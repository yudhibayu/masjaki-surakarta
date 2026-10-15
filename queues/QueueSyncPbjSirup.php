<?php

namespace app\queues;

use app\modules\data_sync\models\SyncLog;
use app\modules\rup\models\Pbj;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class QueueSyncPbjSirup extends BaseObject implements JobInterface
{
    public $year;
    public $start = 0;
    public $sync_log_id = null;
    public $pbj_type = Pbj::TIPE_PBJ_TENDER;

    private const PAGE_SIZE = 100;
    private const URL = 'https://sirup.inaproc.id/sirup/caripaketctr/search';
    private const DETAIL_URL = 'https://sirup.inaproc.id/sirup/rup/detailPaketPenyedia2020?idPaket=';

    public function execute($queue)
    {
        $year = (int) $this->year;
        if ($year < 2000 || $year > 2100 || (int) $this->start < 0
            || !in_array((int) $this->pbj_type, [Pbj::TIPE_PBJ_TENDER, Pbj::TIPE_PBJ_NONTENDER], true)) {
            throw new \InvalidArgumentException('Parameter sinkronisasi SIRUP tidak valid.');
        }

        if ($this->sync_log_id === null) {
            $syncLog = new SyncLog();
            $syncLog->name = 'Sync ' . ($this->pbj_type == Pbj::TIPE_PBJ_TENDER ? 'Tender' : 'Non Tender') . ' PBJ SIRUP ' . $year;
            $syncLog->record_total = 0;
            $syncLog->queue_total = 1;
            $syncLog->queue_finished = 0;
            if (!$syncLog->save()) {
                throw new \RuntimeException('Gagal membuat log sinkronisasi SIRUP.');
            }
        } else {
            $syncLog = SyncLog::findOne($this->sync_log_id);
            if ($syncLog === null) {
                throw new \RuntimeException('Log sinkronisasi SIRUP tidak ditemukan.');
            }
        }

        $result = $this->fetchPage($year, (int) $this->start);
        $rows = $result['data'];
        $total = (int) $result['recordsFiltered'];

        if ($total > $this->start && count($rows) === 0) {
            throw new \RuntimeException('Halaman SIRUP kosong sebelum seluruh data diterima.');
        }

        $saved = 0;
        foreach ($rows as $row) {
            if ($this->saveRow($row, $year)) {
                $saved++;
            }
        }
        $syncLog->record_total = (int) $syncLog->record_total + $saved;
        if (!$syncLog->save()) {
            throw new \RuntimeException('Gagal memperbarui jumlah data SIRUP.');
        }

        $nextStart = (int) $this->start + count($rows);
        if ($nextStart < $total) {
            $syncLog->queue_total = (int) $syncLog->queue_total + 1;
            if (!$syncLog->save()) {
                throw new \RuntimeException('Gagal memperbarui jumlah antrean SIRUP.');
            }
            Yii::$app->queue->push(new self([
                'year' => $year,
                'start' => $nextStart,
                'sync_log_id' => $syncLog->id,
                'pbj_type' => $this->pbj_type,
            ]));
        }

        $syncLog->queue_finished = (int) $syncLog->queue_finished + 1;
        if (!$syncLog->save()) {
            throw new \RuntimeException('Gagal memperbarui status sinkronisasi SIRUP.');
        }
    }

    private function fetchPage($year, $start)
    {
        $columns = ['', 'paket', 'pagu', 'jenisPengadaan', 'isPDN', 'isUMK',
            'metode', 'pemilihan', 'kldi', 'satuanKerja', 'lokasi', 'id'];
        $params = [
            'tahunAnggaran' => $year,
            'jenisPengadaan' => 2,
            'metodePengadaan' => $this->pbj_type == Pbj::TIPE_PBJ_TENDER ? 13 : '',
            'minPagu' => '',
            'maxPagu' => '',
            'bulan' => '',
            'lokasi' => 14798,
            'kldi' => 427, // ID filter SIRUP untuk KLDI D147 (Kota Surakarta).
            'pdn' => '',
            'ukm' => '',
            'draw' => 1,
        ];
        foreach ($columns as $index => $name) {
            $params['columns'][$index] = [
                'data' => $name,
                'name' => '',
                'searchable' => $index === 0 ? 'false' : 'true',
                'orderable' => $index === 0 ? 'false' : 'true',
                'search' => ['value' => '', 'regex' => 'false'],
            ];
        }
        // ID paket memberi urutan stabil saat hasil diproses beberapa halaman.
        $params['order'] = [['column' => 11, 'dir' => 'ASC']];
        $params['start'] = $start;
        $params['length'] = self::PAGE_SIZE;
        $params['search'] = ['value' => '', 'regex' => 'false'];

        $curl = curl_init(self::URL . '?' . http_build_query($params, '', '&', PHP_QUERY_RFC3986));
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json, text/javascript, */*; q=0.01',
                'X-Requested-With: XMLHttpRequest',
                'Referer: https://sirup.inaproc.id/sirup/caripaketctr',
            ],
            CURLOPT_USERAGENT => 'Mozilla/5.0',
        ]);
        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);

        if ($response === false || $status !== 200) {
            throw new \RuntimeException('Permintaan SIRUP gagal (HTTP ' . $status . '): ' . $error);
        }
        $result = json_decode($response, true);
        if (!is_array($result) || !array_key_exists('data', $result)
            || !isset($result['recordsFiltered']) || !is_numeric($result['recordsFiltered'])
            || ($result['data'] !== null && !is_array($result['data']))) {
            throw new \RuntimeException('Respons SIRUP tidak memiliki format DataTables yang diharapkan.');
        }
        $result['data'] = $result['data'] ?? [];
        return $result;
    }

    private function saveRow($row, $year)
    {
        if (!is_array($row)) {
            throw new \RuntimeException('Data paket SIRUP tidak valid.');
        }
        $method = $row['metode'] ?? null;
        if (($row['jenisPengadaan'] ?? null) !== 'Pekerjaan Konstruksi'
            || ($row['idKldi'] ?? null) !== 'D147'
            || ($this->pbj_type == Pbj::TIPE_PBJ_TENDER && $method !== 'Tender')
            || ($this->pbj_type == Pbj::TIPE_PBJ_NONTENDER
                && (!is_string($method) || in_array($method, ['Tender', 'Tender Cepat', 'Seleksi'], true)))) {
            return false;
        }
        if (empty($row['id']) || empty($row['paket'])
            || !isset($row['pagu'], $row['idSatker'])) {
            throw new \RuntimeException('Data paket SIRUP tidak lengkap.');
        }

        $rupId = (int) $row['id'];
        $model = Pbj::findOne(['kodelelang' => $rupId]);
        if ($model !== null && $model->rup_code != $rupId) {
            throw new \RuntimeException('Kode PBJ ' . $rupId . ' sudah dipakai paket lain.');
        }
        if ($model === null) {
            $model = Pbj::findOne(['rup_code' => $rupId, 'fiscal_year' => $year]);
        }
        $isNew = $model === null;
        if ($isNew) {
            $model = new Pbj();
            $model->kodelelang = $rupId;
            $model->rup_code = $rupId;
        }
        if (!$isNew && (int) $model->fiscal_year !== $year) {
            throw new \RuntimeException('Kode PBJ ' . $rupId . ' sudah dipakai tahun lain.');
        }

        // Data PBJ dari sumber lama dapat memiliki kode paket tersendiri.
        // Lengkapi kolom yang kosong tanpa menimpa rincian PBJ tersebut.
        $preserveExisting = !$isNew && (int) $model->kodelelang !== $rupId;
        $values = [
            'name' => trim($row['paket']),
            'budget' => $row['pagu'],
            'kodesatker' => (string) $row['idSatker'],
            'namasatker' => $row['satuanKerja'] ?? null,
            'idmetodepengadaan' => $row['idMetode'] ?? null,
            'metodepengadaan' => $row['metode'],
            'idjenispengadaan' => $row['idJenisPengadaan'] ?? 2,
            'jenispengadaan' => $row['jenisPengadaan'],
        ];
        foreach ($values as $field => $value) {
            if (!$preserveExisting || $model->$field === null || $model->$field === '') {
                $model->$field = $value;
            }
        }
        if ($model->source_of_funds === null) {
            $model->source_of_funds = $this->fetchSourceOfFunds($rupId);
        }
        if (!$preserveExisting) {
            $model->pbj_type = $this->pbj_type;
            $model->fiscal_year = $year;
        }
        $model->is_sync = true;
        if (!$model->save()) {
            throw new \RuntimeException('Gagal menyimpan paket SIRUP ' . $rupId . ': ' . json_encode($model->errors));
        }
        return true;
    }

    private function fetchSourceOfFunds($rupId)
    {
        $curl = curl_init(self::DETAIL_URL . $rupId);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 8,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'Mozilla/5.0',
        ]);
        $html = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($html === false || $status !== 200) {
            Yii::warning('Detail SIRUP ' . $rupId . ' tidak dapat dibaca (HTTP ' . $status . ').', __METHOD__);
            return null;
        }

        $document = new \DOMDocument();
        $previous = libxml_use_internal_errors(true);
        try {
            if (!$document->loadHTML($html)) {
                return null;
            }
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }
        $xpath = new \DOMXPath($document);
        $cells = $xpath->query('//td[contains(concat(" ", normalize-space(@class), " "), " label-left ")][normalize-space(.)="Sumber Dana"]/following-sibling::td[1]//tr[td]/td[2]');
        if ($cells === false || $cells->length === 0) {
            return null;
        }

        $names = [];
        foreach ($cells as $cell) {
            $name = strtoupper(trim($cell->textContent));
            if ($name !== '') {
                $names[$name] = true;
            }
        }
        // PBJ hanya memiliki satu FK sumber dana. Jangan pilih salah satu jika paket memakai beberapa sumber.
        if (count($names) !== 1) {
            return null;
        }
        $name = array_key_first($names);
        if ($name === 'APBDP') {
            $name = 'APBD PERUBAHAN';
        }
        $sourceId = Yii::$app->db->createCommand(
            'SELECT id FROM source_of_funds WHERE UPPER(name) = :name LIMIT 1',
            [':name' => $name]
        )->queryScalar();
        return $sourceId === false ? null : (int) $sourceId;
    }
}
