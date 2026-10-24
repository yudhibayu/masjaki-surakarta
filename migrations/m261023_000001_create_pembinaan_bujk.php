<?php

use yii\db\Migration;

class m261023_000001_create_pembinaan_bujk extends Migration
{
    public function safeUp()
    {
        $bujk = $this->db->getTableSchema('business_entity', true);
        if ($bujk === null || !isset($bujk->columns['id']) || !$bujk->columns['id']->isPrimaryKey) {
            throw new RuntimeException('Tabel business_entity dengan primary key id harus tersedia sebelum migration pembinaan BUJK dijalankan.');
        }
        $bujkIdType = strtolower($bujk->columns['id']->dbType);
        if (strpos($bujkIdType, 'bigint') === 0) {
            $businessEntityId = $this->bigInteger()->null();
        } elseif (strpos($bujkIdType, 'int') === 0) {
            $businessEntityId = $this->integer()->null();
        } else {
            throw new RuntimeException('Tipe business_entity.id harus diperiksa sebelum menambahkan relasi pembinaan.');
        }
        if (strpos($bujkIdType, 'unsigned') !== false) {
            $businessEntityId->unsigned();
        }

        $options = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';

        $this->createTable('{{%masyarakat_jasa_konstruksi}}', [
            'id' => $this->primaryKey(),
            'jenis_subjek' => $this->string(20)->notNull(),
            'nama' => $this->string(200)->notNull(),
            'nik' => $this->string(16)->null(),
            'instansi' => $this->string(180)->null(),
            'jabatan' => $this->string(150)->null(),
            'alamat' => $this->text()->null(),
            'nomor_telepon' => $this->string(30)->null(),
            'is_active' => $this->boolean()->notNull()->defaultValue(true),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'deleted_at' => $this->dateTime()->null(),
        ], $options);
        $this->createIndex('idx_masyarakat_jk_active', '{{%masyarakat_jasa_konstruksi}}', ['is_active', 'deleted_at', 'nama']);
        $this->createIndex('idx_masyarakat_jk_nik', '{{%masyarakat_jasa_konstruksi}}', 'nik');

        $this->createTable('{{%jenis_kegiatan_pembinaan}}', [
            'id' => $this->primaryKey(),
            'nama' => $this->string(120)->notNull(),
            'is_active' => $this->boolean()->notNull()->defaultValue(true),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'deleted_at' => $this->dateTime()->null(),
        ], $options);
        $this->createIndex('idx_jenis_pembinaan_nama', '{{%jenis_kegiatan_pembinaan}}', 'nama');

        $this->createTable('{{%kegiatan_pembinaan}}', [
            'id' => $this->primaryKey(),
            'jenis_kegiatan_id' => $this->integer()->notNull(),
            'tahun_anggaran' => $this->smallInteger()->notNull(),
            'nama_kegiatan' => $this->string(200)->notNull(),
            'tanggal_mulai' => $this->date()->null(),
            'tanggal_selesai' => $this->date()->null(),
            'lokasi' => $this->string(255)->null(),
            'penyelenggara' => $this->string(180)->null(),
            'keterangan' => $this->text()->null(),
            'is_published' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'deleted_at' => $this->dateTime()->null(),
        ], $options);
        $this->createIndex('idx_kegiatan_pembinaan_tahun_publikasi', '{{%kegiatan_pembinaan}}', ['tahun_anggaran', 'is_published', 'deleted_at']);
        $this->createIndex('idx_kegiatan_pembinaan_jenis', '{{%kegiatan_pembinaan}}', 'jenis_kegiatan_id');
        $this->addForeignKey('fk_kegiatan_pembinaan_jenis', '{{%kegiatan_pembinaan}}', 'jenis_kegiatan_id', '{{%jenis_kegiatan_pembinaan}}', 'id', 'RESTRICT', 'CASCADE');

        $this->createTable('{{%peserta_pembinaan}}', [
            'id' => $this->primaryKey(),
            'kegiatan_id' => $this->integer()->notNull(),
            'id_business_entity' => $businessEntityId,
            'masyarakat_jasa_konstruksi_id' => $this->integer()->null(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'deleted_at' => $this->dateTime()->null(),
            'active_business_entity_id' => $this->integer()->append('GENERATED ALWAYS AS (CASE WHEN deleted_at IS NULL THEN id_business_entity ELSE NULL END) STORED'),
            'active_masyarakat_id' => $this->integer()->append('GENERATED ALWAYS AS (CASE WHEN deleted_at IS NULL THEN masyarakat_jasa_konstruksi_id ELSE NULL END) STORED'),
        ], $options);
        $this->createIndex('uq_peserta_pembinaan_bujk_aktif', '{{%peserta_pembinaan}}', ['kegiatan_id', 'active_business_entity_id'], true);
        $this->createIndex('uq_peserta_pembinaan_masyarakat_aktif', '{{%peserta_pembinaan}}', ['kegiatan_id', 'active_masyarakat_id'], true);
        $this->createIndex('idx_peserta_pembinaan_kegiatan', '{{%peserta_pembinaan}}', 'kegiatan_id');
        $this->createIndex('idx_peserta_pembinaan_bujk', '{{%peserta_pembinaan}}', 'id_business_entity');
        $this->createIndex('idx_peserta_pembinaan_masyarakat', '{{%peserta_pembinaan}}', 'masyarakat_jasa_konstruksi_id');
        $this->addForeignKey('fk_peserta_pembinaan_kegiatan', '{{%peserta_pembinaan}}', 'kegiatan_id', '{{%kegiatan_pembinaan}}', 'id', 'RESTRICT', 'CASCADE');
        // Primary keys are immutable in the application. RESTRICT avoids
        // MySQL 5.7's restriction on cascading updates to columns used by a
        // stored generated column (active_*_id).
        $this->addForeignKey('fk_peserta_pembinaan_bujk', '{{%peserta_pembinaan}}', 'id_business_entity', 'business_entity', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('fk_peserta_pembinaan_masyarakat', '{{%peserta_pembinaan}}', 'masyarakat_jasa_konstruksi_id', '{{%masyarakat_jasa_konstruksi}}', 'id', 'RESTRICT', 'RESTRICT');

        $this->createTable('{{%hasil_pembinaan}}', [
            'id' => $this->primaryKey(),
            'peserta_pembinaan_id' => $this->integer()->notNull(),
            'hasil_capaian' => $this->text()->null(),
            'memperoleh_sertifikat' => $this->boolean()->null(),
            'keterangan_tindak_lanjut' => $this->text()->null(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $options);
        $this->createIndex('uq_hasil_pembinaan_peserta', '{{%hasil_pembinaan}}', 'peserta_pembinaan_id', true);
        $this->addForeignKey('fk_hasil_pembinaan_peserta', '{{%hasil_pembinaan}}', 'peserta_pembinaan_id', '{{%peserta_pembinaan}}', 'id', 'RESTRICT', 'CASCADE');

        $this->createTable('{{%dokumen_kegiatan_pembinaan}}', [
            'id' => $this->primaryKey(),
            'kegiatan_id' => $this->integer()->notNull(),
            'jenis_dokumen' => $this->string(30)->notNull(),
            'nama_file' => $this->string(255)->notNull(),
            'file_path' => $this->string(100)->notNull(),
            'mime_type' => $this->string(120)->notNull(),
            'file_size' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
        ], $options);
        $this->createIndex('idx_dokumen_pembinaan_kegiatan', '{{%dokumen_kegiatan_pembinaan}}', 'kegiatan_id');
        $this->addForeignKey('fk_dokumen_pembinaan_kegiatan', '{{%dokumen_kegiatan_pembinaan}}', 'kegiatan_id', '{{%kegiatan_pembinaan}}', 'id', 'RESTRICT', 'CASCADE');

        $this->execute("CREATE TRIGGER trg_peserta_pembinaan_entity_insert BEFORE INSERT ON {{%peserta_pembinaan}} FOR EACH ROW BEGIN IF ((NEW.id_business_entity IS NULL) = (NEW.masyarakat_jasa_konstruksi_id IS NULL)) THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Exactly one participant master must be selected'; END IF; END");
        $this->execute("CREATE TRIGGER trg_peserta_pembinaan_entity_update BEFORE UPDATE ON {{%peserta_pembinaan}} FOR EACH ROW BEGIN IF ((NEW.id_business_entity IS NULL) = (NEW.masyarakat_jasa_konstruksi_id IS NULL)) THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Exactly one participant master must be selected'; END IF; END");
    }

    public function safeDown()
    {
        $tables = [
            '{{%dokumen_kegiatan_pembinaan}}', '{{%hasil_pembinaan}}', '{{%peserta_pembinaan}}',
            '{{%kegiatan_pembinaan}}', '{{%jenis_kegiatan_pembinaan}}', '{{%masyarakat_jasa_konstruksi}}',
        ];
        foreach ($tables as $table) {
            if ((int) $this->db->createCommand('SELECT COUNT(*) FROM ' . $table)->queryScalar() > 0) {
                throw new RuntimeException('Rollback ditolak karena data pembinaan sudah ada. Ekspor data terlebih dahulu.');
            }
        }
        $this->execute('DROP TRIGGER IF EXISTS trg_peserta_pembinaan_entity_insert');
        $this->execute('DROP TRIGGER IF EXISTS trg_peserta_pembinaan_entity_update');
        foreach ($tables as $table) {
            $this->dropTable($table);
        }
    }
}
