<?php

use yii\db\Migration;

class m261021_000002_add_tkk_kegiatan_publication extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%kegiatan_fasilitasi_sertifikasi}}', 'is_public',
            $this->boolean()->notNull()->defaultValue(false));
        $this->createIndex('idx_tkk_kegiatan_public_tahun',
            '{{%kegiatan_fasilitasi_sertifikasi}}', ['is_public', 'tahun_anggaran']);
    }

    public function safeDown()
    {
        if ((int) $this->db->createCommand(
            'SELECT COUNT(*) FROM {{%kegiatan_fasilitasi_sertifikasi}} WHERE is_public=1'
        )->queryScalar() > 0) {
            throw new RuntimeException('Rollback ditolak: masih ada kegiatan TKK yang diterbitkan.');
        }
        $this->dropIndex('idx_tkk_kegiatan_public_tahun', '{{%kegiatan_fasilitasi_sertifikasi}}');
        $this->dropColumn('{{%kegiatan_fasilitasi_sertifikasi}}', 'is_public');
    }
}
