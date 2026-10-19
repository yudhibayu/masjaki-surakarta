<?php

use yii\db\Migration;

class m261019_000003_migrate_standar_harga_data extends Migration
{
    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');
        foreach (['HSD', 'AHSP', 'HSPK'] as $name) {
            if (!$this->db->createCommand('SELECT id FROM {{%master_produk_hukum}} WHERE nama=:nama', [':nama' => $name])->queryScalar()) {
                $this->insert('{{%master_produk_hukum}}', ['nama' => $name, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now]);
            }
        }
        if ($this->db->schema->getTableSchema($this->db->schema->getRawTableName('{{%standar_harga_tahunan}}'), true) === null) {
            return;
        }
        $yearsWithoutDocuments = (int) $this->db->createCommand(
            'SELECT COUNT(*) FROM {{%standar_harga_tahunan}} t LEFT JOIN {{%standar_harga_dokumen}} d ON d.standar_harga_tahunan_id=t.id WHERE d.id IS NULL'
        )->queryScalar();
        $archived = (int) $this->db->createCommand(
            'SELECT COUNT(*) FROM {{%standar_harga_tahunan}} WHERE deleted_at IS NOT NULL'
        )->queryScalar() + (int) $this->db->createCommand(
            'SELECT COUNT(*) FROM {{%standar_harga_dokumen}} WHERE deleted_at IS NOT NULL'
        )->queryScalar();
        if ($yearsWithoutDocuments || $archived) {
            throw new RuntimeException('Data tahun tanpa dokumen atau arsip soft delete perlu keputusan migrasi manual. Tidak ada tabel lama yang dihapus.');
        }
        $rows = $this->db->createCommand(
            'SELECT d.*, t.tahun, t.is_public, t.created_by, t.updated_by FROM {{%standar_harga_dokumen}} d JOIN {{%standar_harga_tahunan}} t ON t.id=d.standar_harga_tahunan_id ORDER BY d.id'
        )->queryAll();
        $directory = Yii::getAlias('@app/storage/standar-harga');
        $copied = [];
        try {
            foreach ($rows as $row) {
                if (!in_array($row['jenis'], ['HSD', 'AHSP', 'HSPK'], true)) {
                    throw new RuntimeException('Jenis dokumen lama tidak dikenal: ' . $row['jenis']);
                }
                $masterId = $this->db->createCommand('SELECT id FROM {{%master_produk_hukum}} WHERE nama=:nama', [':nama' => $row['jenis']])->queryScalar();
                $previous = $this->db->createCommand(
                    'SELECT id FROM {{%produk_hukum_tahunan}} WHERE legacy_dokumen_id=:id', [':id' => $row['id']]
                )->queryScalar();
                if ($previous) {
                    continue;
                }
                $conflict = $this->db->createCommand(
                    'SELECT id FROM {{%produk_hukum_tahunan}} WHERE master_produk_hukum_id=:master AND tahun=:tahun',
                    [':master' => $masterId, ':tahun' => $row['tahun']]
                )->queryScalar();
                if ($conflict) {
                    throw new RuntimeException('Konflik dokumen ' . $row['jenis'] . ' tahun ' . $row['tahun'] . '.');
                }
                if (!preg_match('/\A[a-f0-9]{32}\.(pdf|xls|xlsx)\z/', $row['file_path'])) {
                    throw new RuntimeException('Nama file lama tidak aman untuk dokumen ID ' . $row['id'] . '.');
                }
                $source = $directory . DIRECTORY_SEPARATOR . $row['file_path'];
                if (!is_file($source)) {
                    throw new RuntimeException('File lama tidak ditemukan untuk dokumen ID ' . $row['id'] . '.');
                }
                $newName = bin2hex(random_bytes(16)) . '.' . pathinfo($source, PATHINFO_EXTENSION);
                $target = $directory . DIRECTORY_SEPARATOR . $newName;
                if (!copy($source, $target)) {
                    throw new RuntimeException('Gagal menyalin file dokumen ID ' . $row['id'] . '.');
                }
                $copied[] = $target;
                $this->insert('{{%produk_hukum_tahunan}}', [
                    'master_produk_hukum_id' => $masterId,
                    'tahun' => $row['tahun'],
                    'nama_file' => $row['nama_file'],
                    'file_path' => $newName,
                    'mime_type' => $row['mime_type'],
                    'file_size' => filesize($target),
                    'is_public' => $row['is_public'],
                    'created_by' => $row['created_by'],
                    'updated_by' => $row['updated_by'],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at'],
                    'legacy_dokumen_id' => $row['id'],
                ]);
            }
        } catch (Throwable $error) {
            foreach ($copied as $path) {
                @unlink($path);
            }
            throw $error;
        }
    }

    public function safeDown()
    {
        throw new RuntimeException('Rollback data tidak otomatis karena dokumen baru mungkin telah diubah. Pulihkan dari backup.');
    }
}
