<?php

use yii\db\Migration;

class m261112_000001_add_jenis_produk_hukum_to_produk_hukum_tahunan extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%produk_hukum_tahunan}}', 'jenis_produk_hukum', $this->string(64)->null());
    }

    public function safeDown()
    {
        $count = (int) $this->db->createCommand(
            'SELECT COUNT(*) FROM {{%produk_hukum_tahunan}} WHERE jenis_produk_hukum IS NOT NULL'
        )->queryScalar();
        if ($count > 0) {
            throw new RuntimeException('Jenis produk hukum sudah diisi. Pulihkan dari backup untuk rollback data.');
        }

        $this->dropColumn('{{%produk_hukum_tahunan}}', 'jenis_produk_hukum');
    }
}
