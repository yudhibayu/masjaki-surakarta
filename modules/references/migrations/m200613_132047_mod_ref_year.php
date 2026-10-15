<?php

use yii\db\Schema;
use yii\db\Migration;

class m200613_132047_mod_ref_year extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(
            '{{%mod_ref_year}}',
            [
                'id'=> $this->primaryKey(5),
                'nama'=> $this->integer(5)->null()->defaultValue(null),
                'aktif'=> "enum('F', 'T') NULL DEFAULT 'F'",
            ],$tableOptions
        );

    }

    public function safeDown()
    {
        $this->dropTable('{{%mod_ref_year}}');
    }
}
