<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWahaConfig extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'key' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('key', true);
        $this->forge->createTable('waha_config', true);
    }

    public function down()
    {
        $this->forge->dropTable('waha_config', true);
    }
}