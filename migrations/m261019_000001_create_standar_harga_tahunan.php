<?php

use yii\db\Migration;

class m261019_000001_create_standar_harga_tahunan extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%standar_harga_tahunan}}', [
            'id' => $this->primaryKey(),
            'tahun' => $this->smallInteger()->notNull(),
            'is_public' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'deleted_at' => $this->dateTime()->null(),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
        // MySQL 5.7 membolehkan beberapa NULL pada indeks unik.
        $this->execute('ALTER TABLE {{%standar_harga_tahunan}} ADD COLUMN active_tahun SMALLINT GENERATED ALWAYS AS (IF(deleted_at IS NULL, tahun, NULL)) STORED');
        $this->createIndex('uq_standar_harga_active_tahun', '{{%standar_harga_tahunan}}', 'active_tahun', true);

        $this->createTable('{{%standar_harga_dokumen}}', [
            'id' => $this->primaryKey(),
            'standar_harga_tahunan_id' => $this->integer()->notNull(),
            'jenis' => $this->string(4)->notNull(),
            'nama_file' => $this->string(255)->notNull(),
            'file_path' => $this->string(255)->notNull(),
            'mime_type' => $this->string(127)->null(),
            'file_size' => $this->integer()->null(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'deleted_at' => $this->dateTime()->null(),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
        $this->execute("ALTER TABLE {{%standar_harga_dokumen}} ADD COLUMN active_jenis VARCHAR(4) GENERATED ALWAYS AS (IF(deleted_at IS NULL, jenis, NULL)) STORED");
        $this->createIndex('uq_standar_harga_dokumen_active', '{{%standar_harga_dokumen}}', ['standar_harga_tahunan_id', 'active_jenis'], true);
        $this->createIndex('idx_standar_harga_dokumen_tahunan', '{{%standar_harga_dokumen}}', 'standar_harga_tahunan_id');
        $this->addForeignKey('fk_standar_harga_dokumen_tahunan', '{{%standar_harga_dokumen}}', 'standar_harga_tahunan_id', '{{%standar_harga_tahunan}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('{{%standar_harga_dokumen}}');
        $this->dropTable('{{%standar_harga_tahunan}}');
    }
}
