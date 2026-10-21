<?php

use yii\db\Migration;

class m261021_000001_create_rekap_sertifikasi_tkk extends Migration
{
    public function safeUp()
    {
        $options = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';

        $this->createTable('{{%tenaga_kerja_konstruksi}}', [
            'id' => $this->primaryKey(),
            'nik' => $this->string(16)->notNull(),
            'nama_lengkap' => $this->string(150)->notNull(),
            'jenis_kelamin' => $this->string(1)->null(),
            'tanggal_lahir' => $this->date()->null(),
            'alamat' => $this->text()->null(),
            'nomor_telepon' => $this->string(30)->null(),
            'pendidikan_terakhir' => $this->string(100)->null(),
            'asal_instansi' => $this->string(150)->null(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $options);
        $this->createIndex('uq_tkk_nik', '{{%tenaga_kerja_konstruksi}}', 'nik', true);
        $this->createIndex('idx_tkk_nama', '{{%tenaga_kerja_konstruksi}}', 'nama_lengkap');

        $this->createTable('{{%master_lsp}}', [
            'id' => $this->primaryKey(),
            'nama_lsp' => $this->string(180)->notNull(),
            'is_active' => $this->boolean()->notNull()->defaultValue(true),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $options);
        $this->createIndex('uq_tkk_lsp_nama', '{{%master_lsp}}', 'nama_lsp', true);

        $this->createTable('{{%kegiatan_fasilitasi_sertifikasi}}', [
            'id' => $this->primaryKey(),
            'nama_kegiatan' => $this->string(200)->notNull(),
            'tahun_anggaran' => $this->smallInteger()->notNull(),
            'tanggal_mulai' => $this->date()->notNull(),
            'tanggal_selesai' => $this->date()->notNull(),
            'lokasi_kegiatan' => $this->string(255)->null(),
            'lsp_id' => $this->integer()->notNull(),
            'keterangan' => $this->text()->null(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $options);
        $this->createIndex('idx_tkk_kegiatan_tahun', '{{%kegiatan_fasilitasi_sertifikasi}}', 'tahun_anggaran');
        $this->createIndex('idx_tkk_kegiatan_lsp', '{{%kegiatan_fasilitasi_sertifikasi}}', 'lsp_id');
        $this->addForeignKey('fk_tkk_kegiatan_lsp', '{{%kegiatan_fasilitasi_sertifikasi}}', 'lsp_id', '{{%master_lsp}}', 'id', 'RESTRICT', 'CASCADE');

        $this->createTable('{{%peserta_fasilitasi_sertifikasi}}', [
            'id' => $this->primaryKey(),
            'tenaga_kerja_id' => $this->integer()->notNull(),
            'kegiatan_id' => $this->integer()->notNull(),
            'jabatan_kerja' => $this->string(150)->notNull(),
            'jenjang_kualifikasi' => $this->string(100)->null(),
            'skema_sertifikasi' => $this->string(180)->notNull(),
            'jenis_fasilitasi' => $this->string(30)->notNull(),
            'status_sertifikasi' => $this->string(30)->notNull(),
            'nomor_skk' => $this->string(120)->null(),
            'tanggal_terbit_skk' => $this->date()->null(),
            'masa_berlaku_skk_hingga' => $this->date()->null(),
            'keterangan' => $this->text()->null(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $options);
        $this->createIndex('idx_tkk_peserta_tenaga', '{{%peserta_fasilitasi_sertifikasi}}', 'tenaga_kerja_id');
        $this->createIndex('idx_tkk_peserta_kegiatan', '{{%peserta_fasilitasi_sertifikasi}}', 'kegiatan_id');
        $this->createIndex('idx_tkk_peserta_status', '{{%peserta_fasilitasi_sertifikasi}}', 'status_sertifikasi');
        $this->createIndex('idx_tkk_peserta_jabatan', '{{%peserta_fasilitasi_sertifikasi}}', 'jabatan_kerja');
        $this->createIndex('idx_tkk_peserta_skema', '{{%peserta_fasilitasi_sertifikasi}}', 'skema_sertifikasi');
        $this->addForeignKey('fk_tkk_peserta_tenaga', '{{%peserta_fasilitasi_sertifikasi}}', 'tenaga_kerja_id', '{{%tenaga_kerja_konstruksi}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_tkk_peserta_kegiatan', '{{%peserta_fasilitasi_sertifikasi}}', 'kegiatan_id', '{{%kegiatan_fasilitasi_sertifikasi}}', 'id', 'RESTRICT', 'CASCADE');

        $this->createTable('{{%dokumen_peserta_sertifikasi}}', [
            'id' => $this->primaryKey(),
            'peserta_sertifikasi_id' => $this->integer()->notNull(),
            'jenis_dokumen' => $this->string(30)->notNull(),
            'nama_file' => $this->string(255)->notNull(),
            'file_path' => $this->string(255)->notNull(),
            'mime_type' => $this->string(100)->notNull(),
            'file_size' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
        ], $options);
        $this->createIndex('idx_tkk_dokumen_peserta', '{{%dokumen_peserta_sertifikasi}}', 'peserta_sertifikasi_id');
        $this->addForeignKey('fk_tkk_dokumen_peserta', '{{%dokumen_peserta_sertifikasi}}', 'peserta_sertifikasi_id', '{{%peserta_fasilitasi_sertifikasi}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function safeDown()
    {
        foreach (['dokumen_peserta_sertifikasi', 'peserta_fasilitasi_sertifikasi', 'kegiatan_fasilitasi_sertifikasi', 'master_lsp', 'tenaga_kerja_konstruksi'] as $table) {
            if ((int) $this->db->createCommand('SELECT COUNT(*) FROM {{%' . $table . '}}')->queryScalar() > 0) {
                throw new RuntimeException('Rollback ditolak: data modul TKK sudah ada. Cadangkan dan pindahkan data terlebih dahulu.');
            }
        }
        $this->dropTable('{{%dokumen_peserta_sertifikasi}}');
        $this->dropTable('{{%peserta_fasilitasi_sertifikasi}}');
        $this->dropTable('{{%kegiatan_fasilitasi_sertifikasi}}');
        $this->dropTable('{{%master_lsp}}');
        $this->dropTable('{{%tenaga_kerja_konstruksi}}');
    }
}
