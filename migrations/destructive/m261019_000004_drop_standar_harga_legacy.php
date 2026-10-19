<?php

use yii\db\Migration;

class m261019_000004_drop_standar_harga_legacy extends Migration
{
    public function safeUp()
    {
        $oldCount = (int) $this->db->createCommand('SELECT COUNT(*) FROM {{%standar_harga_dokumen}}')->queryScalar();
        $mappedCount = (int) $this->db->createCommand('SELECT COUNT(*) FROM {{%produk_hukum_tahunan}} WHERE legacy_dokumen_id IS NOT NULL')->queryScalar();
        if ($oldCount !== $mappedCount) {
            throw new RuntimeException('Dokumen lama belum seluruhnya dipindahkan. Penghapusan dibatalkan.');
        }
        $unmapped = (int) $this->db->createCommand(
            'SELECT COUNT(*) FROM {{%standar_harga_dokumen}} d LEFT JOIN {{%produk_hukum_tahunan}} p ON p.legacy_dokumen_id=d.id WHERE p.id IS NULL'
        )->queryScalar();
        $archived = (int) $this->db->createCommand('SELECT COUNT(*) FROM {{%standar_harga_dokumen}} WHERE deleted_at IS NOT NULL')->queryScalar()
            + (int) $this->db->createCommand('SELECT COUNT(*) FROM {{%standar_harga_tahunan}} WHERE deleted_at IS NOT NULL')->queryScalar();
        if ($unmapped || $archived) {
            throw new RuntimeException('Ada dokumen belum dipindahkan atau arsip lama. Penghapusan dibatalkan.');
        }
        $orphanYears = (int) $this->db->createCommand(
            'SELECT COUNT(*) FROM {{%standar_harga_tahunan}} t LEFT JOIN {{%standar_harga_dokumen}} d ON d.standar_harga_tahunan_id=t.id WHERE d.id IS NULL'
        )->queryScalar();
        if ($orphanYears) {
            throw new RuntimeException('Ada tahun lama tanpa dokumen. Penghapusan memerlukan keputusan manual.');
        }
        $foreignKeys = (int) $this->db->createCommand(
            "SELECT COUNT(*) FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA=DATABASE() AND REFERENCED_TABLE_NAME IN ('standar_harga_tahunan','standar_harga_dokumen') AND TABLE_NAME NOT IN ('standar_harga_tahunan','standar_harga_dokumen')"
        )->queryScalar();
        if ($foreignKeys) {
            throw new RuntimeException('Ada foreign key lain ke tabel lama. Penghapusan dibatalkan.');
        }
        $this->dropTable('{{%standar_harga_dokumen}}');
        $this->dropTable('{{%standar_harga_tahunan}}');
    }

    public function safeDown()
    {
        throw new RuntimeException('DROP TABLE tidak dapat dipulihkan otomatis. Gunakan backup database.');
    }
}
