<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWahaTemplates extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'key' => [ 'type' => 'VARCHAR', 'constraint' => 100 ],
            'name' => [ 'type' => 'VARCHAR', 'constraint' => 150 ],
            'template' => [ 'type' => 'TEXT' ],
            'enabled' => [ 'type' => 'TINYINT', 'constraint' => 1, 'default' => 1 ],
            'description' => [ 'type' => 'VARCHAR', 'constraint' => 255, 'null' => true ],
            'created_at' => [ 'type' => 'DATETIME', 'null' => true ],
            'updated_at' => [ 'type' => 'DATETIME', 'null' => true ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('key');
        $this->forge->createTable('waha_templates', true);
    }

    public function down()
    {
        $this->forge->dropTable('waha_templates', true);
    }
}