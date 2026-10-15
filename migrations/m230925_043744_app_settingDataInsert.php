<?php

use yii\db\Schema;
use yii\db\Migration;

class m230925_043744_app_settingDataInsert extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->batchInsert('{{%app_setting}}',
                           ["id", "name", "hints", "segment_id", "segment_name", "input_type_id", "input_data_type", "data_content", "size", "row", "sort", "status", "key", "value"],
                            [
    [
        'id' => '1',
        'name' => 'Company Name',
        'hints' => 'Input your company name',
        'segment_id' => '1',
        'segment_name' => 'General',
        'input_type_id' => '1',
        'input_data_type' => 'Single Input',
        'data_content' => null,
        'size' => '6',
        'row' => null,
        'sort' => '10',
        'status' => 'Active',
        'key' => 'company_name',
        'value' => 'Yii2 Template BS5',
    ],
    [
        'id' => '2',
        'name' => 'Email',
        'hints' => 'Input your company email',
        'segment_id' => '3',
        'segment_name' => 'Messaging',
        'input_type_id' => '1',
        'input_data_type' => 'Single Input',
        'data_content' => null,
        'size' => '5',
        'row' => '0',
        'sort' => '10',
        'status' => 'Active',
        'key' => 'company_email',
        'value' => 'admin@infinindotech.com',
    ],
    [
        'id' => '3',
        'name' => 'Address',
        'hints' => 'Input yout company address',
        'segment_id' => '2',
        'segment_name' => 'Display',
        'input_type_id' => '1',
        'input_data_type' => 'Single Input',
        'data_content' => null,
        'size' => '5',
        'row' => '0',
        'sort' => '10',
        'status' => 'Active',
        'key' => 'company_address',
        'value' => 'Jl. Cocak I nomor 17, Surakarta',
    ],
]
        );
    }

    public function safeDown()
    {
        //$this->truncateTable('{{%app_setting}} CASCADE');
    }
}
