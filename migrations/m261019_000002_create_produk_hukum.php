<?php

use yii\db\Migration;

class m261019_000002_create_produk_hukum extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%master_produk_hukum}}', [
            'id' => $this->primaryKey(),
            'nama' => $this->string(100)->notNull(),
            'is_active' => $this->boolean()->notNull()->defaultValue(true),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
        $this->createIndex('uq_master_produk_hukum_nama', '{{%master_produk_hukum}}', 'nama', true);

        $this->createTable('{{%produk_hukum_tahunan}}', [
            'id' => $this->primaryKey(),
            'master_produk_hukum_id' => $this->integer()->notNull(),
            'tahun' => $this->smallInteger()->notNull(),
            'nama_file' => $this->string(255)->notNull(),
            'file_path' => $this->string(255)->notNull(),
            'mime_type' => $this->string(127)->null(),
            'file_size' => $this->integer()->null(),
            'is_public' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'deleted_at' => $this->dateTime()->null(),
            'legacy_dokumen_id' => $this->integer()->null(),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
        $this->createIndex('uq_produk_hukum_master_tahun', '{{%produk_hukum_tahunan}}', ['master_produk_hukum_id', 'tahun'], true);
        $this->createIndex('uq_produk_hukum_legacy', '{{%produk_hukum_tahunan}}', 'legacy_dokumen_id', true);
        $this->createIndex('idx_produk_hukum_publik_tahun', '{{%produk_hukum_tahunan}}', ['is_public', 'deleted_at', 'tahun']);
        $this->addForeignKey('fk_produk_hukum_master', '{{%produk_hukum_tahunan}}', 'master_produk_hukum_id', '{{%master_produk_hukum}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function safeDown()
    {
        if ((int) $this->db->createCommand('SELECT COUNT(*) FROM {{%produk_hukum_tahunan}}')->queryScalar() > 0) {
            throw new RuntimeException('Dokumen produk hukum sudah ada. Pulihkan dari backup untuk rollback data.');
        }
        $this->dropTable('{{%produk_hukum_tahunan}}');
        $this->dropTable('{{%master_produk_hukum}}');
    }
}
