<?php

use yii\db\Migration;

class m261024_000001_create_pengawasan_tertib extends Migration
{
    public function safeUp()
    {
        $task = $this->db->getTableSchema('{{%monitoring_task}}', true);
        if ($task === null || !isset($task->columns['id']) || !$task->columns['id']->isPrimaryKey) {
            throw new RuntimeException('Tabel monitoring_task dengan primary key id harus tersedia sebelum migration pengawasan tertib dijalankan.');
        }

        $idType = strtolower($task->columns['id']->dbType);
        if (strpos($idType, 'bigint') === 0) {
            $taskId = $this->bigInteger();
        } elseif (strpos($idType, 'int') === 0) {
            $taskId = $this->integer();
        } else {
            throw new RuntimeException('Tipe monitoring_task.id harus diperiksa sebelum menambahkan relasi pengawasan tertib.');
        }
        if (strpos($idType, 'unsigned') !== false) {
            $taskId->unsigned();
        }

        $options = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';
        $this->createTable('{{%pengawasan_tertib}}', [
            'id' => $this->primaryKey(),
            'monitoring_task_id' => $taskId->notNull(),
            'tanggal_pengawasan' => $this->date()->notNull(),
            'metode_pengawasan' => $this->string(30)->notNull(),
            'uraian' => $this->text()->notNull(),
            'status_pelaksanaan' => $this->string(30)->notNull()->defaultValue('TERJADWAL'),
            'is_public' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
        ], $options);
        $this->createIndex('idx_pengawasan_tertib_task_public', '{{%pengawasan_tertib}}', ['monitoring_task_id', 'is_public']);
        $this->createIndex('idx_pengawasan_tertib_tanggal', '{{%pengawasan_tertib}}', 'tanggal_pengawasan');
        $this->addForeignKey('fk_pengawasan_tertib_task', '{{%pengawasan_tertib}}', 'monitoring_task_id', '{{%monitoring_task}}', 'id', 'RESTRICT', 'CASCADE');

        $this->createTable('{{%pengawasan_tertib_checklist}}', [
            'id' => $this->primaryKey(),
            'pengawasan_id' => $this->integer()->notNull(),
            'nama_persyaratan' => $this->string(200)->notNull(),
            'status_pemeriksaan' => $this->string(30)->notNull(),
            'catatan' => $this->text()->null(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
        ], $options);
        $this->createIndex('idx_pengawasan_tertib_checklist', '{{%pengawasan_tertib_checklist}}', 'pengawasan_id');
        $this->addForeignKey('fk_pengawasan_tertib_checklist', '{{%pengawasan_tertib_checklist}}', 'pengawasan_id', '{{%pengawasan_tertib}}', 'id', 'RESTRICT', 'CASCADE');

        $this->createTable('{{%pengawasan_tertib_temuan}}', [
            'id' => $this->primaryKey(),
            'pengawasan_id' => $this->integer()->notNull(),
            'kategori' => $this->string(120)->notNull(),
            'uraian' => $this->text()->notNull(),
            'rekomendasi' => $this->text()->null(),
            'prioritas' => $this->string(20)->notNull()->defaultValue('NORMAL'),
            'status_tindak_lanjut' => $this->string(20)->notNull()->defaultValue('BELUM'),
            'catatan_internal' => $this->text()->null(),
            'is_public' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
        ], $options);
        $this->createIndex('idx_pengawasan_tertib_temuan', '{{%pengawasan_tertib_temuan}}', ['pengawasan_id', 'is_public']);
        $this->createIndex('idx_pengawasan_tertib_temuan_status', '{{%pengawasan_tertib_temuan}}', 'status_tindak_lanjut');
        $this->addForeignKey('fk_pengawasan_tertib_temuan', '{{%pengawasan_tertib_temuan}}', 'pengawasan_id', '{{%pengawasan_tertib}}', 'id', 'RESTRICT', 'CASCADE');

        $this->createTable('{{%pengawasan_tertib_tindak_lanjut}}', [
            'id' => $this->primaryKey(),
            'temuan_id' => $this->integer()->notNull(),
            'tanggal_tindak_lanjut' => $this->date()->notNull(),
            'uraian_tindakan' => $this->text()->notNull(),
            'hasil_verifikasi' => $this->text()->null(),
            'status' => $this->string(20)->notNull(),
            'catatan_internal' => $this->text()->null(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->null(),
        ], $options);
        $this->createIndex('idx_pengawasan_tertib_tindak_lanjut', '{{%pengawasan_tertib_tindak_lanjut}}', ['temuan_id', 'tanggal_tindak_lanjut']);
        $this->addForeignKey('fk_pengawasan_tertib_tindak_lanjut', '{{%pengawasan_tertib_tindak_lanjut}}', 'temuan_id', '{{%pengawasan_tertib_temuan}}', 'id', 'RESTRICT', 'CASCADE');

        $this->createTable('{{%pengawasan_tertib_dokumen}}', [
            'id' => $this->primaryKey(),
            'pengawasan_id' => $this->integer()->notNull(),
            'jenis_dokumen' => $this->string(80)->notNull(),
            'nama_file' => $this->string(255)->notNull(),
            'file_path' => $this->string(80)->notNull(),
            'mime_type' => $this->string(100)->notNull(),
            'file_size' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->null(),
        ], $options);
        $this->createIndex('idx_pengawasan_tertib_dokumen', '{{%pengawasan_tertib_dokumen}}', 'pengawasan_id');
        $this->createIndex('uq_pengawasan_tertib_dokumen_path', '{{%pengawasan_tertib_dokumen}}', 'file_path', true);
        $this->addForeignKey('fk_pengawasan_tertib_dokumen', '{{%pengawasan_tertib_dokumen}}', 'pengawasan_id', '{{%pengawasan_tertib}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function safeDown()
    {
        foreach ([
            'pengawasan_tertib_dokumen',
            'pengawasan_tertib_tindak_lanjut',
            'pengawasan_tertib_temuan',
            'pengawasan_tertib_checklist',
            'pengawasan_tertib',
        ] as $table) {
            if ((int) $this->db->createCommand('SELECT COUNT(*) FROM {{%' . $table . '}}')->queryScalar() > 0) {
                throw new RuntimeException('Rollback ditolak karena data pengawasan tertib sudah ada. Ekspor data terlebih dahulu.');
            }
        }

        $this->dropTable('{{%pengawasan_tertib_dokumen}}');
        $this->dropTable('{{%pengawasan_tertib_tindak_lanjut}}');
        $this->dropTable('{{%pengawasan_tertib_temuan}}');
        $this->dropTable('{{%pengawasan_tertib_checklist}}');
        $this->dropTable('{{%pengawasan_tertib}}');
    }
}
